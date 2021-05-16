<template>
    <v-data-table
        :headers="headers"
        :items="inspires"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar color="white" flat>
                <v-toolbar-title>Inspires</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog max-width="500px" v-model="dialog">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            class="mb-2"
                            color="primary"
                            dark
                            v-bind="attrs"
                            v-on="on"
                        >New Quote
                        </v-btn>
                    </template>
                    <v-form v-model="valid">
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col>
                                            <v-text-field label="Text" :rules="quoteRules" v-model="editedItem.quote"></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn @click="closeDialog" color="blue darken-1" text>Cancel</v-btn>
                                <v-btn @click="handleInspireAction" :loading="uploading" :disabled="!valid" color="blue darken-1" text>Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                @click="editItemAction(item)"
                class="mr-2"
                small
            >
                mdi-pencil
            </v-icon>
            <v-icon
                @click="deleteItemAction(item)"
                small
            >
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn @click="fetchInspires"  color="primary">Reset</v-btn>
        </template>
    </v-data-table>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "Inspires",
        data: function () {
            return {
                dialog: false,
                valid: false,
                quoteRules: [
                    v => !!v || 'Quote is required',
                ],
                headers: [
                    {
                        text: 'Text',
                        alight: 'start',
                        sortable: true,
                        value: 'quote',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    },
                ],
                inspires: [],
                editedIndex: -1,
                editedItem: {
                    quote: ''
                },
                defaultItem: {
                    quote: '',
                },
                uploading: false
            }
        },
        computed: {
            ...mapGetters({
                isLoading: 'adminUi/getIsLoading'
            }),
            formTitle() {
                return this.editedIndex === -1 ? 'New Inspire' : 'Edit Inspire'
            },
        },
        mounted() {
            this.fetchInspires();
        },
        methods: {
            async fetchInspires() {
                this.$store.commit('adminUi/setIsLoading', true)
                try {
                    let res = await this.$http.get(`/api/quote/all`)

                    this.inspires = res.data.quote;
                } catch (e) {}
                this.$store.commit('adminUi/setIsLoading', false)
            },
            async deleteItemAction(item) {
                if (confirm('Are you sure you want to delete this inspire?')) {
                    this.$store.commit('adminUi/setIsLoading', true)

                    try {
                        let res = await this.$http.delete(`/api/quote/${item.id}`);

                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })

                        this.inspires.splice(this.inspires.indexOf(item), 1)

                    } catch (e) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: e.response.data.message,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    }
                    this.$store.commit('adminUi/setIsLoading', false)

                }
            },
            editItemAction(item) {
                this.editedIndex = this.inspires.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            closeDialog() {
                this.dialog = false;
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },
            async handleInspireAction() {
                if (this.editedIndex > -1) {
                    this.uploading = true;
                    this.$store.commit('adminUi/setIsLoading', true)

                    try {
                        let res = await this.$http.post(`/api/quote`, {
                            quote: this.editedItem.quote
                        })

                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        Object.assign(this.inspires[this.editedIndex], this.editedItem);
                    } catch (e) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: e.response.data.message,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    }
                    this.uploading = false;

                    this.$store.commit('adminUi/setIsLoading', false)

                } else {
                    this.$store.commit('adminUi/setIsLoading', true)
                    this.uploading = true;
                    try {
                        let res = await this.$http.post(`/api/quote`, {
                            quote: this.editedItem.quote
                        })
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        this.inspires.push(res.data.quote)

                    } catch (e) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: e.response.data.message,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    }
                    this.$store.commit('adminUi/setIsLoading', false)
                    this.uploading = false;
                }
                this.closeDialog()
            }
        }
    }
</script>

<style scoped>

</style>
