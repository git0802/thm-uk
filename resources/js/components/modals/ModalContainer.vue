<template>
    <div class="modal-container" :style="{ 'z-index': zIndex }"
    >
        <slot/>
    </div>
</template>

<script>
    export default {
        name: "ModalContainer",
        props: {
            zIndex: {
                type: Number,
                required: true,
                default: 20
            },
        }
    }
</script>

<style scoped lang="scss">
    .modal-container {
        position: fixed;
        top: 0px;
        width: 100%;
        height: 100vh;
        overflow-y: auto;
        padding: 60px 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        @media screen and (min-width: 481px) and (max-width: 767px) {
            padding: 40px 20px;
        }
        @media screen and (max-width: 480px) {
            padding: 40px 0px 0 0px;
        }
    }
</style>


