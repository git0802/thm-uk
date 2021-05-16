<template>
    <ckeditor :editor="editor" v-model="text" :config="config" :style="{'width': '100%', 'min-height': '500px'}" />
</template>

<script>
    import Cookies from "js-cookie"
    require ('../../plugins/ckeditor/build/ckeditor')

    export default {
        props: {
            value: {
                type: String,
                default: ""
            }
        },

        data () {
            return {
                editor: ClassicEditor,
                config: {
                    simpleUpload: {
                        uploadUrl: {
                            url: '/api/image/upload',
                            headers: {
                                'Authorization': 'Bearer ' + Cookies.get("token")
                            }
                        }
                    }
                }
            }
        },

        computed: {
            text: {
                get() {
                    return this.value
                },
                set(value) {
                    this.$emit("input", value)
                }
            }
        }
    }
</script>

<style>
.ck.ck-editor {
    width: 100%;
}
.ck-editor__editable {
    min-height: 350px;
}
</style>
