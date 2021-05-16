<template>
    <div class="close-modal"
        @click="$emit('click')"
    >
        <icon-close/>
    </div>
</template>

<script>
    import IconClose from "../svg/IconClose";
    export default {
        name: "ButtonCloseModal",
        components: {IconClose}
    }
</script>

<style scoped lang="scss">
    .close-modal {
        position: absolute;
        right: 20px;
        top: 20px;

        &:hover {
            cursor: pointer;
        }
    }
</style>


