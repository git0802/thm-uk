<template>
    <div class="main">
        <v-data-table
            :headers="headers"
            :items="users"
            class="elevation-1"
        >
            <template v-slot:top>
                <v-toolbar color="white" flat>
                    <v-toolbar-title>Users</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                </v-toolbar>
            </template>

            <template v-slot:item.activated="{ item }">
                <v-chip :color="getColor(item.activated)" dark>{{ item.activated ? "Active" : "Not active" }}</v-chip>
            </template>

            <template v-slot:item.created_at="{ item }">
                {{ momentDate(item.created_at) }}
            </template>
            <template v-slot:item.finished_setup="{ item }">
                <v-chip :color="getColor(item.finished_setup)" dark>{{ item.finished_setup ? "Yes" : "No" }}</v-chip>

            </template>

            <template v-slot:item.paid="{ item }">
                {{ item.paid }}
            </template>

            <template v-slot:item.actions="{ item }">
                <v-btn
                    @click="sendActivation(item.id)"
                >
                    Send activation
                </v-btn>
            </template>

        </v-data-table>
        <overlay-component v-if="isLoading" />
    </div>
</template>

<script>
import moment from "moment";
import OverlayComponent from "../../js/components/overlay/OverlayComponent";
export default {
    name: "Inspires",
    components: {OverlayComponent},
    data: function () {
        return {
            isLoading: false,
            headers: [
                {
                    text: 'Email',
                    alight: 'start',
                    sortable: true,
                    value: 'email',
                },
                {
                    text: 'Status',
                    value: 'activated',
                    sortable: true
                },
                {
                    text: 'Created first planner',
                    value: 'finished_setup',
                    sortable: true
                },
                {
                    text: 'Name',
                    value: 'name',
                    sortable: true
                },
                {
                    text: 'Last Name',
                    value: 'last_name',
                    sortable: true
                },
                {
                    text: 'Registration date',
                    value: 'created_at',
                    sortable: true
                },
                {
                    text: 'Billing date',
                    value: 'billed_at',
                    sortable: true
                },
                {
                    text: 'Paid status',
                    value: 'paid',
                    sortable: true
                },
                {
                    text: 'Actions', value: 'actions', sortable: false
                },
            ],
            users: []
        }
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        notify(text, status) {
            this.$store.commit('snackbar/pushMessage', {
                message: text,
                color: status,
                timeout: 5000,
                right: true,
                bottom: true
            })
        },
        async sendActivation(id){
            this.isLoading = true;
            try {
                let res = await this.$http.post(`/api/user/resend/`+id)
                this.notify(res.data.message, 'success')
                await this.fetchUsers()
            } catch (e) {
                this.notify(e.response.data.message, 'error')
            }
            this.isLoading = false;
        },
        getColor (activated) {
            if (activated === true) return 'green'
            else return 'orange'
        },
        momentDate(date) {
            return moment(date).format('YYYY-MM-DD')
        },
        async fetchUsers() {
            this.$store.commit('adminUi/setIsLoading', true)
            try {
                let res = await this.$http.get(`/api/user/all`)
                this.users = res.data.data;
            } catch (e) {}
            this.$store.commit('adminUi/setIsLoading', false)

        },
    }
}
</script>

<style scoped>
.main {
    position: relative;
}
</style>
