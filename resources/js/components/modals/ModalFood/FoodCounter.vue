<template>
    <div class="food-counter">
        <div class="food-counter__text">Number of servings</div>
        <div class="food-counter__action m-b-5"
            @click="decrementCounter()"
        >
            <icon-increment/>
        </div>
        <div class="food-counter__value">{{ counter }}</div>
        <div class="food-counter__action"
            @click="incrementCounter()"
        >
            <icon-decrement/>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                counter: 1,
            }
        },
        methods: {
            incrementCounter() {
                if (this.counter < 100) {
                    this.counter++;
                }
            },
            decrementCounter() {
                if (this.counter > 1) {
                    this.counter--;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/mixins';

    .food-counter {
        @include base-font;

        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;

        @media screen and (max-width: 600px) {
            justify-content: flex-start;
        }

        &__text {
            margin-right: 15px;
            text-align: end;
        }

        &__value {
            background: var(--grey-light);
            border: 1px solid var(--grey);
            border-radius: 7px;
            min-width: 35px;
            text-align: center;
            margin: 0px 5px;
            padding: 2px 0px;
        }

        &__action {
            :hover {
                cursor: pointer;
            }
        }
    }

    .m-b-5 {
        margin-bottom: 5px;
    }
</style>


