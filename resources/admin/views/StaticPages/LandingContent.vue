<template>
    <v-row justify="center">
        <v-expansion-panels inset>
            <v-expansion-panel
                v-for="(content,index) in site_content"
                :key="'key'+index"

            >
                <v-expansion-panel-header>
                    <span>
                        {{content.block_name}}
                    </span>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <div>
                        <span>title: {{content.title}}</span>
                    </div>
                    <div class="my-2">
                        <span>description: <span v-html="content.description"></span></span>
                    </div>
                    <v-dialog v-model="dialog" persistent max-width="600px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                @click="setParams(index, content.id)"
                            >
                                Edit
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">Edit your block</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="params.block_name"
                                                label="Block Name"
                                                :rules="nameRules"
                                                :counter="191"
                                                required
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="params.title"
                                                label="Title"
                                                :rules="titleRules"
                                                :counter="191"
                                                required
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-textarea
                                                v-model="params.description"
                                                name="input-7-1"
                                                label="Description"
                                                hint="Hint text"
                                                :rules="descriptionRules"
                                                required
                                            />
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                                <v-btn color="blue darken-1" :loading="uploading" text @click="saveChanges(content.id)">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
        <overlay-component v-if="isLoading"/>
    </v-row>
</template>

<script>
import { mapGetters } from "vuex";
import OverlayComponent from '../../../js/components/overlay/OverlayComponent.vue'
export default {
    name: "admin.content.static-page.landing-content",
    components:{OverlayComponent},
    data() {
        return {
            isLoading: false,
            uploading: false,
            dialog: false,
            params: {
                block_name: '',
                title: '',
                description: ''
            },
            content_id: null,
            valid: false,
            nameRules: [
                v => !!v || 'Block Name is required',
                v => v.length <= 191 || 'Maximum 191 characters',
            ],
            titleRules: [
                v => !!v || 'Title is required',
                v => v.length <= 191 || 'Maximum 191 characters',
            ],
            descriptionRules: [
                v => !!v || 'Description is required',
            ],
            site_content: []
        }
    },
    methods: {
        async saveChanges() {
            this.uploading = true;
            let data = {
                id: this.content_id,
                block_name: this.params.block_name,
                title: this.params.title,
                description: this.params.description,
            };
            try {
                let res = await this.$http.post(`/api/content/update/` + data.id , {
                    block_name: data.block_name,
                    title: data.title,
                    description: data.description,
                });
                this.dialog = false;
                await this.getContent();
            } catch (e) {

            }

            this.uploading = false;
        },
        setParams(index, id) {
            this.params.block_name = this.site_content[index].block_name;
            this.params.title = this.site_content[index].title;
            this.params.description = this.site_content[index].description;
            this.content_id = id
        },
        async getContent() {
            this.isLoading = true;
            try {
                let res = await this.$http.get(`/api/content`)
                this.site_content = res.data.data;
            } catch (e) {

            }
            this.isLoading = false;
        },
    },
    created() {
        this.getContent()
    }
}
</script>

<style scoped>
    .row {
        padding: 2rem;
        position: relative;
    }
</style>
