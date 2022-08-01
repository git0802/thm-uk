<template>
    <v-navigation-drawer
        v-model="drawerState"
        :clipped="$vuetify.breakpoint.lgAndUp"
        app
    >
        <v-list dense>
            <template v-for="item in items">
                <v-row
                    v-if="item.heading"
                    :key="item.heading"
                    align="center"
                >
                    <v-col cols="6">
                        <v-subheader v-if="item.heading">
                            {{ item.heading }}
                        </v-subheader>
                    </v-col>
                    <v-col
                        cols="6"
                        class="text-center"
                    >
                        <a
                            href="#!"
                            class="body-2 black--text"
                        >EDIT</a>
                    </v-col>
                </v-row>
                <v-list-group
                    v-else-if="item.children"
                    :key="item.text"
                    v-model="item.model"
                    :prepend-icon="item.model ? item.icon : item['icon-alt']"
                    append-icon=""
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item
                        v-for="(child, i) in item.children"
                        :key="i"
                        link
                        :to="child.to ? {name: child.to} : ''"
                        exact
                    >
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ child.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
                <v-list-item
                    v-else
                    :key="item.text"
                    :to="item.to ? {name: item.to} : ''"
                    exact
                >
                    <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ item.text }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>

<script>

    /**
     *
     * Use named routes!
     *
     * @titov
     */


    export default {
        name: "AppDrawer",
        data: function() {
            return {
                items: [
                    { icon: 'mdi-account', text: 'Customers', to: 'admin.customers' },
                    { icon: 'mdi-file-table', text: 'Stores', to: 'admin.stores' },
                    { icon: 'mdi-content-copy', text: 'Presets', to: 'admin.presets' },
                    { icon: 'mdi-ticket-percent', text: 'Coupon', to: 'admin.coupons' },
                    { icon: 'mdi-account', text: 'Guest Settings', to: 'admin.guest' },
                    {
                        icon: 'mdi-chevron-up',
                        'icon-alt': 'mdi-chevron-down',
                        text: 'Site Content',
                        model: false,
                        children: [
                            { text: 'Social Links', to: 'admin.content.links' },
                            { text: 'Inspires', to: 'admin.content.inspires' },
                            { text: 'Blog', to: 'admin.content.blog' },
                            { text: 'Videos', to: 'admin.content.videos' },
                            { text: 'Static Pages', to: 'admin.content.static-page' },
                        ],
                    },
                    // {
                    //     icon: 'mdi-chevron-up',
                    //     'icon-alt': 'mdi-chevron-down',
                    //     text: 'SEO',
                    //     model: false,
                    //     children: [
                    //         { text: 'Meta' },
                    //         { text: 'Tags' },
                    //         { text: 'Images' },
                    //     ],
                    // },
                ],
            }
        },
        mounted() {
        },
        methods: {
        },
        computed: {
            drawerState: {
                get() {
                    return this.$store.state.adminUi.drawer;
                },
                set(state) {
                    if(state !== this.$store.state.adminUi.drawer) {
                        this.$store.commit('adminUi/setDrawer', state)
                    }
                }

            }
        }
    }
</script>

<style scoped>

</style>
