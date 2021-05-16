<template>
    <div class="go-back"
        @click="$emit('click')"
    >
        <icon-back class="m-r-16"/>Go back
    </div>
</template>

<script>
    import IconBack from "../svg/IconBack"

    export default {
        name: "ButtonGoBack",
        components: {
            IconBack
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';

    .go-back {
        @include large-font;

        margin-bottom: 11px;

        &:hover {
            cursor: pointer;
        }
    }

    .m-r-16 {
        margin-right: 16px;
    }
</style>



