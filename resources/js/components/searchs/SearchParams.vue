<template>
    <div class="search-container">
        <autocomplete
            ref="autocomplete"
            :debounceTime="500"
            :get-result-value="getResultValue"
            @submit="submitParams"
            :search="searchParams"
        >
            <template
                #default="{
        rootProps,
        inputProps,
        inputListeners,
        resultListProps,
        resultListListeners,
        results,
        resultProps
      }"
            >
                <div v-click-outside="clickOutsideAutocomplete"
                     v-bind="rootProps">
                    <custom-input
                        v-bind="inputProps"
                        v-on="inputListeners"
                        :class="[
            'autocomplete-input',
            { 'autocomplete-input-no-results': noResults },
            { 'autocomplete-input-focused': focused }
          ]"
                        @focus="handleFocus"
                        @blur="handleBlur"
                    ></custom-input>
                    <ul

                        v-if="noResults && !isOpen"
                        class="autocomplete-result-list"
                        style="position: absolute; z-index: 1; width: 100%; top: 100%;border-radius: 7px;"
                    >
                        <li class="autocomplete-result">
                            <template v-if="selectedStore.is_custom">
                                This is a custom store you created. You must add new food to it.
                            </template>
                            <template v-else>
                                No results found
                            </template>
                        </li>
                    </ul>
                    <ul v-bind="resultListProps" v-on="resultListListeners">
                        <li
                            v-for="(result, index) in results"
                            :key="resultProps[index].name"
                            v-bind="resultProps[index]"
                        >
                            {{ result.name }}
                        </li>
                    </ul>
                </div>
            </template>
        </autocomplete>
        <button type="button" class="clearBtn" @click="clearSearch">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.06127 6.00012L11.7801 1.28108C12.0733 0.988138 12.0733 0.512831 11.7801 0.219892C11.4869 -0.0732973 11.0122 -0.0732973 10.719 0.219892L6.00012 4.93894L1.28102 0.219892C0.987847 -0.0732973 0.51306 -0.0732973 0.219883 0.219892C-0.0732943 0.512831 -0.0732943 0.988138 0.219883 1.28108L4.93898 6.00012L0.219883 10.7192C-0.0732943 11.0121 -0.0732943 11.4874 0.219883 11.7804C0.366471 11.9267 0.558587 12 0.750453 12C0.942319 12 1.13444 11.9267 1.28102 11.7801L6.00012 7.06106L10.719 11.7801C10.8656 11.9267 11.0577 12 11.2495 12C11.4414 12 11.6335 11.9267 11.7801 11.7801C12.0733 11.4872 12.0733 11.0119 11.7801 10.7189L7.06127 6.00012Z"
                    fill="#747474"/>
            </svg>
        </button>
        <!--<icon-search class="search-container__icon"/>-->
    </div>
</template>

<script>
import Autocomplete from '@trevoreyre/autocomplete-vue'
import '@trevoreyre/autocomplete-vue/dist/style.css'
import {mapGetters, mapMutations} from 'vuex';
import axios from "axios";
import Vue from 'vue';

Vue.directive('click-outside',
    {
        bind() {
            this.event = event => this.vm.$emit(this.expression, event)
            this.el.addEventListener('click', this.stopProp)
            document.body.addEventListener('click', this.event)
        },
        unbind() {
            this.el.removeEventListener('click', this.stopProp)
            document.body.removeEventListener('click', this.event)
        },
        stopProp(event) {
            event.stopPropagation()
        }
    });
const CustomInput = Vue.component('CustomInput', {
    props: {
        value: {
            type: String,
            default: ''
        }
    },
    template: `
        <input
            :value="value"
            placeholder="Start typing your food names"
            v-on="$listeners"
        />
    `
})

export default {
    data() {
        return {
            results: [],
            focused: false,
            value: '',
            isOpen: false,
        }
    },
    components: {
        Autocomplete,
        CustomInput
    },
    computed: {
        ...mapGetters({
            food: 'food/getFood',
            myFoodChoosed: 'food/getMyFoodChoosed',
            selectedStore: 'stores/getSelectedStore',
            stores: 'stores/getStores'
        }),
        noResults() {
            return this.value && this.results.length == 0
        }
    },
    methods: {

        ...mapMutations({
            addFoodChoosed: 'food/addFoodChoosed',
        }),
        handleFocus() {
            this.focused = true
        },
        clickOutsideAutocomplete() {
            this.isOpen = true
        },
        handleBlur() {
            this.focused = false
        },
        async searchParams(value) {
            this.$emit('update');
            this.value = value;
            if (value.length >= 2) {

                const params = {
                    search: value,
                    store_id: this.selectedStore.id
                };

                const response = await axios.get('/api/product/find', {params});
                this.results = [...response.data.products];
                this.isOpen = false;
                this.results.forEach((item, index, array) => {
                    this.stores.forEach((store) => {
                        if (item.store_id == store.id) {
                            //Added object store with store name to product for correct work
                            array[index].store = {
                                name: store.name
                            };
                        }
                    })
                })

                return this.results;
            } else {
                return [];
            }
        },
        clearSearch() {
            this.$refs.autocomplete.value = '';
        },
        getResultValue() {
            return '';
        },
        submitParams(food) {
            if (food.id) {
                this.findFood(food.id);
            }
        },
        findFood(id) {
            const result = this.myFoodChoosed.find((item) => {
                return item.id == id;
            });

            if (!result) {
                const food = this.results.find((item) => {
                    return item.id == id;
                });
                if (food.id) {
                    if (this.myFoodChoosed.length == 0) {
                        this.addFoodChoosed(food);
                    } else {
                        this.$notify({
                            group: 'planner',
                            title: `Error`,
                            type: 'error',
                            text: 'You cannot add two dishes',
                        });
                    }
                }
            } else {
                this.$emit('showMessage');
            }
        },

    }
}
</script>

<style lang="scss">

.search-container {
    position: relative;
    display: flex;
    flex-direction: column;

    .clearBtn {
        position: absolute;
        bottom: 0px;
        right: 0px;
        margin-right: 14px;
        margin-bottom: 10px;

        &:hover {
            cursor: pointer;
        }
    }

    .autocomplete {
        .autocomplete-input {
            background-color: var(--grey-light);
            border: 1px solid var(--grey);
            border-radius: 7px;
            padding-top: 9px;
            padding-bottom: 8px;
            padding-right: 40px;
        }

        .autocomplete-input[aria-expanded=true] {
            border-radius: 7px 7px 0px 0px;

            ~ .autocomplete .autocomplete-result-list {
                border-radius: 7px 7px 0px 0px;
            }
        }

        .autocomplete-result-list {
            z-index: 6 !important;
            background: var(--grey-light);
            border: 1px solid var(--grey);
            border-top: 0px solid var(--grey);
            padding: 4px;
        }

        .autocomplete-result:hover {
            background-color: var(--primary);
            border-radius: 7px;
            color: var(--white);
        }
    }
}
</style>


