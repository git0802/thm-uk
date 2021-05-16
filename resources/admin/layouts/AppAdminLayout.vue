<template>
    <v-app id="inspire">
        <app-drawer/>

        <app-nav-bar/>

        <v-main>
            <v-container fluid>
                <router-view/>
                <slot/>
                <overlay-component v-if="isLoading" />
            </v-container>
        </v-main>
        <snackbar/>
    </v-app>
</template>

<script>
    import AppDrawer from "../components/AppAdminDrawer";
    import AppNavBar from "../components/AppAdminNavBar";
    import Snackbar from "../components/misc/Snackbar";
    import {mapGetters} from 'vuex'
    import OverlayComponent from '../../js/components/overlay/OverlayComponent'
    export default {
        components: {Snackbar, AppNavBar, AppDrawer, OverlayComponent},
        computed: {
            ...mapGetters({
                isLoading: 'adminUi/getIsLoading'
            })
        }
    }
</script>
<style scoped>
    .container {
        position: relative;
    }
</style>
