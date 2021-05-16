<template>
    <app-layout>
        <div class="about">

            <section class="top">
                <div class="wrapper">
                    <div class="title">We LOVE eating...<br>with a natural sense of control</div>
                    <div class="text">Using everyday, normal, grocery shopping, it's easier and less disruptive to become conscious of one's calorie consumption. That's why The Hot Meal prefers not to overwhelm people with the arduous complexities of weight management. Simply shop where you normally shop, but do it with the help of our platform telling you when to stop.</div>
                </div>
            </section>

            <section class="subtop">
                <div class="wrapper">
                    <div class="title">Our Mission</div>
                    <div class="text">Our mission is to help people plan eating and develop healthy habits without the drama. It's been proven that repeated behaviours can form into habits that reprogram your mind, and it's our mission to make meal planning with us, a habit. Using our smart technology, we'll help people tackle excess eating and embrace food consciousness--without having to change their daily grocery shopping (or even restaurant-eating) routines, because we have a fully customisable approach.</div>
                </div>
            </section>

            <section class="team">
                <div class="wrapper">
                    <div class="title">Meet the Team</div>

                    <div class="members">
                        <div v-for="member in members" class="member">
                            <img class="image" :src="member.photo">

                            <div class="member-title">
                                <span class="name">{{member.name}}</span>
                                <span class="sep"></span>
                                <span class="position">{{member.position}}</span>
                            </div>

                            <div class="text">{{member.text}}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bottom">
                <div class="wrapper">
                    <div class="title">Job Vacancies</div>
                    <div class="text">
                        <p>We're always looking for brilliant, driven, passionate people in every area and can work remotely. Techies and research assistants welcome.</p>
                        <p class="contact">Drop us a line at <a href="mailto:jobs@thehotmeal.co.uk">jobs@thehotmeal.co.uk</a>.</p>
                    </div>
                </div>
            </section>

        </div>

    </app-layout>
</template>

<style scoped lang="scss">

    .wrapper {
        max-width: 1038px;
    }

    .top {
        padding-top: 200px;
        padding-bottom: 100px;

        background-image: url(/assets/images/about/top.svg);
        background-position: top right;
        background-repeat: no-repeat;

        .title {
            margin: 20px 0;
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 36px;
            line-height: 132.19%;
            color: #292D34;
        }

        .text {
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 18px;
            line-height: 151.19%;
            color: #292D34;
            opacity: 0.6;
            width: 60%;
        }
    }

    @media screen and (max-width: 767px) {
        .top {
            padding-top: 400px;
            background-position: 100% -20%;

            .text {
                width: 100%;
            }
        }
    }

    .subtop {
        padding: 40px 0;
        background-color: white;

        .title {
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 36px;
            line-height: 132.19%;
            text-align: center;
            color: #292D34;
            margin-bottom: 20px;
        }

        .text {
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 18px;
            line-height: 151.19%;
            color: #292D34;
            opacity: 0.6;
            text-align: center;
        }
    }

    .team {
        padding: 40px 0;

        .wrapper {
            background-image: url('/assets/images/about/boubles.svg');
            //background-position: top right;
            background-position: top right;
            background-repeat: no-repeat;
        }

        @media screen and (max-width: 767px) {
            .wrapper {
                background-position: 100% -50px;
            }
        }

        .title {
            font-family: Gilroy;
            font-style: normal;
            font-weight: 800;
            font-size: 36px;
            line-height: 132.19%;
            text-align: center;
            color: #292D34;
        }

        .members {
            display: flex;
            flex-wrap: wrap;

            .member {
                width: 50%;
                padding: 60px 60px;

                img.image {
                    display: block;
                    margin: 0 auto;
                    width: 100%;
                    max-width: 320px;
                }

                .member-title {
                    margin: 20px 0;

                    .name {
                        font-family: Gilroy;
                        font-style: normal;
                        font-weight: 800;
                        font-size: 24px;
                        line-height: 132.19%;
                        color: #B27DFF;

                        &:after {
                            content: " ";
                            display: inline-block;
                            position: relative;
                            background-color: #DF2C65;
                            width: 4px;
                            height: 4px;
                            border-radius: 50%;
                            margin: 5px 20px;
                        }
                    }

                    .position {
                        font-family: Gilroy;
                        font-style: normal;
                        font-weight: 300;
                        font-size: 18px;
                        line-height: 151.19%;
                        color: #292D34;
                        opacity: 0.6;
                    }
                }

                .text {
                    font-family: Gilroy;
                    font-style: normal;
                    font-weight: 800;
                    font-size: 14px;
                    line-height: 17px;
                    text-align: justify;
                    color: #000000;
                    opacity: 0.7;
                }

            }

            @media screen and (max-width: 767px) {
                .member {
                    width: 100%;
                    padding: 30px 0;

                    .member-title {
                        .name {
                            display: block;
                            text-align: center;

                            &:before, &:after {
                                content: " ";
                                display: inline-block;
                                position: relative;
                                background-color: #DF2C65;
                                width: 4px;
                                height: 4px;
                                border-radius: 50%;
                                margin: 5px 10px;
                            }
                        }
                        .position {
                            display: block;
                            text-align: center;
                            margin-top: 10px;
                        }
                    }
                }
            }
        }
    }

    .bottom {
        background: #F6E3FF;
        padding: 90px 0;

        .wrapper {
            display: flex;
            flex-wrap: wrap;

            .title {
                width: 40%;
                font-family: Gilroy;
                font-style: normal;
                font-weight: 800;
                font-size: 36px;
                line-height: 132.19%;
                color: #292D34;
                margin: 20px 0;
            }

            .text {
                width: 60%;

                p {
                    font-family: Gilroy;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 18px;
                    line-height: 151.19%;
                    color: #292D34;
                    opacity: 0.6;
                    margin-bottom: 20px;
                }
                p.contact {
                    font-weight: bold;

                    a {
                        color: #DF2C65;
                    }
                }
            }

            @media screen and (max-width: 767px) {
                .title, .text {
                    width: 100%;
                }
            }

        }

    }


    @media screen and (max-width: 767px) {
        .subtop {
            .title {
                text-align: left;
            }
            .text {
                text-align: left;
            }
        }
        .team {
            .title {
                text-align: left;
            }
        }
        .bottom {
            padding: 30px 0;

            .title {
                text-align: left;
            }

        }
    }
</style>

<script>
    export default {
        data() {
            return {
                members: [
                    { name: "China",    photo: "/assets/images/about/china.png",    position: "Tech & Management",  text: "Loves eating and has a vegetarian recipe for everything! Her best part of the site is the analytics, because she likes to see how close she is to her goals. Her background as a television personality makes her keen to keep fit! Her tech and management training encompasses cybersecurity and all things digital."},
                    { name: "Tara",     photo: "/assets/images/about/tara.png",     position: "Tech & Management",  text: "Loves dining out and uses her nutrition background to cleverly customise the site for her eating style. She's responsible for lots of tech management and project supervision."},
                    // { name: "Tara",     photo: "/assets/images/about/tara2.png",     position: "Tech & Management",  text: "Loves dining out and uses her nutrition background to cleverly customise the site for her eating style. She's responsible for lots of tech management and project supervision."},
                    { name: "Janice",   photo: "/assets/images/about/janice.png",   position: "Operations",         text: "Loves meat and had spontaneous cooking competitions with the boss until the pandemic slowed things down! Enjoys the fact that she can grocery shop on The Hot Meal, and add any items she finds missing. "},
                    // { name: "Mateo",    photo: "/assets/images/about/mateo.png",    position: "Operations",         text: "Newbie who has been excited about the site from Day 1 of joining. Loves organizing group emails, helping with upcoming concepts, and bouncing ideas with everyone."},
                    // { name: "Jonathan", photo: "/assets/images/about/jonathan.png", position: "Health & Nutrition", text: "Loves consulting on health and nutrition questions that arise during meetings and sometimes checks everyone's \"steps per day.\""},
                    { name: "Ziggy",    photo: "/assets/images/about/ziggy.png",    position: "Canine Rep.",        text: "Loves bones, people, hugs, kisses, and barking when bored. Enjoys being a helpful assistant."}
                ]
            }
        },
    }
</script>
