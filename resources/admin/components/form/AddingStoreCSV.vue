<template>
    <v-form v-model="valid">
        <v-row class="xs12">
            <v-col class="d-flex" cols="12" sm="12">
                <v-select
                    :items="stores"
                    v-model="selectedStore"
                    :item-value="'id'"
                    :item-text="'name'"
                    label="Select store"
                    prepend-icon="mdi-cart"
                    outlined
                    required
                ></v-select>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Name Column"
                    prepend-icon="mdi-form-textbox"
                    v-model="inputs.name.value"
                    :rules="inputs.name.rules"
                    :counter="255"
                    outlined
                    required
                ></v-text-field>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Serving Column"
                    prepend-icon="mdi-food-fork-drink"
                    v-model="inputs.servingSize.value"
                    :rules="inputs.servingSize.rules"
                    :counter="255"
                    outlined
                    required
                ></v-text-field>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Package Size Column"
                    prepend-icon="mdi-food-fork-drink"
                    v-model="inputs.packageSize.value"
                    :rules="inputs.packageSize.rules"
                    :counter="255"
                    outlined
                    required
                ></v-text-field>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Image Column"
                    v-model="inputs.image.value"
                    :rules="inputs.image.rules"
                    :counter="255"
                    prepend-icon="mdi-file-image"
                    outlined
                    required
                >

                </v-text-field>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Price Column"
                    v-model="inputs.cost.value"
                    :rules="inputs.cost.rules"
                    :counter="255"
                    prepend-icon="mdi-cash"
                    outlined
                    required
                >

                </v-text-field>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Calories Column"
                    v-model="inputs.calories.value"
                    :rules="inputs.calories.rules"
                    :counter="255"
                    prepend-icon="mdi-form-select"
                    outlined
                    required
                >
                </v-text-field>
            </v-col>

            <v-col class="d-flex" cols="12" sm="6">
                <v-text-field
                    label="Original URL"
                    v-model="inputs.url.value"
                    :rules="inputs.url.rules"
                    :counter="255"
                    prepend-icon="mdi-form-select"
                    outlined
                    required
                >
                </v-text-field>
            </v-col>


            <v-col class="d-flex" cols="12" sm="12">
                <v-file-input
                    color="deep-purple accent-4"
                    accept="text/csv"
                    label="File input"
                    placeholder="Select CSV file"
                    prepend-icon="mdi-paperclip"
                    v-model="file.value"
                    :show-size="1000"
                    @change="handleFileSelect"
                    :error="file.validation.failed"
                    :error-messages="file.validation.failed ? file.validation.errorMessage : null"
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



            <v-col class="d-flex" cols="12" sm="6">
                <v-btn :disabled="!isValidated || uploading || !!dupes.length " :loading="uploading" @click="handleStore">
                    Store
                </v-btn>
                <v-flex v-if="dupes.length > 0" align-self-center style="color: red; margin-left: 1em">
                    <span>This values considered as duplicates: <span v-for="dupe in dupes">{{ dupe }}, </span> </span>
                </v-flex>

            </v-col>


        </v-row>

    </v-form>
</template>

<script>
export default {
    name: "AddingStoreCSV",
    data: function () {
        return {
            stores: null,
            selectedStore: undefined,
            file: {
                value: undefined,
                validation: {
                    allowed: 'csv',
                    failed: null,
                    errorMessage: null,
                }
            },
            uploading: false,
            errors: [],
            valid: false,
            inputs: {
                name: {
                    value: '',
                    rules: [
                        v => !!v             || 'Description is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
                servingSize: {
                    value: '',
                    rules: [
                        v => !!v             || 'Description is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
                packageSize: {
                    value: '',
                    rules: [
                        v => !!v             || 'Package size is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
                image: {
                    value: '',
                    rules: [
                        v => !!v             || 'Description is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
                cost: {
                    value: '',
                    rules: [
                        v => !!v             || 'Description is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
                calories: {
                    value: '',
                    rules: [
                        v => !!v             || 'Description is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
                url: {
                    value: '',
                    rules: [
                        v => !!v             || 'Url is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                    ]
                },
            },
            dupes: [],
        }
    },
    computed: {
        isValidated() {
            return !!((!this.file.validation.failed && this.file.value) && this.selectedStore)
        },
    },

    watch: {
        inputs: {
            handler(val) {
                let flatten = [];
                Object.keys(this.inputs).forEach(i=> {
                    flatten.push(this.inputs[i].value);
                })

                this.dupes = flatten.filter((item, index) => flatten.indexOf(item) !== index && item !== "")
            },
            deep: true
        }
    },

    mounted() {
        this.fetchShops()
    },
    methods: {
        test() {
            let flatten = [];
            Object.keys(this.inputs).forEach(i=> {
                flatten.push(this.inputs[i].value);
            })

            return flatten.filter((item, index) => flatten.indexOf(item) !== index && index !== "")
        },
        async fetchShops() {
            this.$store.commit('adminUi/setIsLoading', true)
            try {
                let res = await this.$http.get('/api/store/defaults')
                this.stores = res.data.data;
            } catch (e) {}
            this.$store.commit('adminUi/setIsLoading', false)
        },
        async handleStore() {
            this.uploading = false;
            let data = new FormData();
            data.set('csv', this.file.value);
            data.set('name', this.inputs.name.value);
            data.set('serving_size', this.inputs.servingSize.value);
            data.set('package_size', this.inputs.packageSize.value);
            data.set('', this.inputs.servingSize.value);
            data.set('image', this.inputs.image.value);
            data.set('cost', this.inputs.cost.value);
            data.set('calories', this.inputs.calories.value);
            data.set('_method', 'PUT');
            this.$store.commit('adminUi/setIsLoading', true)

            try {
                let res = await this.$http.post(`/api/store/${this.selectedStore}`, data,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })


                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: "CSV added to fetching queue",
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    this.fetchShops()
                }


                this.uploading = false;
            } catch (e) {
                this.uploading = false;

                if(e.response.data.errors) {
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
                    e.response.data.message.forEach(m => {
                        this.$store.commit('snackbar/pushMessage', {
                            message: m,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    })
                }
            }
            this.$store.commit('adminUi/setIsLoading', false)
            this.uploading = false;
        },
        handleFileSelect() {
            let fileExt = this.checkFileExtensions(this.file.value.name);
            if(fileExt[0] !== this.file.validation.allowed) {
                this.file.validation.failed = true;
                this.file.value = undefined;
            } else {
                this.file.validation.failed = false;
            }
        },
        checkFileExtensions(filename) {
            return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
        },

    },
}
</script>

<style scoped>

</style>
