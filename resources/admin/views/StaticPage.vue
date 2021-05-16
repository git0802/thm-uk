<template>
    <div>
        <v-tabs fixed-tabs>
            <v-tab :to="{ name: 'admin.content.static-page.landing-content' }">Landing Content</v-tab>
            <v-tab :to="{ name: 'admin.content.static-page.create-slug' }">Create Slug</v-tab>
        </v-tabs>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "StaticPage"
}
</script>

<style scoped>

</style>
