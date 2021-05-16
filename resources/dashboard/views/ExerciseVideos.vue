<template>
    <div class="container__main__slot">
        <div class="container__head top-head top-head--sticky">
            <app-left-drawer-burger/>
            <div class="title">
                Bite-Sized Fitness Videos & Daily Meditations
            </div>
        </div>
        <div class="svg-block" v-if="!videos.length">
            <svg viewBox="0 0 135 135" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.8" d="M123.144 134.252H11.372C5.1664 134.252 0.134277 129.22 0.134277 123.014V11.2376C0.134277 5.03214 5.1664 0 11.372 0H123.144C129.35 0 134.382 5.03214 134.382 11.2376V123.014C134.382 129.22 129.35 134.252 123.144 134.252Z" fill="url(#paint0_linear)"/>
                <path opacity="0.52" d="M123.011 134.413H11.2377C5.03212 134.413 0 129.381 0 123.175V11.3988C0 5.19327 5.03212 0.161133 11.2377 0.161133H123.011C129.216 0.161133 134.248 5.19327 134.248 11.3988V123.175C134.248 129.381 129.216 134.413 123.011 134.413Z" fill="url(#paint1_linear)"/>
                <path opacity="0.72" d="M48.3208 81.5797H40.7803V74.043H42.1692V80.1908H48.3208V81.5797Z" fill="url(#paint2_linear)"/>
                <path opacity="0.72" d="M93.2597 81.5797H85.7153V80.1908H91.8709V74.043H93.2597V81.5797Z" fill="url(#paint3_linear)"/>
                <path opacity="0.72" d="M42.1696 42.7065H40.7808V35.1699H48.3213V36.5587H42.1696V42.7065Z" fill="url(#paint4_linear)"/>
                <path opacity="0.72" d="M93.2597 42.7075H91.8709V36.5597H85.7153V35.1709H93.2597V42.7075Z" fill="url(#paint5_linear)"/>
                <path opacity="0.72" d="M82.9573 90.3818H44.2661V93.071H82.9573V90.3818Z" fill="url(#paint6_linear)"/>
                <path opacity="0.72" d="M70.7386 96.8418H44.2661V99.5312H70.7386V96.8418Z" fill="url(#paint7_linear)"/>
                <path opacity="0.72" d="M83.9724 58.377L55.0029 41.6533V75.1008L83.9724 58.377Z" fill="url(#paint8_linear)"/>
                <defs>
                    <linearGradient id="paint0_linear" x1="67.2572" y1="136.51" x2="67.3256" y2="0.161561" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#ECECEC"/>
                        <stop offset="0.598958" stop-color="white"/>
                    </linearGradient>
                    <linearGradient id="paint1_linear" x1="67.1221" y1="155.416" x2="67.1221" y2="-32.5655" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint2_linear" x1="44.5514" y1="184.917" x2="44.5514" y2="-30.5574" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint3_linear" x1="89.4883" y1="184.917" x2="89.4883" y2="-30.5574" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint4_linear" x1="44.5518" y1="146.044" x2="44.5518" y2="-69.4306" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint5_linear" x1="89.4883" y1="146.045" x2="89.4883" y2="-69.4297" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint6_linear" x1="63.6157" y1="129.943" x2="63.6157" y2="53.059" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint7_linear" x1="57.5051" y1="136.404" x2="57.5051" y2="59.5186" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                    <linearGradient id="paint8_linear" x1="69.4907" y1="533.712" x2="69.4907" y2="-422.561" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#DF2C65"/>
                        <stop offset="0.2021" stop-color="#DF2C65"/>
                        <stop offset="1" stop-color="#B27DFF"/>
                    </linearGradient>
                </defs>
            </svg>

            <div class="coming-soon"><span>Videos Coming Soon</span></div>
        </div>
        <div v-else class="container__content" style="width: 100%;">
            <div  class="video-container">
                <div class="video-card shadowbox" v-for="video in videos">
                    <a :href="video.url" class="youtube-link" target="_blank"></a>
                    <div class="video-card__thumbnail">
                        <img height="256"
                             :src="  video.image ?  video.image : ('http://img.youtube.com/vi/' + video.youtube_id + '/hqdefault.jpg')"
                             alt="">
                        <play-button/>
                    </div>
                    <div class="video-card__caption">
                        <div class="video-card__caption__title">
                            {{video.title}}
                        </div>
                        <div class="video-card__caption__sub">
                            {{video.description}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import getVideoId from 'get-video-id';
    import PlayButton from "../components/icons/PlayButton";

    export default {
        name: "ExerciseVideos",
        components: {PlayButton},
        data: function() {
            return {
                videos: [],
            }
        },
        async mounted() {
            await this.fetchVideos();
        },
        methods: {
            getVideoId,
            async fetchVideos() {
                try {
                    let res = await this.$http.get('/api/video');
                    this.videos = res.data.data;

                } catch (e) {

                }
            },

        }
    }
</script>

<style scoped lang="scss">
    .coming-soon {
        margin-top: 2rem;
        text-align: center;
        font-size: 18px;
    }
    .svg-block {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 60px 20px;
        svg {
            max-width: 340px;
            width: 100%;
        }
    }
    .youtube-link {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1;
    }


    .video-container {
        margin-top: 25px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(20rem, 3fr));
        grid-gap: 20px;
        align-items: stretch;
        padding: 0 10px;
        @media screen and (max-width: 480px) {
            display: flex;
            flex-direction: column;
        }
    }


    .video-card {
        position: relative;
        cursor: pointer;
        &__thumbnail {
            pointer-events: none;
            position: relative;
            height: 186px;
            img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
        &__caption {
            padding: 20px 25px;
            min-height: 120px;
            &__title {
                color: var(--purple);
                font-size: 18px;
                margin-bottom: 8px;
                line-height: 1.2;
                -webkit-line-clamp: 2;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            &__sub {
                font-size: 14px;
                line-height: 1.3;
                -webkit-line-clamp: 3;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

        }
    }
</style>
