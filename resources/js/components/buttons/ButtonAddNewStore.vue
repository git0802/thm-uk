<template>
    <div class="add-new-store"
        @click="$emit('click')"
    >
        <icon-add-store/>
        <div> Add new store</div>
    </div>
</template>

<script>
    export default {
        name: "ButtonAddStore"
    }
</script>

<style lang="scss">
    @import '../../../sass/_mixins.scss';

    .add-new-store {
        @include head-16-font;

        width: 130px;
        height: 130px;
        background: var(--white);
        border: 2px solid var(--purple);
        border-radius: 50%;
        color: var(--purple);
        margin-left: 20px;
        text-align: center;

        svg {
            margin: 45px 0px;
            @media screen and (max-width: 767px) {
                margin: 34px 0px;
            }
        }

        &:hover {
            border: 2px solid var(--primary);
            color: var(--primary);
            cursor: pointer;
            transition: 400ms;

            .icon {
                fill: var(--primary);
                stroke: var(--primary);
                transition: 400ms;
            }
        }

        @media screen and (max-width: 767px) {
            width: 110px;
            height: 110px;
        }
    }
</style>
