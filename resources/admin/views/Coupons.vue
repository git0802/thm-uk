<template>
    <v-layout column>
        <v-layout>
            <v-flex class="xs12 pa-2">
                <h1 class="flex">
                    Coupons and Groupons
                </h1>


                <v-flex class="xs12">

                        <v-btn class="ml-2 mb-2" @click.stop="handleCouponModal" color="yellow">
                            Add coupons
                        </v-btn>

                        <v-btn class="ml-2 mb-2 white--text" @click.stop="openCouponCreate" color="brown">
                            Create coupon group
                        </v-btn>

                        <v-btn v-if="setLoader.index !== -1" class="ml-2 mb-2 white--text" @click.stop="openCouponEditTab" color="black">
                            Edit coupon group
                        </v-btn>

                </v-flex>

            </v-flex>

        </v-layout>

        <v-layout>
            <a href="#tabletop" ref="tabletop"></a>
            <v-flex class="xs12 pa-2" v-if="couponSets">
                <v-item-group>
                    <template v-for="(item, i) in couponSets">
                        <v-btn class="ml-2 mb-2"
                               :color="(i === setLoader.index) ? 'deep-purple accent-4  white--text' : 'primary'"
                               @click="fetchSet(i)"
                               :loading="setLoader.loading && i === setLoader.index"
                        >
                            {{item.name}}
                        </v-btn>
                    </template>
                </v-item-group>
            </v-flex>

        </v-layout>

        <v-layout>
            <v-flex class="xs12" v-if="couponSets">
                <v-data-table
                    :headers="headers"
                    :items="coupons"
                    :flatted-sets="flattedSets"
                    class="elevation-1"
                    v-on:update:page="paginationHandler"
                >
                    <template v-slot:item.coupon_set_id="{ item }">
                        {{ flattedSets[item.coupon_set_id] }}
                    </template>

                </v-data-table>
            </v-flex>
        </v-layout>

        <add-coupon-modal :forcedTab="forcedTab" v-on:clearforcedtab="clearForcedTab" />



    </v-layout>
</template>

<script>
    import AddCouponModal from "../components/modals/CouponManageModal";
    export default {
        name: "Coupons",
        components: {AddCouponModal},
        data () {
            return {
                // couponSets: null,
                setLoader: {
                    index: -1,
                    loading: false,
                },
                headers: [
                    {
                        text: 'Code',
                        align: 'start',
                        sortable: false,
                        value: 'code',
                    },
                    { text: 'Set', value: 'coupon_set_id' },
                    { text: 'Used At', value: 'used_at' },
                    { text: 'Used By', value: 'user_id' },
                ],
                coupons: [
                ],
                forcedTab: null,
            }
        },
        computed: {
            flattedSets() {
                let obj =  {}
                this.couponSets.map(e => { return obj[e.id] = e.name });
                return obj;
            },
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
        mounted() {
          this.fetchCouponSets();
        },
        watch: {
            setLoader() {
                this.selectedCouponEdit = this.couponSets[this.setLoader.index];
            },
            couponSets() {
                this.setLoader = {
                    index: -1,
                    loading: false,
                }
                this.coupons = [];
            },
        },
        methods: {
            fetchCouponSets() {
                this.$store.dispatch('coupon/fetchSets', this.$http);
            },
            async fetchSet(index) {
                this.setLoader = {
                    index: index,
                    loading: true,
                }
                this.$store.commit('adminUi/setIsLoading', true)
                try {
                    let set = this.couponSets[index];
                    let res = await this.$http.get(`/api/coupon/${set.id}`);

                    this.coupons = res.data.sets;

                    this.setLoader = {
                        index: index,
                        loading: false,
                    }
                } catch (e) {
                    this.setLoader = {
                        index: -1,
                        loading: false,
                    }
                }
                this.$store.commit('adminUi/setIsLoading', false)
            },
            async handleCouponModal() {
                this.$store.commit('adminUi/setCouponModal', !this.$store.state.adminUi.couponModal)
            },
            openCouponCreate() {
                this.forcedTab = 1;
                this.handleCouponModal();
            },
            openCouponEditTab() {
                this.forcedTab = 2;
                this.handleCouponModal();
            },
            clearForcedTab() {
                this.forcedTab = null;
            },
            paginationHandler() {
                this.$refs.tabletop.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    }
</script>

<style scoped>

</style>
