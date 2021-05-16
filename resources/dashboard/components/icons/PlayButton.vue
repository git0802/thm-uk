<template>
    <svg width="88" height="87" viewBox="0 0 88 87" fill="none" xmlns="http://www.w3.org/2000/svg" class="play-button--absolute">
        <g filter="url(#filter0_d)">
            <path d="M43.7588 5.16553C23.1846 5.16553 6.50537 21.8444 6.50537 42.419C6.50537 62.9935 23.1846 79.6724 43.7588 79.6724C64.3331 79.6724 81.0123 62.9935 81.0123 42.419C81.0123 21.8444 64.3331 5.16553 43.7588 5.16553ZM56.6345 44.3937L38.0078 56.0354C37.6309 56.2707 37.2024 56.389 36.7738 56.389C36.3855 56.389 35.9967 56.2924 35.6448 56.0968C34.9047 55.6864 34.4455 54.9076 34.4455 54.0607V30.7773C34.4455 29.9303 34.9047 29.1515 35.6448 28.7411C36.385 28.3284 37.29 28.3535 38.0078 28.8025L56.6345 40.4443C57.315 40.8706 57.7289 41.6164 57.7289 42.419C57.7289 43.2215 57.315 43.9675 56.6345 44.3937Z" fill="white"/>
        </g>
        <path d="M57.0499 44.4576L37.8223 56.4744C37.4332 56.7173 36.9909 56.8394 36.5485 56.8394C36.1477 56.8394 35.7463 56.7397 35.3831 56.5378C34.6191 56.1142 34.145 55.3103 34.145 54.4361V30.4025C34.145 29.5283 34.6191 28.7244 35.3831 28.3008C36.1471 27.8748 37.0813 27.9006 37.8223 28.3642L57.0499 40.3809C57.7523 40.8211 58.1795 41.5909 58.1795 42.4193C58.1795 43.2477 57.7523 44.0177 57.0499 44.4576Z" fill="#DF2C65"/>
        <path d="M57.0499 44.4576L37.8223 56.4744C37.4332 56.7173 36.9909 56.8394 36.5485 56.8394C36.1477 56.8394 35.7463 56.7397 35.3831 56.5378C34.6191 56.1142 34.145 55.3103 34.145 54.4361V30.4025C34.145 29.5283 34.6191 28.7244 35.3831 28.3008C36.1471 27.8748 37.0813 27.9006 37.8223 28.3642L57.0499 40.3809C57.7523 40.8211 58.1795 41.5909 58.1795 42.4193C58.1795 43.2477 57.7523 44.0177 57.0499 44.4576Z" fill="#DF2C65"/>
        <defs>
            <filter id="filter0_d" x="0.505371" y="0.165527" width="86.5069" height="86.5069" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                <feOffset dy="1"/>
                <feGaussianBlur stdDeviation="3"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
            </filter>
        </defs>
    </svg>
</template>

<script>
    export default {
        name: "PlayButton"
    }
</script>

<style scoped lang="scss">
    .play-button--absolute {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
