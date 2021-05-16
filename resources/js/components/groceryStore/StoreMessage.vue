<template>
    <div class="message">
        <div class="message__icon">
            <icon-warning/>
        </div>
        <p>
            If you don’t see your fave store listed, you can add your own store
        </p>
    </div>
</template>

<script>
    export default {
        name: "StoreMessage"
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/mixins';

    .message {
        @include head-18-font;

        display: flex;
        width: 600px;
        align-items: center;
        text-align: center;

        @media screen and (max-width: 600px) {
            width: 300px;
        }

        @media screen and (max-width: 480px) {
            flex-direction: column;
            align-items: center;
        }

        &__icon {
            width: 18px;
            display: flex;
            margin-right: 8px;

            @media screen and (max-width: 480px) {
                margin: 0 0 6px 0;
            }
        }
    }

    .text-center {
        text-align: center;
    }
</style>
