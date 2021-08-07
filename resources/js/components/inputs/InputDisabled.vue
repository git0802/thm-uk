<template>
    <div class="input-container">
        <div class="input-container__data" :class="{ 'correct': value }">
            <input class="input-container__entry"
                type="tel"
                disabled="disabled"
                :placeholder="placeHolder"
                v-model="value"
            />
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            placeHolder: {
                type: String,
                required: true,
                default: 'Placeholder'
            },
            value: {
                type: String,
                required: true,
                default: ''
            }
        },
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/_mixins.scss';
    input:disabled {
        // -webkit-text-fill-color:var(--black);
        -webkit-opacity:1;
    }
    .input-container {
        display: flex;
        flex-direction: column;
        @include base-font;

        &__data {
            position: relative;
            background: var(--grey-light);
            padding: 9px 45px 8px 20px;
            border-radius: 7px;
            border: 1px solid var(--grey);

            &.correct {
                transition: 400ms;
                border: 1px solid var(--green);
                background: var(--green-light);
            }
        }

        &__entry {
            -webkit-text-fill-color: inherit;
            width: 100%;
            background: transparent;
        }
    }
</style>


