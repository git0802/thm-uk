<template>
    <div class="gender-container">
        <div @click="setInitValue('Male')" class="flex-1 gender-container__item" :class="{'gender-container__selected' : inputData == 'Male'}">
            <span>Male</span>
        </div>
        <div @click="setInitValue('Female')" class="flex-1 gender-container__item" :class="{'gender-container__selected' : inputData == 'Female'}">
            <span>Female</span>
        </div>
    </div>
</template>

<script>

    export default {
        name: "GenderSwitch",
        props: {
            initValue: {
                type: String,
                required: false,
                default: 'Female'
            }
        },
        data() {
            return {
                inputData: '',
                inputPlaceHolder: ''
            }
        },
        created() {
            this.inputPlaceHolder = this.placeHolder;

            this.setInitValue(this.initValue);
        },
        methods: {
            updateValue() {
                this.$emit('validation', 'gender', true, this.inputData);
            },
            setInitValue(value) {
                if (value) {
                    this.inputData = value;
                    this.updateValue();
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';

    .gender-container {
        display: flex;
        border-radius: 7px;
        border: 1px solid #B6B6B6;
        position: relative;
        background: #F9F9F9;
        flex-direction: row;
        font-family: Lato;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 17px;
        align-items: stretch;
        text-align: center;

        @media screen and (max-width: 600px) {
            font-size: 12px !important;
            line-height: 14px !important;
        }

        &__item {
            text-align: center;
            padding: 10px 20px 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        &__selected {
            color: var(--white);
            position: relative;
            background: linear-gradient(94.02deg, #FF369A 0.94%, #FF1D8D 92.78%);
            border-radius: 7px;
        }

        &__entry {
            @include base-font;

            width: 100%;
            background: transparent;
            color: var(--black);

            .error & {
                color: var(--red);
            }

            &::placeholder {
                color: var(--black);
                /*Recommended write this for FireFox*/
                opacity: 1;

                .error & {
                    color: var(--red);
                }
            }
        }

        :focus {
            outline: none;
        }
    }
</style>


