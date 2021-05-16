<template>
    <v-layout column>
        <v-flex class="xs12 pb-5">
            <h2>
                Create new coupon category
            </h2>
        </v-flex>
        <v-layout column>
            <v-form ref="form" v-model="valid">

                <v-row class="xs12">
                    <v-col class="d-flex" cols="12" sm="6">
                        <v-text-field
                            label="Category Name"
                            prepend-icon="mdi-form-select"
                            v-model="categoryName"
                            :rules="categoryNameRules"
                            :counter="127"
                            outlined
                            required
                        ></v-text-field>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="6">
                        <v-select
                            label="Coupon Type"
                            prepend-icon="mdi-form-select"
                            :items="types"
                            v-model="selectedType"
                            outlined
                            required
                        ></v-select>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="6">
                        <v-text-field
                            label="Value"
                            type="number"
                            v-model="value"
                            :rules="valueRules"
                            :counter="2"
                            prepend-icon="mdi-form-select"
                            outlined
                            required
                        >

                        </v-text-field>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="6">
                        <v-text-field
                            label="Description"
                            v-model="description"
                            :rules="descriptionRules"
                            :counter="127"
                            prepend-icon="mdi-form-select"
                            outlined
                            required
                        >

                        </v-text-field>
                    </v-col>

                    <v-col class="d-flex" cols="12" sm="6">
                        <v-btn :disabled="!valid" :loading="uploading" @click="storeHandler">
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
    name: "CreateCategory",
    data: function() {
        return {
            valid: false,
            types: [
                'percent',
                'month',
                'fixed',
            ],
            selectedType: null,
            // 2 min
            // 127 max string
            categoryName: '',
            categoryNameRules: [
                v => !!v             || 'Category name is required',
                v => v.length <= 127 || 'Maximum 127 characters',
                v => v.length >= 2   || 'Minimum 2 characters',
            ],
            // number

            value: '',
            valueRules: [
                v => !!v || 'Value is required',
                v => v.length <= 2   || 'Maximum 2 characters',
            ],
            // 2 min
            // 127 max string
            description: '',
            descriptionRules: [
                v => !!v || 'Description is required',
                v => v.length <= 127 || 'Maximum 127 characters',
                v => v.length >= 2   || 'Minimum 2 characters',

            ],
            uploading: false,
        }
    },
    methods: {
        async storeHandler() {
            this.uploading = true;
            try {
                let res = await this.$http.post(`/api/coupon`, {
                    name: this.categoryName,
                    type: this.selectedType,
                    value: this.value,
                    title: this.description
                });

                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })

                    this.resetInputs();
                    this.$refs.form.resetValidation();

                    await this.$store.dispatch('coupon/fetchSets', this.$http)

                    await this.$store.commit('adminUi/setCouponModal', false)
                }
            } catch (e) {
                this.$store.commit('snackbar/pushMessage', {
                    message: e.response.data.message,
                    color: "error",
                    timeout: 5000,
                    right: true,
                    bottom: true
                })
            }
            this.uploading = false;

        },
        resetInputs() {
            this.selectedType = null;
            this.categoryName = '';
            this.value = '';
            this.description = '';
        }
    }
}
</script>

<style scoped>

</style>
