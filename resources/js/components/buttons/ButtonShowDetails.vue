<template>
    <button class="show-details" :class="{ 'show': show }"
        @click="$emit('show'), show = !show"
    >Details
    </button>
</template>

<script>
    export default {
        name: "ButtonShowDetails",

        data() {
            return {
                show: false,
            }
        }
    }
</script>

<style scoped lang="scss">
    .show-details {
        position: relative;

        &:after {
            content: '';
            display: block;
            border-style: solid;
            border-width: 6px 5px 0 5px;
            border-color: var(--primary) transparent transparent transparent;
            position: absolute;
            top: 35%;
            left: -15px;
            transition: 250ms;
        }

        &.show {
            &:after {
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
            }
        }

        &:hover {
            cursor: pointer;
        }
    }
</style>
