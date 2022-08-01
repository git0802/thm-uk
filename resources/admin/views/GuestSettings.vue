<template>
    <v-layout column>
        <overlay-component v-if="loading"/>
        <v-flex class="xs12 pa-2">
            <div style="display: inline-flex; justify-content: space-between; width: 100%">
                <h1>
                    Guest Settings
                </h1>
                <v-btn color="success" @click="updateGuestSettings">
                    Save
                </v-btn>
            </div>
            <v-divider/>
        </v-flex>

        <v-lazy>
            <v-form>
                <v-row>
                    <v-col class="d-flex" cols="12" md="12">
                      <v-checkbox
                          v-model="settings.enabled"
                          :label="`Guest Enabled`"
                          name="enabled"
                      />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col class="d-flex" cols="12" md="4">
                        <v-text-field
                            label="Guest First Name"
                            name="name"
                            v-model="settings.user.name"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col class="d-flex" cols="12" md="4">
                        <v-text-field
                            label="Guest Weight, lb"
                            name="weight"
                            v-model="settings.user.weight"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col class="d-flex" cols="12" md="4">
                        <v-text-field
                            label="Guest Height, cm"
                            name="height"
                            v-model="settings.user.height"
                            required
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col class="d-flex" cols="12" md="4">
                        <v-text-field
                            label="Guest Age"
                            name="age"
                            v-model="settings.user.age"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col class="d-flex" cols="12" md="4">
                        <v-select
                            name="gender"
                            :items="sexSelect"
                            v-model="settings.user.gender"
                            :item-value="'id'"
                            :item-text="'name'"
                            label="Select Guest Sex"
                            outlined
                            required
                        ></v-select>
                    </v-col>
                </v-row>
            </v-form>
        </v-lazy>
    </v-layout>
</template>

<script>
import OverlayComponent from "../../js/components/overlay/OverlayComponent";
export default {
    name: "GuestSettings",
    components: {OverlayComponent},
    data: function() {
        return {
            loading: false,
            settings: [],
            sexSelect: [
                {
                    'id': 'male',
                    'name': 'Male'
                },
                {
                    'id': 'female',
                    'name': 'Female'
                },
            ],
            forms: {},
        }
    },
    mounted() {
        this.fetchGuestSettings()
    },
    methods: {
        async fetchGuestSettings() {
            this.loading = true
            try {
                let res = await this.$http.get(`/api/guest/settings`)

                this.settings = res.data.settings
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

        async updateGuestSettings() {
            this.loading = true
            try {
                let res = await this.$http.post(`/api/guest/settings/update`, {
                    settings: this.settings,
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
    },
}
</script>

<style scoped>

</style>
