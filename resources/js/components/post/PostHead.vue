<template>
    <section class="post-head">
        <div class="wrapper">
            <span class="post-head__title">{{ title }}</span>
            <span class="post-head__text">{{ description }}</span>
        </div>
        <img :src="image" :alt="title" class="post-head__img">
    </section>
</template>

<script>
    export default {
        props: ['title', 'description', 'image'],
        name: "PostHead"
    }
</script>

<style scoped lang="scss">
    .post-head {
        padding-top: 76px;
        position: relative;
        > div {
            display: flex;
            flex-direction: column;
            min-height: 370px;
            justify-content: center;
            padding-top: 25px;
            padding-bottom: 25px;
        }
        &:after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--purple);
            opacity: 0.9;
        }
        &__title {
            font-weight: 800;
            font-size: 36px;
            line-height: 132.19%;
            z-index: 2;
            color: #fff;
            margin-bottom: 0.2em;
        }
        &__text {
            font-weight: 800;
            font-size: 18px;
            line-height: 151.19%;
            color: rgb(255 255 255 / 0.6);
            z-index: 2;
            width: 50%;
            min-width: 425px;
        }
        &__img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
    @media screen and (max-width:980px) {
        .post-head {
            padding-top: 59px;
        }
    }
    @media screen and (max-width:767px) {
        .post-head__text {
            width: 100%;
            min-width: auto;
            max-width: 425px;
        }
    }
</style>
