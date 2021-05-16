<template>
    <v-layout column>
        <v-layout>
            <v-form ref="form" v-model="valid">
                <v-row class="xs12">
                    <v-col class="d-flex" cols="12" sm="12">
                        <v-text-field
                            label="Title"
                            prepend-icon="mdi-form-select"
                            v-model="post.title"
                            :rules="titleRules"
                            :counter="191"
                            outlined
                            required
                        />
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-text-field
                            label="Page slug(address)"
                            prepend-icon="mdi-form-select"
                            v-model="post.slug"
                            :rules="slugRules"
                            :counter="191"
                            outlined
                            required
                        />
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-text-field
                            label="Description"
                            prepend-icon="mdi-form-select"
                            v-model="post.description"
                            :rules="descriptionRules"
                            :counter="191"
                            outlined
                            required
                        />
                    </v-col>


                    <v-col class="d-flex" cols="12" sm="6">
                        <v-text-field
                            label="OG TITLE"
                            prepend-icon="mdi-form-select"
                            v-model="post.og_title"
                            :rules="ogTitleRules"
                            :counter="191"
                            outlined
                            required
                        />
                    </v-col>


                    <v-col class="d-flex" cols="12" sm="6">
                        <v-text-field
                            label="OG Description"
                            prepend-icon="mdi-form-select"
                            v-model="post.og_description"
                            :rules="ogDescriptionRules"
                            :counter="191"
                            outlined
                            required
                        />
                    </v-col>


                    <v-col class="d-flex" cols="12" sm="12">
                        <v-select
                            label="Taxonomy"
                            prepend-icon="mdi-form-select"
                            :items="taxonomies"
                            v-model="post.taxonomy"
                            outlined
                            required
                        ></v-select>
                    </v-col>


                    <v-col class="d-flex" cols="12" sm="12">
                        <v-file-input
                            v-model="image"
                            color="deep-purple accent-4"
                            accept="image/*"
                            label="Image"
                            placeholder="Select cover image"
                            prepend-icon="mdi-paperclip"
                            outlined

                            :show-size="1000"
                        >
                            <template v-slot:selection="{ index, text }">
                                <v-chip
                                    v-if="index < 2"
                                    color="deep-purple accent-4"
                                    dark
                                    label
                                    small
                                >
                                    {{ text }}
                                </v-chip>
                            </template>
                        </v-file-input>
                    </v-col>


                    <v-col class="d-flex" cols="12" sm="12">
                        <v-checkbox
                            v-model="post.is_featured"
                            :label="`Post is featured (at the top of blog index page)`"
                        />
                    </v-col>



                    <v-col class="d-flex" cols="12" sm="12">
                        <v-layout column>
                            <v-flex :style="{color: 'red'}" v-if="!validBody"><h4 :style="{'font-weight': 400}">Post body is to short!</h4></v-flex>
                            <text-editor v-model="post.body"/>
                        </v-layout>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-layout column>
                            <ImageManager/>
                        </v-layout>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-btn
                            color="info"
                            class="white--text"
                            @click="storeBlogPost"
                            :disabled="!isValidated"
                            :loading="uploading"
                        >
                            Store
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-layout>

    </v-layout>

</template>

<script>
import TextEditor from "../../misc/TextEditor";
import ImageManager from "../../misc/ImageManager";

export default {
    components: {ImageManager, TextEditor},
    data: function () {
        return {
            valid: false,
            taxonomies: null,
            uploading: false,
            image: undefined,
            blacklistedSlugs: [],
            post: {
                title: '',
                slug: '',
                description: '',
                og_title: '',
                og_description: '',
                taxonomy: '',
                body: '',
                is_featured: null,
            },
            titleRules: [
                v => !!v || 'Title is required',
                v => v.length <= 191 || 'Maximum 191 characters',
                v => v.length >= 3 || 'Minimum 3 characters',
            ],
            slugRules: [
                v => !!v || 'Slug is required',
                v => v.length <= 191 || 'Maximum 191 characters',
                v => /^[a-z0-9_]+(-[a-z0-9_]+)*$/.test(v) || 'Bad formatting. Allowed only characters of english alphabet and numbers'
            ],
            descriptionRules: [
                v => !!v || 'Description is required',
                v => v.length <= 191 || 'Maximum 191 characters',
                v => v.length >= 3 || 'Minimum 3 characters',
            ],
            bodyRules: [
                v => !!v || 'Body is required',
                v => v.length >= 3 || 'Minimum 3 characters',
            ],
            ogTitleRules: [
                v => !!v || 'OG TITLE is required',
                v => v.length <= 191 || 'Maximum 191 characters',
                v => v.length >= 3  || 'Minimum 3 characters',
            ],
            ogDescriptionRules: [
                v => !!v || 'OG DESCRIPTION is required',
                v => v.length <= 191 || 'Maximum 191 characters',
                v => v.length >= 3  || 'Minimum 3 characters',
            ],

        }
    },
    computed: {
        edited() {
            return false
        },
        validBody() {
            return this.post.body ? this.post.body.length >= 10 : false
        },
        isValidated() {
            return !!(this.valid && this.image && this.validBody);
        },
    },
    mounted() {
        this.getTaxonomies();
    },
    methods: {
        async getBlacklistedSlugs() {
            try {
                let res = await this.$http.get('/api/post/blacklist')
                this.blacklistedSlugs = res.data.blacklisted
            } catch (e) {}
        },
        async getTaxonomies() {
            try {
                let res = await this.$http.get('/api/post/taxonomies')

                this.taxonomies = res.data.taxonomies
            } catch (e) {}
        },
        async storeBlogPost() {
            let data = new FormData()
            this.uploading = true
            data.set('image', this.image)
            data.set('title', this.post.title)
            data.set('slug', this.post.slug)
            data.set('description', this.post.description)
            data.set('og_title', this.post.og_title)
            data.set('og_description', this.post.og_description)
            data.set('taxonomy', this.post.taxonomy)
            data.set('body', this.post.body)
            data.set('is_featured', + this.post.is_featured)

            try {
                let res = await this.$http.post('/api/post', data,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })

                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    this.clearInputs();
                    this.$refs.form.resetValidation();
                    this.$store.commit('adminUi/setBlogModal', false)
                    this.$emit('updateposts');
                }

                this.uploading = false

            } catch (e) {
                this.$refs.form.validate();
                // if(e.response.data.errors) {
                //     Object.keys(e.response.data.errors).forEach(i => {
                //         setTimeout(() => {
                //             this.$store.commit('snackbar/pushMessage', {
                //                 message: e.response.data.errors[i],
                //                 color: "error",
                //                 timeout: 5000,
                //                 right: true,
                //                 bottom: true
                //             })
                //         }, 500)
                //     })
                // } else if (e.response.data.message) {
                //     e.response.data.message.forEach(m => {
                //         this.$store.commit('snackbar/pushMessage', {
                //             message: m,
                //             color: "error",
                //             timeout: 5000,
                //             right: true,
                //             bottom: true
                //         })
                //     })
                // }

                this.uploading = false
            }
        },
        clearInputs() {
            this.post = {
                title: '',
                slug: '',
                description: '',
                og_title: '',
                og_description: '',
                taxonomy: '',
                body: '',
                is_featured: null
            }
            this.image = undefined
        }
    }
}
</script>

<style scoped>

</style>
