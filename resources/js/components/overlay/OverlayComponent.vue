<template>
    <div class="overlay-component">
        <div id="loading"></div>
    </div>
</template>

<script>
export default {
    name: "OverlayComponent"
}
</script>

<style scoped>
    .overlay-component {
        display: flex;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: #fff;
        opacity: 0.6;
        z-index: 9;
    }
    #loading {
        width: 50px;
        height: 50px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #000;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
        margin: auto
    }

    @keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }
    @-webkit-keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }
</style>
