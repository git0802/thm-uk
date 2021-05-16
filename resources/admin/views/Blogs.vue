<template>
    <v-layout column>
        <v-layout>
            <v-flex class="xs12 pa-2">
                <h1 class="flex">
                    Blog posts
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
                <template v-slot:item.updated_at="{ item }">
                       {{ formatDate(item.updated_at) }}
                </template>
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-btn
                            color="green"
                            class="white--text"
                            @click="handleBlogModal"
                        >
                            Create new post
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
                                    <td>Image: <a :href="item.image" target="_blank">image url</a></td>
                                </tr>
                                <tr>
                                    <td>OG Title: <strong>{{ item.og_title }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Description: {{ item.description }}</td>
                                </tr>
                                <tr>
                                    <td>OG Description: {{ item.og_description }}</td>
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

                <template v-slot:item.is_featured="{ item }">
                    {{ item.is_featured ? 'true' : 'false' }}
                </template>

            </v-data-table>
        </v-layout>

        <blog-post-manage-modal :forced-tab="forcedTab" :selected-post="selectedPost" v-on:updateposts="fetchBlogPosts"
                                v-on:clearforcedtab="clearForcedTab"/>
    </v-layout>

</template>

<script>
import BlogPostManageModal from "../components/modals/BlogPostManageModal";
import moment from "moment";

export default {
    components: {BlogPostManageModal},
    data() {
        return {
            forcedTab: null,
            selectedPost: null,
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
                        text: 'Is featured',
                        value: 'is_featured'
                    },
                    {
                        text: 'Views',
                        value: 'views'
                    },
                    {
                        text: 'Taxonomy',
                        value: 'taxonomy'
                    },
                    {
                        text: 'Latest update',
                        value: 'updated_at'
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
        this.fetchBlogPosts();
    },
    methods: {
        formatDate(val) {
            return moment.utc(val).format('DD.MM.YYYY');
        },
        async fetchBlogPosts() {
            this.$store.commit('adminUi/setIsLoading', true)
            try {
                let res = await this.$http.get(`/api/post/all_blogs`)

                this.table.data = res.data.posts
            } catch (e) {
                if (e.response.data.errors) {
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
            this.$store.commit('adminUi/setIsLoading', false)
        },
        handleBlogModal() {
            this.$store.commit('adminUi/setBlogModal', !this.$store.state.adminUi.blogModal)
        },
        clearForcedTab() {
            this.forcedTab = null
        },
        editItem(post) {
            this.forcedTab = 1;
            this.selectedPost = post;
            this.handleBlogModal();
        },
        async deleteItem(page) {

            if (confirm('Are you sure you want to delete this post? This action cannot be undone!')) {
                this.$store.commit('adminUi/setIsLoading', true)

                try {
                    let res = await this.$http.delete(`/api/post/${page.id}`)
                    if (res.data.success) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        await this.fetchBlogPosts()
                    }
                } catch (e) {
                    if (e.response.data.errors) {
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
                this.$store.commit('adminUi/setIsLoading', false)

            }
        }
    }
}
</script>
