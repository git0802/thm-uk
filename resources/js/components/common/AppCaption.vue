<template>
    <div class="app-caption">
        <div class="app-caption__title">{{ title}}</div>
        <div>{{ description }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                required: true,
                default: 'Caption'
            },
            description: {
                type: String,
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/mixins';

    .app-caption {
        @include title-font;

        display: flex;
        flex-direction: column;

        &__title {
            @include large-font;

            position: relative;
            padding-left: 50px;
            margin-bottom: 14px;
            color: var(--primary);
            opacity: 0.6;

            &:before {
                content: "";
                display: block;
                position: absolute;
                vertical-align: middle;
                width: 35px;
                height: 1px;
                left: 0px;
                top: 50%;
                background-color: var(--primary-light);
            }
        }
    }
</style>
