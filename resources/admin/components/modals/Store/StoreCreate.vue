<template>
    <v-layout class="" column>
        <v-flex class="xs12 pb-5">
            <h2>
                Create new store
            </h2>
        </v-flex>
        <v-layout column>

            <v-form ref="form" v-model="valid">
                <v-row class="xs12">

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-text-field
                            label="Store name"
                            prepend-icon="mdi-form-select"
                            v-model="store.name"
                            :rules="nameRules"
                            :counter="191"
                            v-on:keydown.enter.prevent='storeStore'
                            outlined
                            required
                        />
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-file-input
                            color="deep-purple accent-4"
                            accept="image/*"
                            label="File input"
                            placeholder="Select store image"
                            prepend-icon="mdi-paperclip"
                            v-model="store.image"
                            :show-size="1000"
                            outlined
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
                        <v-btn
                            color="info"
                            class="white--text"
                            @click.prevent="storeStore"
                            :disabled="!isValidated  || uploading"
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
export default {
    name: "StoreCreate",
    data: function () {
        return {
            valid: false,
            uploading: false,
            store: {
                name: '',
                image: undefined,
            },
            nameRules: [
                v => !!v || 'Name is required',
                v => v.length <= 191 || 'Maximum 191 characters',
            ]
        }
    },
    computed: {
        isValidated() {
            return this.valid && this.store.image;
        }
    },
    methods: {
        async storeStore() {
            let data = new FormData()
            this.uploading = true;
            data.set('image', this.store.image)
            data.set('name', this.store.name)
            data.set('is_default', true)

            try {
                let res = await this.$http.post('/api/store', data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })

                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message ? res.data.message : 'Success',
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })

                    this.resetInputs()
                    this.$refs.form.resetValidation();
                    this.uploading = false;

                    this.$emit('updatestore')

                    this.$store.commit('adminUi/setStoreModal', false)
                    this.$store.commit('stores/addNewStore', res.data.store)

                }
            } catch (e) {
                this.uploading = false
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        this.$store.commit('snackbar/pushMessage', {
                            message: e.response.data.errors[i],
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        },
        resetInputs() {
            this.store = {
                name: '',
                image: undefined,
            }
        }
    }
}
</script>

<style scoped>

</style>
