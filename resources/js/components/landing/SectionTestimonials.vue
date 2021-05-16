<template>
    <section class="testimonials-section">
        <div class="wrapper testimonials-section__content">
            <div class="section__caption">
                <div class="section-header__text">
                    <span>
                        Our loyal hotties
                    </span>
                </div>
                <div class="section__caption__title">
                    <span>
                        Join the portion control revolution!
                    </span>
                </div>
            </div>
            <div class="section__content">
                <hooper :infiniteScroll="true" :itemsToShow="itemsToShow">
                    <slide class="hooper-slide"
                           v-for="(testimonial, index) in testimonials"
                           :key="'key'+index"
                    >
                        <div class="image">
                            <img :src="testimonial.image" :alt="'Store Image ' + index">
                        </div>
                        <div class="name">{{ testimonial.name }}</div>
                        <div class="content">{{ testimonial.content }}</div>
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

    export default {
        name: "SectionTestimonials",
        data() {
            return {
                testimonials: [
                    {
                        id: 1,
                        name: 'Demetria Ward',
                        image: '/assets/images/testimonials/demetria1.jpeg',
                        content: 'I\'m a past customer from the old site. Just wanted to email you & say, wow, kudos. This rocks!'
                    },
                    {
                        id: 2,
                        name: 'Max R',
                        image: '/assets/images/testimonials/max.png',
                        content: 'You guys are rocking the game. I took my time & signed up...[can\'t believe] I don\'t even use my other site anymore.'
                    },
                    {
                        id: 3,
                        name: 'Kristin Murphy',
                        image: '/assets/images/testimonials/kristin.png',
                        content: 'Err, I can grocery shop AND meal plan on your site? Bless you geniuses.'
                    },
                    {
                        id: 4,
                        name: 'Darrell Simmons',
                        image: '/assets/images/testimonials/darrell.jpeg',
                        content: 'The concept won me over with the budgeting part of groceries. I spend way too much buying food without regard.'
                    },
                    {
                        id: 5,
                        name: 'Wendy',
                        image: '/assets/images/testimonials/wendy.jpeg',
                        content: 'Sure, I think it’s awesome. I’m sticking to it. Thanks for getting back to me quickly & for being funny.'
                    },
                    {
                        id: 6,
                        name: 'Aisha M.',
                        image: '/assets/images/testimonials/aisha.jpeg',
                        content: 'My testimonial is just I love that you’re running a practical kind of tracker that’s for real people!'
                    },
                    {
                        id: 7,
                        name: 'Laura Sanchez',
                        image: '/assets/images/testimonials/laura.jpeg',
                        content: 'Are these my cheekbones I see again? Ha. Seriously, been tracking my foods as Eaten on this thing. LUVss.'
                    },
                ]
            }
        },
        components: {
            Hooper,
            Slide,
            HooperPagination,
            HooperNavigation
        },
        computed: {
            itemsToShow() {
                return document.body.offsetWidth > 980 ? 4 : 1;
            }
        }
    }
</script>
<style>
    .testimonials-section .hooper-slide {
        flex-direction: column;
        align-items: flex-start;
    }
    .hooper svg.icon {
        fill: rgb(0 0 0 / 50%);
    }
    .hooper .hooper-next {
        transform: translate(150%, -50%) !important;
    }
    .hooper .hooper-prev {
        transform: translate(-150%, -50%) !important;
    }
    @media (max-width: 600px) {
        .hooper .hooper-next {
            transform: translate(70%, -50%) !important;
        }
        .hooper .hooper-prev {
            transform: translate(-70%, -50%) !important;
        }
    }
</style>

<style scoped lang="scss">
    .testimonials-section {
        padding: 50px 0;
    }
    .wrapper {
        max-width: 1256px;
        padding: 0 60px;
    }
    .section__content {
        padding: 20px 0;
    }
    .hooper {
        height: auto !important;
        svg.icon {
            fill: rgb(0 0 0 / 50%);
        }
        .hooper-next {
            transform: translate(150%, -50%) !important;
        }
        .hooper-prev {
            transform: translate(-150%, -50%) !important;
        }
        .hooper-slide {
            .image {
                width: 87px;
                height: 87px;
                border-radius: 50%;
                overflow: hidden;
                img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            }
            .name {
                font-family: Gilroy;
                font-style: normal;
                font-weight: 800;
                font-size: 24px;
                line-height: 132.19%;
                color: #B27DFF;
                margin: 14px 0 17px;
            }
            .content {
                font-family: Gilroy;
                font-style: normal;
                font-weight: 800;
                font-size: 14px;
                line-height: 17px;
                color: #000000;
                opacity: 0.7;
                width: 200px;
                height: 90px;
            }
            @media (max-width: 600px) {
                .image {
                    width: 128px;
                    height: 128px;
                }
                .name {
                    font-size: 28px;
                }
                .content {
                    font-size: 22px;
                    width: 100%;
                    line-height: 132.19%;
                    height: 140px;
                }
            }
        }
    }
    @media (max-width: 600px) {
        .wrapper {
            padding: 0 30px;
        }
    }
</style>
