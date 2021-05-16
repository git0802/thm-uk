<template>
    <div class="generate-plan">
        <div class="generate-plan__next">
            <button-view-plan
                @click="nextStep"
            />
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            nextStep() {
                this.$emit('next');
            }
        }
    }
</script>

<style scoped lang="scss">
    .generate-plan {
        display: flex;
        flex-direction: column;
        max-width: 980px;

        &__next {
            display: flex;
            flex-direction: row;

            @media screen and (max-width: 600px) {
                flex-direction: column;
            }
        }
    }
</style>
