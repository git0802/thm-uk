<template>

    <v-layout column>
        <v-flex class="xs12 pb-5">
            <h2>
                Update coupon category
            </h2>
        </v-flex>
        <v-layout column>

            <v-flex>
                <v-select
                    :items="couponSets"
                    v-model="selected"
                    :item-value="'id'"
                    :item-text="'name'"
                    label="Select category"
                    prepend-icon="mdi-form-select"
                    outlined
                ></v-select>
            </v-flex>

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
                            :disabled="true"
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
                            disabled
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
                        <v-btn :disabled="!valid" @click="updateHandler">
                            Store
                        </v-btn>

                        <v-btn v-if="selected" color="error" class="mx-5" @click="deleteHandler">
                            DELETE CATEGORY
                        </v-btn>
                    </v-col>

                </v-row>

            </v-form>
        </v-layout>
    </v-layout>

</template>

<script>

import find from 'lodash/find'

export default {
    name: "UpdateCategory",
    data: function () {
        return {
            selected: null,
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
                v => !!v || 'Category name is required',
                v => v.length <= 127 || 'Maximum 127 characters',
                v => v.length >= 2 || 'Minimum 2 characters',
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
                v => v.length >= 2 || 'Minimum 2 characters',

            ],

        }
    },
    computed: {
        couponSets: {
            get() {
                return this.$store.state.coupon.couponSets;
            },
            set(state) {
                this.$store.commit('coupon/setCouponSets', state)
            }
        },
        selectedCouponEdit: {
            get() {
                return this.$store.state.coupon.selectedCouponEdit;
            },
            set(state) {
                this.$store.commit('coupon/selectedCouponEdit', state);
            }
        }

    },
    watch: {
        selectedCouponEdit() {
            if (this.selectedCouponEdit) {
                this.selectedType = this.selectedCouponEdit.type;
                this.value = this.selectedCouponEdit.value;
                this.categoryName = this.selectedCouponEdit.name;
                this.description = this.selectedCouponEdit.title;
                this.selected = this.selectedCouponEdit.id;
            }
        },
        selected() {
            this.$store.commit('coupon/selectedCouponEdit', find(this.couponSets, (e) => {
                return e.id === this.selected
            }));
        }
    },
    mounted() {
        this.$refs.form.resetValidation();
        if (this.selectedCouponEdit) {
            this.selectedType = this.selectedCouponEdit.type;
            this.value = this.selectedCouponEdit.value;
            this.categoryName = this.selectedCouponEdit.name;
            this.description = this.selectedCouponEdit.title;
            this.selected = this.selectedCouponEdit.id;
        }
    },
    methods: {
        async updateHandler() {
            try {
                let res = await this.$http.patch(`/api/coupon/${this.selected}`, {
                    name: this.categoryName,
                    type: this.selectedType,
                    value: this.value,
                    title: this.description
                });
                this.$store.commit('snackbar/pushMessage', {
                    message: res.data.message,
                    color: "success",
                    timeout: 5000,
                    right: true,
                    bottom: true
                })
                await this.$store.dispatch('coupon/fetchSets', this.$http)

                await this.$store.commit('adminUi/setCouponModal', false)

            } catch (e) {
                this.$store.commit('snackbar/pushMessage', {
                    message: e.response.data.message,
                    color: "error",
                    timeout: 5000,
                    right: true,
                    bottom: true
                })
            }

        },
        resetInputs() {
            this.selectedType = null;
            this.categoryName = '';
            this.value = '';
            this.description = '';
        },

        async deleteHandler() {
            if (confirm('Are you sure that you want to delete this coupon group? All coupons in it will be deleted!')) {
                try {
                    let res = await this.$http.delete(`/api/coupon/${this.selected}`);

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
                } catch (e) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
