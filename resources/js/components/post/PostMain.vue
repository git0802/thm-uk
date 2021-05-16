<template>
    <section class="post-main">
        <div class="wrapper" style="position: relative">
            <div class="post-main__container ck-content ">
                <div class="sharer-post-container">
                    <sharer/>
                </div>
                <div v-html="html"></div>
            </div>
        </div>
    </section>
</template>

<script>
    import Sharer from "../Sharer";
    export default {
        components: {Sharer},
        props: ['html'],
        name: "PostMain",
    }
</script>

<style scoped lang="scss">
    .post-main {
        position: relative;
        &__container {
            //display: flex;
            //flex-direction: column;
            background: #fff;
            padding: 37px 48px 52px 48px;
            //overflow: hidden;
            margin-bottom: 120px;
        }
        &__text {
            font-weight: 800;
            font-size: 18px;
            line-height: 151.19%;
            color: rgb(41 45 52 / 0.6);
            margin-bottom: 3em;
        }
        &__title {
            font-weight: 800;
            font-size: 36px;
            line-height: 140%;
            color: #292D34;
            margin-bottom: 0.6em;
        }
        &__img--50 {
            float: right;
            width: 50%;
            height: 328px;
            max-width: 460px;
            margin-left: 25px;
            object-fit: cover;
        }
        &__img--100 {
            width: 100%;
            height: 328px;
            margin-bottom: 35px;
            object-fit: cover;
        }
        &__bouble-box {
            display: flex;
            justify-content: space-between;
            p {
                width: calc((100% - 46px) / 2);
            }
        }
    }
    .post-bottom {
        position: relative;
        padding-top: 53px;
        display: flex;
        justify-content: space-between;
        &:after {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            height: 1px;
            width: 100vw;
            background: var(--grey-3);
            transform: translateX(-50%);
        }
        &__human {
            display: flex;
            align-items: center;
        }
        &__human-img {
            width: 74px;
            height: 74px;
            min-width: 74px;
            border-radius: 50%;
            overflow: hidden;
            object-fit: cover;
        }
        &__human--name {
            font-weight: 800;
            font-size: 24px;
            line-height: 132.19%;
            margin-left: 1.3em;
            color: var(--purple);
        }
        &__info {
            display: flex;
            align-items: center;
        }
        &__info-group {
            display: flex;
            margin-right: 1.5em;
            align-items: center;
            svg {
                width: 18px;
                fill: #D8BEFF;
                height: 14px;
                margin-right: 6px;
            }
        }
        &__info-text {
            color: #949699;
            font-size: 16px;
            font-weight: 400;
            line-height: 100%;
        }
    }
    @media screen and (max-width:767px) {
        .wrapper {
            padding: 0;
        }
        .post-main {
            &__container {
                padding: 37px 25px 48px 25px;
            }
            &__img--50 {
                float: unset;
                width: 100%;
                max-width: 100%;
                margin: 0 0 35px 0;
            }
            &__bouble-box {
                flex-direction: column;
                p {
                    width: 100%;
                    margin-bottom: 2em;
                }
            }
        }
        .post-bottom {
            flex-direction: column;
            padding-top: 48px;
            align-items: center;
            &__human[data-v-f6c8aa26] {
                margin-bottom: 40px;
            }
        }
    }
</style>
