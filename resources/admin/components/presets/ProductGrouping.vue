<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="800px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                >
                    Create grouped product
                </v-btn>
            </template>
            <v-card>
                <overlay-component v-if="loading"/>
                <v-card-title>
                    <span class="headline">Product Grouping</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col class="d-flex" cols="12" sm="12">
                                <v-autocomplete
                                    @update:search-input="appendSearchInput"
                                    :search-input="productAdder.search"
                                    v-model="productAdder.testModel"
                                    :items="productAdder.products"
                                    :loading="productAdder.isLoading"
                                    item-text="name"
                                    item-value="id"
                                    label="Product search"
                                    placeholder="Start typing to Search"
                                    prepend-icon="mdi-database-search"
                                    hide-no-data
                                    return-object
                                    clearable
                                />
                            </v-col>
                            <v-col class="d-flex" cols="12" sm="12">
                                <v-list
                                >

                                    <template v-for="(item, index) in productAdder.entries">
                                        <div class="remaped-list">
                                            <v-list-item
                                                :key="item.id"
                                                :value="item.id"
                                            >
                                                    <v-list-item-action>
                                                        <v-simple-checkbox
                                                            @click="toggle(item.id)"
                                                            :value="selectedProductIds.includes(item.id)"
                                                            />
                                                    </v-list-item-action>



                                                    <v-list-item-content>
                                                        <v-list-item-title>{{ item.name }}</v-list-item-title>
                                                        <v-list-item-subtitle>

                                                            <div class="fooz" style="padding-top: 15px; margin-bottom: unset">
                                                                <div class="foos foos-4 ">
                                                                    <div class="title">
                                                                        Serving Size
                                                                    </div>
                                                                    <div class="text">
                                                                        {{ item.serving_size }}
                                                                    </div>
                                                                </div>
                                                                <div class="foos foos-4 " v-if="item.package_size">
                                                                    <div class="title">
                                                                        Package Size
                                                                    </div>
                                                                    <div class="text">
                                                                        {{ item.package_size }}
                                                                    </div>

                                                                </div>
                                                                <div class="foos foos-4 ">
                                                                    <div class="title">
                                                                        Calories
                                                                    </div>
                                                                    <div class="text">
                                                                        {{ item.calories }}
                                                                    </div>
                                                                </div>
                                                                <div class="foos foos-4 ">
                                                                    <div class="title">
                                                                        Cost
                                                                    </div>
                                                                    <div class="text">
                                                                        {{ item.cost.toFixed(2) }}£
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>

                                                    <v-list-item-action>
                                                        <v-list-item-avatar style="margin: unset">
                                                            <v-img :src="item.image"/>
                                                        </v-list-item-avatar>
                                                    </v-list-item-action>
                                            </v-list-item>
                                            <div class="remaped-list__button" v-ripple @click="removeProductFromList(item.id)">
                                                <span>
                                                    REMOVE
                                                </span>
                                            </div>
                                        </div>
                                    </template>



                                </v-list>

                            </v-col>
                            <hr>
                            <v-btn
                                :disabled="!validProducts"
                                @click="groupingAccordion = !groupingAccordion"
                                color="info"
                            >
                                Group selected food
                            </v-btn>

                            <template v-if="groupingAccordion">
                                <v-form
                                    v-model="isFormValid"
                                >
                                <v-row class="pt-5">
                                        <v-col class="d-flex" cols="12" sm="12">
                                            <v-text-field
                                                label="Product name*"
                                                name="product_name"
                                                prepend-icon="mdi-form-textbox"
                                                v-model="inputs.name.value"
                                                :rules="inputs.name.rules"
                                                :counter="255"
                                                outlined
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col class="d-flex" cols="12" sm="12">
                                            <v-text-field
                                                label="Calories"
                                                prepend-icon="mdi-coffee"
                                                :value="selectedProductsData.calories"
                                                disabled

                                            ></v-text-field>
                                        </v-col>
                                        <v-col class="d-flex" cols="12" sm="12">
                                            <v-text-field
                                                label="Cost"
                                                :prepend-icon="currencyIcon"
                                                :value="selectedProductsData.cost.toFixed(2)"
                                                disabled
                                            ></v-text-field>
                                        </v-col>

                                        <v-btn
                                            :disabled="!isFormValid"
                                            color="success"
                                            class="mr-4"
                                            @click="createGroupedFood"
                                        >
                                            Create product
                                        </v-btn>

                                    </v-row>
                                </v-form>
                            </template>

                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="dialog = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import find from "lodash/find";
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";

export default {
    components: {OverlayComponent},
    props: ['store_id'],
    data: function () {
        return {
            dialog: false,
            loading: false,
            productAdder: {
                selectedStore: null,
                products: [],
                selectedProduct: undefined,
                search: '',
                isLoading: false,
                testModel: null,
                count: null,
                entries: []
            },
            selectedProductIds: [],
            groupingAccordion: false,
            inputs: {
                name: {
                    value: '',
                    rules: [
                        v => !!v || 'Product Name is required',
                        v => v.length <= 255 || 'Maximum 255 characters',
                        v => v.length >= 2 || 'Minimum 2 characters',
                    ]
                },
            },
            isFormValid: false,
        }
    },
    computed: {
        validProducts() {
            return 1 < this.selectedProductIds.length
        },
        selectedProductsData() {
            let a = {
                calories: 0,
                cost: 0
            }
            let list = this.productAdder.entries.filter(e => {
                return this.selectedProductIds.includes(e.id)
            })
            list.forEach(e => {
                a.calories += e.calories
                a.cost += e.cost
            })
            return a
        },
        currencyIcon() {
            return window.laravel.location == 'UK' ? 'mdi-currency-gbp' : 'mdi-currency-usd'
        },
    },
    watch: {
        'productAdder.testModel': function (value, oldValue) {
            if (value === undefined || value === null) {
                this.productAdder.testModel = null;
            } else {
                console.log(value)
                let finder = find(this.productAdder.entries, function (e) {
                    return e.id === value.id
                })
                if (finder) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: 'Sorry. You cant add same product twice.',
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    this.productAdder.testModel = undefined
                } else {
                    this.productAdder.entries.unshift(value)
                    this.productAdder.testModel = undefined
                    this.productAdder.search = ''

                }
                this.productAdder.search = ''
            }
        },
        'productAdder.search': async function (val) {
            if(!val) return
            if(val.length <= 1) return
            if (this.productAdder.isLoading) return
            this.productAdder.isLoading = true

            try {
                let res = await this.$http.get('/api/preset/find', {
                    params: {
                        search: val,
                        store_id: this.store_id,
                        without_dish: true,
                    }
                });
                this.productAdder.products = res.data.products
                this.productAdder.isLoading = false


            } catch (e) {
                this.productAdder.isLoading = false
            }
        },
        validProducts(value) {
            if(!value) {
                this.groupingAccordion = false
            }
        }
    },
    methods: {
        async createGroupedFood() {
            this.loading = true
            try {
                let res = await this.$http.post(`/api/preset/dish`, {
                    name: this.inputs.name.value,
                    store_id: this.store_id,
                    product_ids: this.selectedProductIds,
                })
                this.$emit('dishAdded', res.data.dish)
                this.$store.commit('snackbar/pushMessage', {
                    message: res.data.message,
                    color: "success",
                    timeout: 5000,
                    right: true,
                    bottom: true
                })
                this.closeModal()
            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
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
            } finally {
                this.loading = false
            }
        },
        toggle(val) {
            if(!this.selectedProductIds.includes(val)) {
                this.selectedProductIds.push(val)
            } else {
                this.selectedProductIds =  this.selectedProductIds.filter(e => {
                    return e !== val
                })
            }
        },
        appendSearchInput(val) {
            if (val) {
                this.productAdder.search = val.trim()
            }
        },
        removeProductFromList(id) {
            if(confirm('Remove from the list?')) {
                this.productAdder.entries = this.productAdder.entries.filter(e => {
                    return e.id !== id
                })
                this.selectedProductIds = this.selectedProductIds.filter(e => {
                    console.log('selected filter', e, id, e !== id)
                    return e !== id
                })
            }
        },
        closeModal() {
            // restoring default state of component
            Object.assign(this.$data, this.$options.data())
        },

    }
}
</script>


<style scoped lang="scss">
.remaped-list {
    width: 100%;
    display: inline-flex;
    margin-bottom: 10px;
    box-shadow: 0 3px 1px -2px rgba(0,0,0,.2), 0 2px 2px 0 rgba(0,0,0,.14), 0 1px 5px 0 rgba(0,0,0,.12);
    border-radius: 15px;
    &__button {
        background: darkred;
        color: white;
        position: relative;
        width: 45px;
        cursor: pointer;
        user-select: none;
        border-top-right-radius: 15px;
        border-bottom-right-radius: 15px;
        span {
            transform: rotate(-90deg) translate(-20%, 45%);
            position: absolute;
            right: 0;
            bottom: 50%;
        }
    }
}
.fooz {
    display: flex;
    margin-bottom: 14px;
    width: 100%;

    .foos {
        width: calc(100% / 5);

        &.foos-4 {
            width: calc(100% / 4);
        }

        display: flex;
        flex-direction: column;
        padding: 0 10px;
        text-align: center;
        border-right: 1px solid hsl(0 0% 0% / 0.1);

        &:last-child {
            border-right: unset;
        }

        .title {
            font-size: 12px !important;
            font-weight: 500;
            margin-bottom: 6px;
            line-height: 1;
        }

        .text {
            font-size: 14px !important;
            display: flex;
            justify-content: center;
        }
    }
}

</style>
