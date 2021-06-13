<template>
    <v-dialog
        v-model="dialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        persistent
        :retain-focus="false"
    >
        <v-card tile>
            <v-toolbar
                flat
                dark
                color="primary"
                style="
                flex: unset"
            >
                <v-btn
                    icon
                    dark
                    @click="handleModal"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>

                <v-toolbar-title>Blog management</v-toolbar-title>

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

                            <component :is="item.component" v-on:updateposts="$emit('updateposts')" :selected-post="selectedPost"></component>

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
import BlogPostCreate from "./Blog/BlogPostCreate";
import BlogPostEdit from "./Blog/BlogPostEdit";

export default {
    components: {StoreTable},
    props: {
        forcedTab: {
            type: Number,
            default: null,
        },
        selectedPost: {
            type: Object,
            default: null,
        }
    },
    data: function () {
        return {
            tab: null,
            items: [
                {
                    title: 'Create new post',
                    component: BlogPostCreate
                },
                {
                    title: 'Edit post',
                    component: BlogPostEdit
                }
            ],
        }
    },
    computed: {
        dialog: {
            get() {
                return this.$store.state.adminUi.blogModal;
            },
            set(state) {
                if (state !== this.$store.state.adminUi.blogModal) {
                    if(state === false) {
                        this.$emit('clearforcedtab');
                        this.tab = null
                    }
                    this.$store.commit('adminUi/setBlogModal', state)
                }
            }
        },
        dialogInterceptor: {
            get() {
                return this.$store.state.adminUi.interceptedBlogModal
            }
        },
    },
    mounted() {},
    methods: {
        handleModal() {
            if(this.dialogInterceptor) {
                if(confirm("You haven't saved your changes. Are you sure your want to close")) {
                    this.dialog = false;
                    this.$store.commit('adminUi/setInterceptedBlogModal', false)
                }
            } else {
                this.dialog = false;
            }
        },
    },
    watch: {
        forcedTab() {
            if(this.forcedTab === 1) {
                this.tab = this.forcedTab;
            }
        }
    },
}
</script>

<style>
.v-slide-group__prev.v-slide-group__prev--disabled {
    display: none !important;
}
</style>
