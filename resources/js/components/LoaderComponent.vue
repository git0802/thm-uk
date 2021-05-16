<template>
    <div class="loader-container" v-if="loading">
        <div>
            <div class="base-loader">Loading...</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoaderComponent",
    props: {
        loading: {
            type: Boolean,
            default: false,
        }
    }
}
</script>

<style scoped lang="scss">
.loader-container {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 5;
}

</style>
