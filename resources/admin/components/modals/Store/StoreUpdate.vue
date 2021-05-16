<template>
    <div>
        <div v-if="selectedStore">
            <v-form v-model="valid">
                <v-row class="xs12">

                    <v-col class="d-flex" cols="12" sm="12">
                        <v-text-field
                            label="Store name"
                            prepend-icon="mdi-form-select"
                            v-model="store.name"
                            :rules="nameRules"
                            :counter="191"
                            v-on:keydown.enter.prevent='updateStore'
                            outlined
                            required
                        />
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="12" align-self="center" v-if="store.image">
                        Store already have&nbsp;<a :href="store.image" target="_blank">cover image </a>. If you want to change it - upload new one below
                    </v-col>
                    <v-col class="d-flex" cols="12" sm="12">
                        <v-file-input
                            color="deep-purple accent-4"
                            accept="image/*"
                            label="File input"
                            placeholder="Select store image"
                            prepend-icon="mdi-paperclip"
                            v-model="image"
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
                            @click="updateStore"
                            :disabled="!isValidated  || uploading"
                            :loading="uploading"

                        >
                            Save
                        </v-btn>
                    </v-col>

                </v-row>
            </v-form>
        </div>
        <div v-else>
            <span>
                Please, select store from table
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "StoreUpdate",
    props: {
        selectedStore: {
            type: Object,
            default: null,
        }
    },
    data: function () {
        return {
            valid: false,
            uploading: false,
            image: null,
            store: {...this.selectedStore},
            nameRules: [
                v => !!v || 'Name is required',
                v => v.length <= 191 || 'Maximum 191 characters',
            ]
        }
    },
    computed: {
        isValidated() {
            return this.valid &&  ( this.image || this.store.image );
        }
    },
    watch: {
        selectedStore() {
            this.store = {...this.selectedStore}
        }
    },
    methods: {
        async updateStore() {
            let data = new FormData()
            this.uploading = true;
            if(this.image) {
                data.set('image', this.image)
            }
            data.set('name', this.store.name)
            data.set('_method', 'patch');

            try {
                let res = await this.$http.post(`/api/store/${this.store.id}`, data, {
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

                    this.uploading = false;

                    this.$emit('updatestore')

                    this.$store.commit('adminUi/setStoreModal', false)

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
            this.store = {}
            this.image = null
        }
    }
}
</script>

<style scoped>

</style>
