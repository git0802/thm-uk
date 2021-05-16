<template>
    <v-layout column>
        <v-layout>
            <v-form ref="form" v-model="valid">
                <v-row class="xs12">
                    <v-col class="d-flex" cols="12" sm="12">
                        <v-text-field
                            label="Title"
                            prepend-icon="mdi-form-select"
                            v-model="page.title"
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
                            v-model="page.slug"
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
                            v-model="page.description"
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
                            v-model="page.og_title"
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
                            v-model="page.og_description"
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
                            v-model="page.order"
                            :rules="orderRules"
                            :counter="255"
                            outlined
                            required
                        ></v-text-field>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-layout column>
                            <v-flex :style="{color: 'red'}" v-if="!validBody"><h4 :style="{'font-weight': 400}">Page body is to short!</h4></v-flex>
                            <text-editor v-model="page.body"/>
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
                            :disabled="!isValidated || uploading"
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

export default {
    components: {TextEditor},
    data: function () {
        return {
            valid: false,
            uploading: false,
            page: {
                title: '',
                slug: '',
                description: '',
                og_title: '',
                og_description: '',
                body: '',
                order: null,
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
            orderRules: [
                v =>  v >= 0  || 'Order value must be positive or 0',
            ],

        }
    },
    computed: {
        edited() {
            return false
        },
        validBody() {
            return this.page.body ? this.page.body.length >= 10 : false
        },
        isValidated() {
            return !!(this.valid && this.validBody);
        },
    },
    methods: {
        async storeBlogPage() {
            let data = {
                title: this.page.title,
                slug: this.page.slug,
                description: this.page.description,
                og_title: this.page.og_title,
                og_description: this.page.og_description,
                body: this.page.body,
                order: this.page.order,
            }
            this.uploading = true
            try {
                let res = await this.$http.post('/api/page', data)

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
                    this.$emit('updatepages');
                }

                this.uploading = false

            } catch (e) {
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
            this.page = {
                title: '',
                slug: '',
                description: '',
                og_title: '',
                og_description: '',
                body: '',
            }
        }
    }
}
</script>

<style scoped>

</style>
