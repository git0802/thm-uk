<template>
    <v-app-bar
        :clipped-left="$vuetify.breakpoint.lgAndUp"
        app
        color="blue darken-3"
        dark
    >
        <v-app-bar-nav-icon
            @click.stop="handleDrawer"
        ></v-app-bar-nav-icon>
        <v-toolbar-title
            @click="routerPush"
            style=" cursor: pointer; width: 300px;"
            class="ml-0 pl-4"
        >
            <span class="hidden-sm-and-down">TheHotMeal</span>
        </v-toolbar-title>

        <v-spacer></v-spacer>



        <v-menu
            v-model="menu"
            :close-on-content-click="false"
            :nudge-width="200"
            offset-x
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    icon
                    large
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-avatar
                        size="32px"
                        item
                    >
                        <v-img
                            :src="user.avatar ? user.avatar : '/assets/images/play_red.svg'"
                            alt="Vuetify"
                        ></v-img>
                    </v-avatar>
                </v-btn>


            </template>

            <v-card>
                <v-list>
                    <v-list-item>
                        <v-list-item-avatar>
                            <img :src="user.avatar ? user.avatar : '/assets/images/play_red.svg'" alt="John">
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>{{ user.name + ' ' + user.last_name }}</v-list-item-title>
                        </v-list-item-content>

                    </v-list-item>
                </v-list>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="error" text @click="handleLogout">Logout</v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>


    </v-app-bar>
</template>

<script>
    export default {
        name: "AppNavBar",
        data: function() {
            return {
                menu: false,

            }
        },
        computed: {
            user() {
                return this.$store.state.auth.user;
            }
        },
        methods: {
            handleDrawer() {
                this.$store.commit('adminUi/setDrawer', !this.$store.state.adminUi.drawer)
            },
            routerPush() {
                this.$router.push('/admin').catch(()=>{});
            },
            handleLogout() {
                this.$store.dispatch('auth/signOut', this.$http)
            }

        }
    }
</script>

<style scoped>

</style>
