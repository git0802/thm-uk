<template>
    <app-layout>
        <post-head :title="title" :description="description" :image="image"  />
        <post-main :html="body"/>
       <!-- <post-blogs/>-->
    </app-layout>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Post",
        metaInfo() {
            return {
                meta: [
                    { property: 'og:title', content: `${this.og_title}`},
                    { property: 'og:description', content: `${this.og_description}`},
                ]
            }
        },
        data() {
            return {
                body: '',
                og_title: '',
                og_description: '',
                title: '',
                description: '',
                image: '',
            }
        },
        created() {
            this.init();
        },
        methods: {
            init() {
                const slug = this.$router.currentRoute.params.slug;

                axios.get(`/api/post/${slug}`)
                    .then((response) => {
                        if (response.data.success) {
                            this.body = response.data.post.body;
                            this.og_title = response.data.post.og_title;
                            this.og_description = response.data.post.og_description;
                            this.title = response.data.post.title;
                            this.description = response.data.post.description;
                            this.image = response.data.post.image;
                        }
                    })
                    .catch((e) => {
                        if(e.response.status === 404) {
                            window.location.href = '/blog'
                        }
                    })
            },
        }
    }
</script>
