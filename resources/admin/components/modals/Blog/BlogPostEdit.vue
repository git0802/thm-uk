<template>
    <v-layout column>
        <v-layout>
            <h1>
                Editing: {{ selectedPost.title }}
            </h1>
        </v-layout>
        <v-divider/>
        <v-layout>
            <v-form v-model="valid">
                <v-row class="xs12" v-if="clonedPost">
                    <v-col class="d-flex" cols="12" sm="12">

                        <v-text-field
                            label="Title"
                            prepend-icon="mdi-form-select"
                            v-model="clonedPost.title"
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
                            v-model="clonedPost.slug"
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
                            v-model="clonedPost.description"
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
                            v-model="clonedPost.og_title"
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
                            v-model="clonedPost.og_description"
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
                            v-model="clonedPost.taxonomy"
                            outlined
                            required
                        ></v-select>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12" align-self="center" v-if="clonedPost.image">
                        Post has cover&nbsp;<a :href="clonedPost.image" target="_blank">image </a>. If you want to change it - upload new one below
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
                            v-model="clonedPost.is_featured"
                            :label="`Post is featured (at the top of blog index page)`"
                        />
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-layout column>
                            <v-flex :style="{color: 'red'}" v-if="!validBody"><h4 :style="{'font-weight': 400}">Post body is to short!</h4></v-flex>
                            <text-editor v-model="clonedPost.body"/>
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
                            :disabled="!isValidated  || uploading"
                            :loading="uploading"

                        >
                            Update
                        </v-btn>
                    </v-col>


                </v-row>
            </v-form>
        </v-layout>

    </v-layout>
</template>

<script>
import TextEditor from "../../misc/TextEditor";

export default {
    name: "BlogPostEdit",
    components: {TextEditor},
    props: ['selectedPost'],
    data: function () {
        return {
            taxonomies: null,
            valid: false,
            uploading: false,
            image: undefined,
            blacklistedSlugs: [],
            clonedPost: {...this.selectedPost},
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
                v => v.length >= 3 || 'Minimum 3 characters',
            ],
            ogDescriptionRules: [
                v => !!v || 'OG DESCRIPTION is required',
                v => v.length <= 191 || 'Maximum 191 characters',
                v => v.length >= 3 || 'Minimum 3 characters',
            ],
        }
    },
    computed: {
        edited() {
            return JSON.stringify(this.clonedPost) !== JSON.stringify(this.selectedPost);
        },
        parentDialog: {
            get() {
                return this.$store.state.adminUi.blogModal;
            },
        },
        validBody() {
            return this.clonedPost.body ? this.clonedPost.body.length >= 10 : false
        },
        isValidated() {
            return !!(this.valid && ( this.image || this.clonedPost.image ) && this.validBody);
        },
    },
    watch: {
        selectedPost() {
            this.clonedPost = {...this.selectedPost}
        },
        edited(value) {
            this.$store.commit('adminUi/setInterceptedBlogModal', value)
        },
        parentDialog(value) {
            if (!value) {
                this.clonedPost = null;
            }
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
            this.image ? data.set('image', this.image) : ''
            this.uploading = true
            data.set('title', this.clonedPost.title)
            data.set('slug', this.clonedPost.slug)
            data.set('description', this.clonedPost.description)
            data.set('og_title', this.clonedPost.og_title)
            data.set('og_description', this.clonedPost.og_description)
            data.set('taxonomy', this.clonedPost.taxonomy)
            data.set('body', this.clonedPost.body)
            data.set('is_featured', + this.clonedPost.is_featured)

            data.set('_method', 'patch');
            try {
                let res = await this.$http.post(`/api/post/${this.clonedPost.id}`, data,
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
                    this.$store.commit('adminUi/setInterceptedBlogModal', false)
                    this.$store.commit('adminUi/setBlogModal', false)
                    this.$emit('updateposts');
                }
                this.uploading = false

            } catch (e) {
                this.uploading = false;
                this.$refs.form.validate();
                // if(e.response.data.errors) {
                //     Object.keys(e.response.data.errors).forEach(i => {
                //         this.$store.commit('snackbar/pushMessage', {
                //             message: e.response.data.errors[i],
                //             color: "error",
                //             timeout: 5000,
                //             right: true,
                //             bottom: true
                //         })
                //     })
                // } else if (e.response.data.message) {
                //         this.$store.commit('snackbar/pushMessage', {
                //             message: e.response.data.message,
                //             color: "error",
                //             timeout: 5000,
                //             right: true,
                //             bottom: true
                //     })
                // }
            }
        },

    }

}
</script>

<style scoped>

</style>
