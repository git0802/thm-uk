<template>
    <v-layout column>
        <overlay-component v-if="loading"/>
        <v-flex class="xs12 pa-2">
            <div style="display: inline-flex; justify-content: space-between; width: 100%">
                <h1>
                    Social Links (at footer)
                </h1>
                <v-btn color="success" @click="fetchLinks">
                    Update List
                </v-btn>
            </div>
            <v-divider/>
        </v-flex>

        <div v-for="(link, uid) in links">
            <v-lazy>
                <v-form>
                    <v-row>
                        <v-col class="d-flex" cols="12" md="4">
                            <v-text-field
                                label="Link Name"
                                name="link_name"
                                prepend-icon="mdi-form-textbox"
                                v-model="links[uid].name"
                                :rules="nameRules"
                                :counter="20"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col class="d-flex" cols="12" md="4">
                            <v-text-field
                                label="Link URL"
                                name="link_url"
                                v-model="links[uid].url"
                                :rules="urlRules"
                                prepend-icon="mdi-link-variant"
                                :counter="255"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col class="d-flex center" cols="12" md="4"  style="align-items: center;">
                            <v-btn color="info" @click="updateLink(uid)">
                                Update Link
                            </v-btn>
                            <v-btn color="error" class="ml-2" @click="deleteLink(uid)">
                                Delete Link
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-lazy>
        </div>
        <template v-for="(newbie, index) in newbies">
            <v-lazy>
                <v-form v-model="newbie.valid">
                    <v-row>
                        <v-col class="d-flex" cols="12" md="4">
                            <v-text-field
                                label="Link Name"
                                name="link_name"
                                prepend-icon="mdi-form-textbox"
                                v-model="newbie.name"
                                :rules="nameRules"
                                :counter="20"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col class="d-flex" cols="12" md="4">
                            <v-text-field
                                label="Link URL"
                                name="link_url"
                                v-model="newbie.url"
                                :rules="urlRules"
                                prepend-icon="mdi-link-variant"
                                :counter="255"
                                required
                            ></v-text-field>
                        </v-col>
                        <v-col class="d-flex center" cols="12" md="4"  style="align-items: center;">
                            <v-btn color="success" :disabled="!newbie.valid" @click="addLink(index)">
                                STORE
                            </v-btn>
                            <v-btn color="error" class="ml-2" @click="deleteNewbie(index)">
                                Cancel
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-lazy>
        </template>
        <v-btn @click="addNewbie">
            add more
        </v-btn>
    </v-layout>
</template>

<script>
import OverlayComponent from "../../js/components/overlay/OverlayComponent";
export default {
    name: "SocialLinks",
    components: {OverlayComponent},
    data: function() {
        return {
            loading: false,
            links: [],
            newbies: [],
            forms: {},
            nameRules: [
                v => !!v || 'Link Name is required',
                v => v.length <= 20 || 'Maximum 20 characters',
                v => v.length >= 2 || 'Minimum 2 characters',
            ],
            urlRules: [
                v => !!v || 'Link URL is required',
                v => v.length <= 255 || 'Maximum 20 characters',
            ]

        }
    },
    mounted() {
        this.fetchLinks()
    },
    methods: {
        deleteNewbie(index) {
            console.log(this.newbies, index)
            this.newbies.splice(index, 1)
        },
        addNewbie() {
            this.newbies.push({name: '', url: '', valid: null})
        },
        async fetchLinks() {
            this.loading = true
            try {
                let res = await this.$http.get(`/api/content/links`)

                this.links = res.data.links
            } catch(e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
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
            } finally {
              this.loading = false
            }
        },

        async addLink(index) {
            this.loading = true
            try {
                let res = await this.$http.post(`/api/content/links`, {
                    name: this.newbies[index].name,
                    url: this.newbies[index].url
                })
                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    await this.fetchLinks()
                    this.newbies.splice(index, 1)
                }

            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
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
            } finally {
                this.loading = false
            }
        },
        async updateLink(uid) {
            this.loading = true
            try {
                let res = await this.$http.patch(`/api/content/links`, {
                    uid: uid,
                    name: this.links[uid].name,
                    url: this.links[uid].url

                 })
                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message,
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                    await this.fetchLinks()
                }

            } catch (e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
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
            } finally {
                this.loading = false
            }
        },
        async deleteLink(uid) {
            if(confirm("are you sure you want to delete this social link?")) {
                this.loading = true
                try {
                    let res = await this.$http.delete(`/api/content/links`, {
                        data: {
                            uid: uid
                        }
                    })
                    if(res.data.success) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                        await this.fetchLinks()
                    }

                } catch (e) {
                    if (e.response.data.errors) {
                        Object.keys(e.response.data.errors).forEach(i => {
                            e.response.data.errors[i].forEach(z => {
                                this.$store.commit('snackbar/pushMessage', {
                                    message: z,
                                    color: "error",
                                    timeout: 5000,
                                    right: true,
                                    bottom: true
                                })
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
                } finally {
                    this.loading = false
                }
            }
        },
    },

}
</script>

<style scoped>

</style>
