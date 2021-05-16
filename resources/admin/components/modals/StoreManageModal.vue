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

                <v-toolbar-title>Store management</v-toolbar-title>

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

                            <component :is="item.component" :selected-store="selectedStore" v-on:updatestore="$emit('updatestore')"></component>

                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>

        </v-card>
    </v-dialog>
</template>

<script>
import StoreCreate from "./Store/StoreCreate";
import StoreAddCSV from "./Store/StoreAddCSV";
import StoreUpdate from "./Store/StoreUpdate";

export default {
    props: {
        forcedTab: {
            type: Number,
            default: null,
        },
        selectedStore: {
            type: Object,
            default: null,
        },
    },
    data: function () {
        return {
            tab: null,

            items: [
                {
                    title: 'Create new store',
                    component: StoreCreate
                },
                {
                    title: 'Store CSV',
                    component: StoreAddCSV
                },
                {
                    title: 'Update store',
                    component: StoreUpdate
                }
            ],

        }
    },
    computed: {
        dialog: {
            get() {
                return this.$store.state.adminUi.storeModal;
            },
            set(state) {
                if (state !== this.$store.state.adminUi.storeModal) {
                    if(state === false) {
                        this.$emit('clearforcedtab');
                        this.tab = null
                    }
                    this.$store.commit('adminUi/setStoreModal', state)
                }
            }
        },

    },
    watch: {
        forcedTab(value) {
            this.tab = value;
        }
    },
    mounted() {

    },
    methods: {
    }
}
</script>

<style>
.v-slide-group__prev.v-slide-group__prev--disabled {
    display: none !important;
}
</style>
