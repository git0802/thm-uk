<template>
    <section class="partners-section">
        <div class="wrapper partners-section__content">
            <div>
                <hooper :infiniteScroll="true" :itemsToShow="itemsToShow">
                    <slide class="hooper-slide"
                           v-for="(image, index) in images"
                           :key="'key'+index"
                    >
                        <img :src="image.url" :alt="'Store Image ' + index">
                    </slide>
                    <hooper-navigation slot="hooper-addons"></hooper-navigation>
                    <hooper-pagination slot="hooper-addons"></hooper-pagination>
                </hooper>

            </div>
        </div>
    </section>
</template>

<script>
    import { Hooper, Slide,
        Pagination as HooperPagination,
        Navigation as HooperNavigation
    } from 'hooper';
    import 'hooper/dist/hooper.css';
    import axios from 'axios'
    export default {
        name: "SectionTwo",
        data() {
            return {
                images: []
            }
        },
        components: {
            Hooper,
            Slide,
            HooperPagination,
            HooperNavigation
        },
        methods: {
            fetchGetImages() {
                axios.get('/api/store/images')
                .then((res) => {
                    this.images = res.data.data
                })
            }
        },
        created() {
            this.fetchGetImages()
        },
        computed: {
            itemsToShow() {
                return document.body.offsetWidth > 980 ? 5 : 1;
            }
        }
    }
</script>

<style scoped lang="scss">
    section.partners-section {
        background: linear-gradient(360deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 100%);
        height: 200px;
        padding-top: 31px;
    }
    .wrapper {
        max-width: 1256px;
        padding: 0 60px;
    }
    @media screen and (max-width: 980px) {
        section.partners-section {
            background: transparent;
        }
    }
</style>
