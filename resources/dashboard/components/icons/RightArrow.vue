<template>
    <svg :width="width" :height="height" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg" :style="styles">
        <path d="M5.17168 7.00023L0.22168 2.05023L1.63568 0.63623L7.99968 7.00023L1.63568 13.3642L0.22168 11.9502L5.17168 7.00023Z" :fill="fill"/>
    </svg>
</template>

<script>
    export default {
        // props: ['width', 'height', 'fill', 'style'],
        props: {
            width: {
              type: Number,
              default: 8,
            },
            height: {
                type: Number,
                default: 14,
            },
            fill: {
                type: String,
                default: 'white',
            },
            styles: {
                type: Object,
                default: () => {},
            },

        },
        name: "RightArrow"
    }
</script>

<style scoped>

</style>
