<template>
    <v-layout class="" column>
        <v-flex class="xs12 pb-5">
            <h2>
                Store new coupons
            </h2>
        </v-flex>
        <v-layout column>
            <v-flex>
                <v-file-input
                    v-model="file"
                    color="deep-purple accent-4"
                    accept="text/csv"
                    label="File input"
                    placeholder="Select CSV file"
                    prepend-icon="mdi-paperclip"
                    outlined
                    :show-size="1000"
                    @change="handleFileSelect"
                    :error="validation.failed"
                    :error-messages="validation.failed ? validation.errorMessage : null"
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
            </v-flex>
            <v-flex>
                    <v-select
                        :items="couponSets"
                        v-model="selected"
                        :item-value="'id'"
                        :item-text="'name'"
                        label="Category"
                        prepend-icon="mdi-form-select"
                        outlined
                    ></v-select>

            </v-flex>
            <v-flex>
                <v-btn
                    :disabled="!isValidated || uploading"
                    :loading="uploading"
                    @click="storeCSV"
                >
                    Store
                </v-btn>
            </v-flex>
        </v-layout>
    </v-layout>
</template>

<script>
export default {
    name: "StoreTable",
    data: function () {
        return {
            file: undefined,
            selected: undefined,
            uploading: false,
            failed: false,
            validation: {
                allowed: 'csv',
                failed: false,
                errorMessage: 'This filetype is not allowed. Please, select CSV file'
            }
        }
    },
    computed: {
        couponSets: {
            get() {
                return this.$store.state.coupon.couponSets;
            },
        },
        selectedCouponEdit: {
            get() {
                return this.$store.state.coupon.selectedCouponEdit;
            },
            set(state) {
                this.$store.commit('coupon/selectedCouponEdit', state);
            }
        },
        isValidated() {
            return !!((!this.validation.failed && this.file) && this.selected);
        },
    },
    watch: {
        selectedCouponEdit() {
            this.selected     = this.selectedCouponEdit.id;
        }

    },
    mounted() {
        if (this.selectedCouponEdit) {
            this.selected = this.selectedCouponEdit.id;
        }
    },
    methods: {
        handleFileSelect(event) {
            let fileExt = this.checkFileExtensions(this.file.name);
            if(fileExt[0] !== this.validation.allowed) {
                this.validation.failed = true;
                this.file = undefined;
            } else {
                this.validation.failed = false;
            }
        },
        checkFileExtensions(filename) {
            return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : undefined;
        },
        async storeCSV() {
            this.uploading = true;
            let data = new FormData();
            data.append('csv', this.file);
            try {
                let res = await this.$http.post(`/api/coupon/${this.selected}/add`, data,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })

                this.$store.commit('snackbar/pushMessage', {
                    message: res.data.message ? res.data.message : 'Success',
                    color: "success",
                    timeout: 5000,
                    right: true,
                    bottom: true
                })

                this.resetInputs();

                this.$store.commit('adminUi/setCouponModal', false)

            } catch (e) {
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

                this.uploading = false
            }
        },
        resetInputs() {
            this.uploading = false;
            this.file      = undefined;
            this.selected  = undefined;
        }

    }
}
</script>

<style scoped>

</style>
