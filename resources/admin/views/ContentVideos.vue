<template>
    <div>
        <v-container grid-list-md text-xs-center>
            <v-dialog v-model="dialog" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                    <v-layout>
                        <v-btn
                            dark
                            v-bind="attrs"
                            v-on="on"
                            @click="openModal('create')"
                        >
                            Create New Video
                        </v-btn>
                    </v-layout>
                </template>
                <v-card>
                    <v-card-title>
                        <span v-if="status == 'update'" class="headline">Update video</span>

                        <span v-else class="headline">Create video</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid">
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            label="URL"
                                            prepend-icon="mdi-youtube"
                                            v-model="params.url"
                                            :rules="urlRules"
                                            :counter="191"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            label="Title"
                                            prepend-icon="mdi-subtitles"
                                            v-model="params.title"
                                            :rules="titleRules"
                                            :counter="191"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            label="Description"
                                            prepend-icon="mdi-grease-pencil"
                                            v-model="params.description"
                                            :rules="descriptionRules"
                                            :counter="191"
                                            required
                                        />
                                    </v-col>

                                    <v-col class="d-flex wrap" cols="12" sm="12" align-self="center" v-if="params.image">
                                        <div>
                                            Store already have&nbsp;<a :href="params.image" target="_blank">cover image </a>. If you want to change it - upload new one below.
                                        </div>
                                    </v-col>


                                    <v-col class="d-flex" cols="12" sm="12">
                                        <v-file-input
                                            color="deep-purple accent-4"
                                            accept="image/*"
                                            label="File input"
                                            placeholder="Select store image"
                                            prepend-icon="mdi-paperclip"
                                            v-model="image"
                                            :show-size="1000"
                                            outlined
                                        >
                                            <template v-slot:selection="{ index, text }">
                                                <v-chip
                                                    v-if="index < 2"
                                                    color="deep-purple accent-4"
                                                    dark
                                                    label
                                                    small
                                                >
                                                    {{ text }}
                                                </v-chip>
                                            </template>
                                        </v-file-input>
                                    </v-col>

                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-switch
                                v-model="params.deleteImage"
                                label="Delete thumbnail?"
                                color="red"
                                class="mb-5 ml-5"
                                hide-details
                                v-if="params.image"
                            ></v-switch>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                            <v-btn :disabled="!valid" v-if="status == 'update'" :loading="uploading" color="blue darken-1" text
                                   @click.prevent="updateVideo()">Save
                            </v-btn>
                            <v-btn :disabled="!valid" v-else color="blue darken-1" :loading="uploading" text
                                   @click.prevent="createVideo()">Save
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>
            <v-layout row wrap>
                <v-card
                    class="mx-1 my-4"
                    max-width="344"
                    v-for="(video, index) in videos"
                    :key="'key'+index"
                >
                    <span
                        @click="toYoutube(video.url)"
                        style="cursor: pointer"
                    >
                            <v-img
                                :src="  video.image ?  video.image : ('http://img.youtube.com/vi/' + video.youtube_id + '/hqdefault.jpg')"
                                height="200px"
                            ></v-img>

                    <v-card-title>
                        {{ video.title }}
                    </v-card-title>
                    </span>


                    <v-card-subtitle>
                        {{ video.description }}
                    </v-card-subtitle>

                    <v-card-actions class="card-actions">
                        <v-btn text @click="openModal('update', video.id, index)">Update</v-btn>
                        <v-btn
                            color="red"
                            text
                            @click="removeVideo(video.id)"
                        >
                            Delete
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
import axios from 'axios'
import {mapGetters} from "vuex";

export default {
    name: "ContentVideos",
    data() {
        return {
            uploading: false,
            videos: [],
            valid: false,
            urlRules: [
                v => !!v || 'Url is required',
                v => (v && v.length <= 191) || 'Maximum 191 characters',
                v => /^(ftp|http|https):\/\/(([a-zA-Z0-9$\-_.+!*'(),;:&=]|%[0-9a-fA-F]{2})+@)?(((25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])){3})|localhost|([a-zA-Z0-9\-\u00C0-\u017F]+\.)+([a-zA-Z]{2,}))(:[0-9]+)?(\/(([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*(\/([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*)*)?(\?([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?(\#([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?)?$/.test(v) || 'Url must be valid'
            ],
            titleRules: [
                v => !!v || 'Title is required',
                v => (v && v.length <= 191)  || 'Maximum 191 characters',
            ],
            descriptionRules: [
                v => !!v || 'Description is required',
                v => (v && v.length <= 191)  || 'Maximum 191 characters',
            ],
            dialog: false,
            params: {
                url: '',
                title: '',
                description: '',
                image: '',
                deleteImage: false,
            },
            image: null,
            status: 'create',
            current_id: null,
        }
    },
    mounted() {
        this.fetchVideos()
    },
    methods: {
        toYoutube(href) {
            window.open(href)
        },
        setParams(index, id) {
            if(this.videos.length) {
                this.params.url = this.videos[index].url;
                this.params.title = this.videos[index].title;
                this.params.description = this.videos[index].description;
                this.params.image = this.videos[index].image;
            }
            this.current_id = id
        },
        resetForm() {
            this.params.url = '';
            this.params.title = '';
            this.params.description = '';
            this.params.image = '';
            this.image = null;
            this.params.deleteImage = false;
        },
        openModal(status, id, index) {
            if(status == 'create' && this.$refs.form) {
                this.$refs.form.resetValidation()
            }
            this.resetForm()
            this.status = status;
            this.dialog = true
            if(this.videos.length > 0){
                if(status == 'update') {
                    this.setParams(index, id)
                }
            }

        },
        thenAfterFetch() {
            this.fetchVideos();
            this.resetForm();
            this.dialog = false;
        },
        async createVideo() {
            this.uploading = true;
            let data = new FormData();
            data.set('url', this.params.url)
            data.set('title', this.params.title)
            data.set('description', this.params.description)
            if(this.image) {
                data.set('image', this.image)
            }
            try {
                let res = await this.$http.post('/api/video/', data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message ?? 'Video create successfully',
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
                this.thenAfterFetch()
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
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
            this.uploading = false;
        },
        async updateVideo() {
            this.uploading = true;

            let data = new FormData();
            data.set('url', this.params.url)
            data.set('title', this.params.title)
            data.set('description', this.params.description)
            data.set('_method', 'patch')
            data.set('del_image', this.params.deleteImage)
            if(this.image) {
                data.set('image', this.image)
            }
            try {
                let res = await this.$http.post('/api/video/' + this.current_id, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                if(res.data.success) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: res.data.message ?? 'Updated create successfully',
                        color: "success",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
                this.thenAfterFetch()
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
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
            this.uploading = false;

        },
        async removeVideo(id) {
            if(confirm('Are you sure you want to delete this video? This action cannot be undone!')) {
                try {
                    let res = await this.$http.delete('/api/video/' + id)
                    if(res.data.success) {
                        this.$store.commit('snackbar/pushMessage', {
                            message: res.data.message,
                            color: "success",
                            timeout: 5000,
                            right: true,
                            bottom: true
                        })
                    }
                    await this.fetchVideos()
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
        },
        async fetchVideos() {
            this.$store.commit('adminUi/setIsLoading', true)

            try {
                let res = await this.$http.get('/api/video')
                this.videos = res.data.data;
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
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }

            this.$store.commit('adminUi/setIsLoading', false)
        }
    }
}
</script>

<style scoped>

</style>
