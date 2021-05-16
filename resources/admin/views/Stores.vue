<template>
    <v-layout column>
        <v-flex class="xs12 pb-5">
            <h1>
                Stores
            </h1>
            <v-divider/>
        </v-flex>
        <v-layout column>
                <v-flex class="xs12">
                    <v-data-table
                        :headers="table.headers"
                        :items="table.data"
                        class="elevation-1"
                    >
                        <template v-slot:top>
                            <v-toolbar flat style="overflow-x: auto;">
                                <v-btn
                                    color="green"
                                    class="white--text ml-2 mb-2"
                                    @click="handleStoreModal"
                                >
                                    Create new <strong>&nbsp;default&nbsp;</strong> store
                                </v-btn>
                                <v-btn
                                    color="info"
                                    class="white--text ml-2 mb-2"
                                    @click="openCsvTab"
                                >
                                    Add CSV
                                </v-btn>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                        </template>

                        <template v-slot:item.image="{ item }">
                            <a :href="item.image" target="_blank">URL</a>
                        </template>

                        <template v-slot:item.in_progress="{ item }">
                            <v-chip :color="item.in_progress ? 'yellow' : 'green'" :class="{'black--text': item.in_progress}" dark>
                                {{ item.in_progress ? 'In progress' : 'None' }}
                            </v-chip>
                        </template>

                        <template v-slot:item.actions="{ item }">
                            <v-icon
                                small
                                @click="updateStore(item)"
                            >
                                mdi-pencil
                            </v-icon>
                            <v-icon
                                small
                                @click="deleteStore(item)"
                            >
                                mdi-delete
                            </v-icon>
                        </template>


                    </v-data-table>
                </v-flex>

            <store-manage-modal :forced-tab="forcedTab" :selected-store="selectedStore" v-on:updatestore="fetchStores"  v-on:clearforcedtab="clearForcedTab" />
        </v-layout>
    </v-layout>
</template>

<script>
import StoreManageModal from "../components/modals/StoreManageModal";
export default {
    name: "Stores",
    components: {StoreManageModal},
    data: function() {
        return {
            forcedTab: null,
            selectedStore: null,
            table: {
                data: [],
                headers: [
                    {
                        text: 'Name',
                        sortable: true,
                        value: 'name'
                    },
                    {
                        text: 'Total products',
                        sortable: true,
                        value: 'product_count'
                    },
                    {
                        text: 'Fetching in progress',
                        sortable: true,
                        value: 'in_progress'
                    },
                    {
                        text: 'Image',
                        sortable: false,
                        value: 'image'
                    },
                    {
                        text: 'Options',
                        value: 'actions',
                        sortable: false,
                    }
                ]
            }
        }
    },
    computed: {
    },
    mounted() {
        this.fetchStores();
    },
    methods: {
        clearForcedTab() {
            this.forcedTab = null
        },
        openCsvTab() {
          this.forcedTab = 1
          this.handleStoreModal()
        },
        updateStore(store) {
            this.selectedStore = store
            this.forcedTab = 2
            this.handleStoreModal()
        },
        async fetchStores() {
            this.$store.commit('adminUi/setIsLoading', true)

            try {
                let res = await this.$http.get('/api/store/defaults')

                this.table.data = res.data.data;
            } catch (e) {}
            this.$store.commit('adminUi/setIsLoading', false)

        },
        handleStoreModal() {
            this.$store.commit('adminUi/setStoreModal', !this.$store.state.adminUi.storeModal)
        },
        async deleteStore(item) {
            if(confirm('Are you sure you want to delete this store? ' +
                'This cannot be undone and all products will be deleted with it!')) {
                if(confirm('ARE YOU REALLY SURE?')) {
                    try {
                        let res = await this.$http.delete(`/api/store/${item.id}`)
                        if(res.data.success) {
                            this.$store.commit('snackbar/pushMessage', {
                                message: res.data.message,
                                color: "success",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                            await this.fetchStores()
                        }
                    } catch (e) {
                        if(e.response.data.errors) {
                            Object.keys(e.response.data.errors).forEach(i => {
                                this.$store.commit('snackbar/pushMessage', {
                                    message: e.response.data.errors[i],
                                    color: "error",
                                    timeout: 5000,
                                    right: true,
                                    bottom: true
                                })
                            })
                        } else if (e.response.data.message) {
                            this.$store.commit('snackbar/pushMessage', {
                                message: e.response.data.message,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        }
                    }
                }
            }
        },
    }
}
</script>

<style scoped>

</style>
