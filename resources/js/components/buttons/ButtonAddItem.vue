<template>
    <div class="add-item"
        @click="$emit('click')"
    >
        {{ title }}<icon-add-item/>
    </div>
</template>

<script>
import IconAddItem from "../svg/IconAddItem";
    export default {
        name: "ButtonAddItem",
        components: {
            IconAddItem
        },
        props: {
            title: {
                type: String,
                required: true,
                default: 'Add item'
            }
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/_mixins.scss';

    .add-item {
        @include head-14-font;

        display: flex;
        color: var(--primary);
        white-space: nowrap;
        padding-left: 10px;
        transition: .1s linear;

        svg {
            display: inline-block;
            margin-left: 8px;
        }

        &:hover {
            cursor: pointer;
            color: var(--primary-dark);
            transition: 400ms;

            .icon {
                fill: var(--primary-dark);
                transition: 400ms;
            }
        }
    }
</style>
