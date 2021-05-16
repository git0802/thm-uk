<template>
    <app-layout>
        <div class="meal-setup meal-setup__navbar--top">
            <div class="meal-setup__wrapper">

                <div class="choosing-from">
                    <div class="choosing-from__box-top">
                        <span class="meal-setup__title">1. Choosing from:</span>
                        <select-store :stores="stores" />
                    </div>


                    <search-params
                        @showMessage="showMessage = true"
                        @update="showMessage = false"
                    />
                    <div class="select-food__message">
                        <warning-message v-if="showMessage"
                             :message="'You already choose this product'"
                        />
                    </div>
                </div>

                <div class="you-chosen">
                    <div class="you-chosen__top">
                        <span class="meal-setup__title">2. You’ve chosen <b v-if="choosedFood.length > 0">( {{ choosedFood.length }} items)</b></span>
                        <button-add-item
                            :title="'Add custom food'"
                            @click="actionsModal({
                            name: 'modalFood',
                            action: 'open'
                        }); clearMessages()"
                        />
                    </div>

                    <food-list/>

                    <div class="you-chosen__btn-group">
                        <button type="button" class="btn btn--primary"
                                @click="deleteFood"
                        >
                            <svg width="10" height="12" viewBox="0 0 10 12" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.03846 1.5H6.92308V1.125C6.92308 0.503672 6.40649 0 5.76923 0H4.23077C3.59351 0 3.07692 0.503672 3.07692 1.125V1.5H0.961538C0.430505 1.5 0 1.91974 0 2.4375V3.1875C0 3.39462 0.172188 3.5625 0.384615 3.5625H9.61539C9.82781 3.5625 10 3.39462 10 3.1875V2.4375C10 1.91974 9.5695 1.5 9.03846 1.5ZM3.84615 1.125C3.84615 0.918281 4.01875 0.75 4.23077 0.75H5.76923C5.98125 0.75 6.15385 0.918281 6.15385 1.125V1.5H3.84615V1.125Z"/>
                                <path d="M0.729904 4.3125C0.661274 4.3125 0.606587 4.36842 0.609856 4.43527L0.927163 10.9284C0.95649 11.5294 1.46274 12 2.07957 12H7.92043C8.53726 12 9.04351 11.5294 9.07284 10.9284L9.39014 4.43527C9.39341 4.36842 9.33873 4.3125 9.2701 4.3125H0.729904ZM6.53846 5.25C6.53846 5.04281 6.71058 4.875 6.92308 4.875C7.13558 4.875 7.30769 5.04281 7.30769 5.25V10.125C7.30769 10.3322 7.13558 10.5 6.92308 10.5C6.71058 10.5 6.53846 10.3322 6.53846 10.125V5.25ZM4.61538 5.25C4.61538 5.04281 4.7875 4.875 5 4.875C5.2125 4.875 5.38462 5.04281 5.38462 5.25V10.125C5.38462 10.3322 5.2125 10.5 5 10.5C4.7875 10.5 4.61538 10.3322 4.61538 10.125V5.25ZM2.69231 5.25C2.69231 5.04281 2.86442 4.875 3.07692 4.875C3.28942 4.875 3.46154 5.04281 3.46154 5.25V10.125C3.46154 10.3322 3.28942 10.5 3.07692 10.5C2.86442 10.5 2.69231 10.3322 2.69231 10.125V5.25Z"/>
                            </svg>
                            <span>DELETE ITEMS</span>
                        </button>
                        <button type="button" class="btn btn--primary"
                                :disabled="choosedFood.length < 2"
                                @click="actionsModal({
                                    name: 'modalDish',
                                    action: 'open'
                                }); clearMessages()"
                        >
                            <span>GROUP ITEMS</span>
                        </button>
                    </div>

                    <div class="food-grouping">
                        <span class="food-grouping__title">Optionally select & group any of the above items together in 1 combined recipe.</span>

                            <food-list-dish/>

                    </div>
                </div>

                <meal-planner/>
            </div>
        </div>

<!--        modals start -->
<!--        modal for adding food-->
        <modal-food/>
<!--        modal for food grooping-->
        <modal-dish/>
        <modal-weekly-goals/>
        <!--        modals end-->


    </app-layout>
</template>

<script>
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';
    import {mapActions, mapGetters} from "vuex";
    import FoodListDish from "../components/modals/ModalPlan/FoodPlanner/FoodListDish";

    export default {
        name: "Drag",
        components: {
            FoodListDish,
            'v-select':  vSelect,
        },
        data() {
            return {
                showMessage: false,
                details: null,
            }
        },
        computed: {
            ...mapGetters({
                choosedFood: 'food/getChoosedFood',
            }),
            stores: {
                get() {
                    return this.$store.state.stores.stores
                }
            }
        },

        mounted() {
            this.fetchStores()
        },
        methods: {
            ...mapActions({
                actionsModal: 'modals/actionsModal',
                clearMessages: 'food/clearMessages',
                deleteFood: 'food/deleteFood',


            }),
            detailsClick: function(param) {
                this.details = this.details == null ? param : null;
            },
            // fetch all stores
            fetchStores: function() {
                this.$store.dispatch('stores/getStoresList');
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
