<template>
    <div>
        <v-snackbars :objects.sync="messages"></v-snackbars>
    </div>
</template>

<script>
    import VSnackbars from "v-snackbars"

    export default {
        name: "Snackbar",
        components:{
            "v-snackbars": VSnackbars
        },
        data: function() {
            return {
                proxy: []
            }
        },
        computed: {
            messages: {
                get() {
                    return this.$store.state.snackbar.messages;
                },
                set(val) {
                    this.$store.commit('snackbar/setMessages', val);
                }
            }
        },
    }
</script>

<style scoped>

</style>
