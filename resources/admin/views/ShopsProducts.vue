<template>
    <v-layout column>
        <v-flex class="xs12 pb-5">
            <h2>
                Store new store
            </h2>
        </v-flex>
        <v-layout column>
            <adding-store-c-s-v/>
        </v-layout>
    </v-layout>
</template>

<script>
    import AddingStoreCSV from "../components/form/AddingStoreCSV";
    export default {
        name: "ShopsProducts",
        components: {AddingStoreCSV},
    }
</script>

<style scoped>

</style>
