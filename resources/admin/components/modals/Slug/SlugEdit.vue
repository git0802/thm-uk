<template>
    <v-layout column>
        <v-layout>
            <h1>
                Editing: {{ selectedPage.title }}
            </h1>
        </v-layout>
        <v-divider/>
        <v-layout>
            <v-form ref="form" v-model="valid">
                <v-row class="xs12" v-if="clonedPage">
                    <v-col class="d-flex" cols="12" sm="12">

                        <v-text-field
                            label="Title"
                            prepend-icon="mdi-form-select"
                            v-model="clonedPage.title"
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
                            v-model="clonedPage.slug"
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
                            v-model="clonedPage.description"
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
                            v-model="clonedPage.og_title"
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
                            v-model="clonedPage.og_description"
                            :rules="ogDescriptionRules"
                            :counter="191"
                            outlined
                            required
                        />
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="6">
                        <v-text-field
                            label="Position (order) in menu"
                            prepend-icon="mdi-form-select"
                            v-model="clonedPage.order"
                            :rules="orderRules"
                            :counter="255"
                            outlined
                            required
                        ></v-text-field>
                    </v-col>


                    <v-col class="d-flex" cols="12" sm="12">
                        <v-layout column>
                            <v-flex :style="{color: 'red'}" v-if="!validBody"><h4 :style="{'font-weight': 400}">Page body is to short!</h4></v-flex>
                            <text-editor v-model="clonedPage.body"/>
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
                            @click="storeBlogPage"
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
    name: "BlogPageEdit",
    components: {TextEditor},
    props: ['selectedPage'],
    data: function () {
        return {
            valid: false,
            loading: false,
            uploading: false,
            clonedPage: {...this.selectedPage},
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
            orderRules: [
                v =>  v >= 0  || 'Order value must be positive or 0',
            ],
        }
    },
    computed: {
        edited() {
            return JSON.stringify(this.clonedPage) !== JSON.stringify(this.selectedPage);
        },
        parentDialog: {
            get() {
                return this.$store.state.adminUi.blogModal;
            },
        },
        validBody() {
            return this.clonedPage.body ? this.clonedPage.body.length >= 10 : false
        },
        isValidated() {
            return !!(this.valid && this.validBody);
        },
    },
    watch: {
        selectedPage() {
            this.clonedPage = {...this.selectedPage}
        },
        edited(value) {
            this.$store.commit('adminUi/setInterceptedBlogModal', value)
        },
        parentDialog(value) {
            if (!value) {
                this.clonedPage = null;
            }
        },
    },
    methods: {
        async storeBlogPage() {
            let data = {
                title: this.clonedPage.title,
                slug: this.clonedPage.slug,
                description: this.clonedPage.description,
                og_title: this.clonedPage.og_title,
                og_description: this.clonedPage.og_description,
                body: this.clonedPage.body,
                order: this.clonedPage.order,
            }
            this.uploading = true
            try {
                let res = await this.$http.patch(`/api/page/${this.clonedPage.id}`, data)
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
                    this.$emit('updatepages');
                }
                this.uploading = false
            } catch (e) {
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
                this.$refs.form.validate();

                this.uploading = false
            }
        },

    }

}
</script>

<style scoped>

</style>
