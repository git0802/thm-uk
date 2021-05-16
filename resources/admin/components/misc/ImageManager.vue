<template>
    <div>
        <div style="display: flex; justify-content: space-between">
            <span style="margin-bottom: 15px">
            Just drag image to text editor
            </span>

            <v-btn
                color="info"
                class="white--text"
                @click="fetchImages"
                :disabled="isBusy"
                :loading="isBusy"
            >
                Update image list
            </v-btn>

        </div>
        <div class="image-manager">
            <div class="image-manager__card" v-for="item in list">
                <div class="image_manager__card-preview">
                    <figure>
                        <img :src="item.url">
                    </figure>
                </div>
                <div class="image_manager__card-caption">
                    <div class="card-caption__date">
                        {{ item.date }}
                    </div>
                    <div class="card-caption__url">
                        <a :href="item.url" target="_blank">
                            URL
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <mask id="clip32" mask-type="alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="16" height="16">
                                    <rect width="16" height="16" transform="matrix(1 0 0 -1 0 16)" fill="url(#pattern0)"/>
                                </mask>
                                <g mask="url(#clip32)">
                                    <rect width="16" height="16" fill="#666666"/>
                                </g>
                                <defs>
                                    <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0" transform="scale(0.02)"/>
                                    </pattern>
                                    <image id="image0" width="50" height="50" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAADqklEQVRogc2aS0hUURjHf6MSgb3TJIgWhigRUYtCyxZWUJSRBT1sVdSux0oQQsggKKJo0QNqE9EmbFmQRIuitBeaaZsiioSiiCkykl6MLc5M3b57zvXemW/mzh/u5p7v/v/ff+55fedOgmA0As1AEzAHqAQ+Ae+BXuA6cBP4PQ5PbFgJ3AfGQlwvgFYgEUumDiSATiBFOBPeqwsoL3jGFpQAV4luwHv1Y7pfrDiKPbkBoA1YDtQADcBeoMcRP0iMZlbi706jwC7Mm3KhBUhSRGbkwB4F6kM+WwMMUwRmGi1J7IzIUU0RmDmGf0wEdScXqoE3xGjmoRBuy4ErVjNvhejyHPliM/NDCNYqcMZi5oMQCztbjYeCmxkQQvsUuYPMTFPUAeCsEOlR5neZuQGUagqts4hs1BTAbaZDU6QMsxX3CiQxK7YmqoF3QmcEqNAUacX/aw2nxTVRD/wSOp2aAglMPaFlpho4CGzFX3BdEBpPskvZjXJMPSHNvCGamS2YTWfm+YOifbHgTwFVuSRuQyVmaszWzGb85YCcBROYut8bs0Qhdx+yNbMQ+Gp57rgldkjENHvaSlFcMLMxc88SfwYzK0q8EnFN6fu1wOv0vVvA1NytRDNjW4tOOninAD9FbF267aK4/wClHUCQmVmeOHloMYh71d4hYj/z763JGW0MeJRvM4fS7ROAL6Jtm4OrDHgmYq942udirzQfA9PzZaY93TZf3P8BTHLwnLIkuUbEuMrmPmCGlpluTP/uBian768Sgi8tz07AvzkdA+44tFxm+oGZuVuxY4MQG7LEXLIk9RVYEMDrMjOA8v4sg6VCKIl/W/JNxKSATSG4XWaekofirAr/ar5IxNzytI0QzkQGQd1M/axZVpnnRftM4ARwjn9rRhS4zHSh/BXgsBD4hV7tn4HLzHZNkQpMl/EKvEO/nqnBf9b8HPs2KGt04P+1opYAYdBi0VmrKVCKOVAohJleoXFamZ9p5FbPhMUBwd+nyP0Xrr3ZMDBPSWOZ4H6vxOtDrpXmeKgTvN8VOJ3Ip5kV+N92XpEvM+2Crze3NMNB20yJhe+ISqYhEDQBRDWz28KjvYsIRJCZsMezDfx/ZjYG3FXPNARcZpKYFduFEmAPfhMpzMCPBS4zmUG7H7NO1GKSbA+IL9jYcKES+/FslKuL7L4+q2MicJnoBlKYT+pFYSKDBKaeeE44E3dxjIli+Y9VGbAaWI/5PD4bc471ETOr3QauYU4erfgDd4VqDQ3HH5wAAAAASUVORK5CYII="/>
                                </defs>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
export default {
    name: "ImageManager",
    data: function() {
        return {
            list: {},
            isBusy: false,
        }
    },
    mounted() {
        this.fetchImages()
    },
    methods: {
        async fetchImages() {
            this.isBusy = true;
            try {
                let res = await this.$http.get('/api/image/list');
                this.list = res.data.data;
                this.isBusy = false;
            } catch (e) {
                this.isBusy = false;
                console.log(e);
            }
        }
    }
}
</script>

<style scoped lang="scss">
    .update-images {
        cursor: pointer;
        color: #292d34;
    }

    .image-manager {
        //font-size: 0;
        //width: calc(100% + 30px);
        //margin-left: -30px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(12rem, 3fr));
        grid-gap: 20px;
        &__card {
            //display: inline-block;
            //width: calc((100% - 150px) / 5);
            //vertical-align: top;
            //max-width: unset!important;
            //margin-left: 30px;
            //margin-bottom: 30px kill $9
        }
    }
    .card-caption {
        &__url {
            cursor: pointer;
        }
    }

    .image_manager__card-preview {
        figure {
            cursor: grab;
            img {
                width: 100%;
            }
        }
    }

</style>
