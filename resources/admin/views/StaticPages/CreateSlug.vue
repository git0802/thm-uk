<template>

    <v-layout column>
        <v-layout>
            <v-flex class="xs12 pa-2">
                <h1 class="flex">
                    Slug pages
                </h1>
                <v-divider/>
            </v-flex>
        </v-layout>

        <v-layout column>
            <v-data-table
                :headers="table.headers"
                :items="table.data"
                :single-expand="table.singleExpand"
                :expanded.sync="table.expanded"
                :options.sync="table.options"
                sort-by="updated_at"
                sort-desc
                item-key="id"
                show-expand
                multi-sort
                class="elevation-1"
            >
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-btn
                            color="green"
                            class="white--text"
                            @click="handleBlogModal"
                        >
                            Create new page
                        </v-btn>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                </template>
                <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length">
                        <v-simple-table>
                            <template v-slot:default>
                                <tbody>
                                <tr>
                                    <td>Title: <a :href="'/' + item.slug" target="_blank">{{ item.title }}</a> </td>
                                </tr>
                                <tr>
                                    <td>OG Title: <strong>{{ item.og_title }}</strong> </td>
                                </tr>
                                <tr>
                                    <td>Description: {{ item.description }} </td>
                                </tr>
                                <tr>
                                    <td>OG Description: {{ item.og_description }} </td>
                                </tr>
                                <tr>
                                    <td>Slug: {{ item.slug }}</td>
                                </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </td>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-layout>

        <slug-manage-modal :forced-tab="forcedTab" :selected-page="selectedPage" v-on:updatepages="fetchPages" v-on:clearforcedtab="clearForcedTab" />
        <overlay-component v-if="isLoading" />
    </v-layout>

</template>

<script>
import SlugManageModal from "../../components/modals/SlugManageModal";
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";
export default {
    components: {SlugManageModal, OverlayComponent},
    data () {
        return {
            isLoading: false,
            forcedTab: null,
            selectedPage: null,
            table: {
                data: [],
                expanded: [],
                singleExpand: false,
                headers: [
                    {
                        text: 'Title',
                        sortable: true,
                        value: 'title'
                    },
                    {
                        text: 'Slug',
                        value: 'slug'
                    },
                    {
                        text: 'Order',
                        value: 'order'
                    },
                    {
                        text: 'Options',
                        value: 'actions',
                        sortable: false,
                    }
                ]
            },
        }
    },

    mounted() {
        this.fetchPages();
    },
    methods: {
        async fetchPages() {
            this.isLoading = true;
            try {
                let res = await this.$http.get(`/api/page?mode=full`)

                this.table.data = res.data.data
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
                    e.response.data.message.forEach(m => {
                        this.$store.commit('snackbar/pushMessage', {
                            message: m,
                            color: "error",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    })
                }
            }
            this.isLoading = false;
        },
        handleBlogModal() {
            this.$store.commit('adminUi/setBlogModal', !this.$store.state.adminUi.blogModal)
        },
        clearForcedTab() {
            this.forcedTab = null
        },
        editItem(page){
            this.forcedTab = 1;
            this.selectedPage = page;
            this.handleBlogModal();
        },
        async deleteItem(page) {

            if(confirm('Are you sure you want to delete this page? This action cannot be undone!')) {
                this.isLoading = true;
                try {
                    let res = await this.$http.delete(`/api/page/${page.id}`)
                    if(res.data.success) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        await this.fetchPages()
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
                        e.response.data.message.forEach(m => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: m,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })
                    }
                }
                this.isLoading = false;

            }
        }
    }
}
</script>
