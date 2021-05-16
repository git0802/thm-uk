<template>
    <v-dialog
        v-model="dialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable
        persistent
    >
        <v-card tile>
            <v-toolbar
                flat
                dark
                color="primary"
                style="flex: unset"
            >
                <v-btn
                    icon
                    dark
                    @click="dialog = false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>

                <v-toolbar-title>Coupons management</v-toolbar-title>

                <v-spacer></v-spacer>


                <template v-slot:extension>
                    <v-tabs
                        v-model="tab"
                        background-color="transparent"
                        color="basil"
                        grow
                    >
                        <v-tab
                            v-for="(item, index) in items"
                            :key="index"
                        >
                            {{ item.title }}
                        </v-tab>
                    </v-tabs>
                </template>


            </v-toolbar>

            <v-tabs-items v-model="tab">
                <v-tab-item
                    v-for="(item, index) in items"
                    :key="index"
                >
                    <v-card flat>
                        <v-card-text>
                            <component :is="item.component"></component>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>

        </v-card>
    </v-dialog>
</template>

<script>
    import StoreTable from "./Coupon/StoreTable";
    import CreateCategory from "./Coupon/CreateCategory";
    import UpdateCategory from "./Coupon/UpdateCategory";

    export default {
        components: {StoreTable},
        props: {
            forcedTab: {
                type: Number,
                default: null,
            }
        },
        data: function () {
            return {
                tab: null,
                items: [
                    {
                        title: 'Store new coupons',
                        component: StoreTable
                    },
                    {
                        title: 'Create coupon category',
                        component: CreateCategory
                    },
                    {
                        title: 'Edit coupon category',
                        component: UpdateCategory
                    }
                ],
            }
        },
        computed: {
            dialog: {
                get() {
                    return this.$store.state.adminUi.couponModal;
                },
                set(state) {
                    if (state !== this.$store.state.adminUi.couponModal) {
                        if(state === false) {
                            this.$emit('clearforcedtab');
                            this.tab = null
                        }
                        this.$store.commit('adminUi/setCouponModal', state)
                    }
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
            forcedTab() {
                this.tab = this.forcedTab;
            }
        },
    }
</script>

<style>
    .v-slide-group__prev.v-slide-group__prev--disabled {
        display: none !important;
    }
</style>
