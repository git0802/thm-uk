<template>
    <div class="select-container" @mouseenter="focused = true" @mouseleave="focused = false" :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
        <v-select
            label="title"
            :value="value"
            :clearable="false"
            :placeholder="validationState === 2 && focused === false ? '' : placeHolder"
            :options="options"
            :disabled="disabled"
            @input="$emit('input', $event); validationCallback()"
            @blur="validationCallback"
            @search:blur="validationCallback"
            :searchable="false"
        >
            <template #option="option">
                <div class="select-subscription-item">
                    <span class="text-bold title" :class="{'year': option.months == 12}">
                        <icon-off v-if="option.months == 12" class="sale-off"/>
                        {{option.title}}
                        <icon-fire v-if="option.months == 12" class="fire"/>
                    </span>
                    <span class="text padded">{{option.months}}-month</span>
                    <label class="padded">
                        <span class="text-bold">{{ phrase.currencySm }}{{option.price_sale}}</span>
                        <span class="text">/mo.</span>
                        <span v-if="option.months > 1"> - {{ phrase.currencySm }}{{option.amount}}</span>
                        <span class="red-text padded" v-if="parseInt(option.sale) > 0">{{parseInt(option.sale)}}% OFF</span>
                    </label>
                </div>
            </template>
        </v-select>
        <div class="select-container__error">
            <transition name="fade">
                <p v-show="validationState === 2 && focused === false ">
                    {{ errorMessage }}
                </p>
            </transition>
        </div>
    </div>
</template>

<script>
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';

    export default {
        components: {
            'v-select':  vSelect,
        },
        props: {
            errorMessage: {
                type: String,
            },
            options: {
                type: Array,
                required: true
            },
            disabled: {
                type: Boolean,
                required: true
            },
            placeHolder: {
                type: String,
                required: true
            },
            name: {
                type: String,
                default: ''
            },
            preselectValue: {
                type: Number,
            },
            value: {

            },
            validationState: {
                type: Number,
            },
            required: {
                type: Boolean,
                default: false,
            }
        },
        data() {
            return {
                focused: false,
            }
        },
        computed: {
            isRequired() {
                return (this.required && !this.value )
            }
        },
        created() {
        },
        methods: {
            validationCallback() {

            }
        }
    }
</script>

<style scoped lang="scss">
    .select-container {
        position: relative;
    }


    .select-container__error {
        color: red;
        font-size: 16px;
        min-height: 20px;
        font-weight: 400;
        position: absolute;
        left: 20px;
        top: 1px;
        background-color: #fae6e6;
        height: 95%;
        align-items: center;
        display: flex;
        z-index: 4;
    }
    .forgotten-input {
        background: unset;
        padding-left: 5px;
        &::placeholder {
            color: black;
        }
    }

    .place-transparent {
        &::placeholder {
            color: transparent;
        }
    }
    .select-subscription-item {
        display: flex;
        flex-direction: row;
        cursor: pointer;
        padding: 10px;
        & input{
            display: none;
            pointer-events: none;
        }
        & > svg{
            width: 26px;
            height: 26px;
            margin-left: 5px;
            background: var(--white);
            color: var(--white);
            border: 2px solid var(--purple);
            padding: 3px;
            border-radius: 2px;
            opacity: 1;
            pointer-events: none;
            transition: 0.15s ease;
        }
        & input:checked + svg{
            background: var(--purple);
            color: var(--white);
        }
        & span{
            flex-basis: 25%;
        }
        & label{
            flex-basis: 40%;
            pointer-events: none;
        }
        & .red-text{
            font-family: Gilroy,serif;
            font-size: 9px;
            font-style: normal;
            font-weight: 400;
            line-height: 12px;
            letter-spacing: 0em;
            text-align: left;
            color: var(--primary);
        }
        & .text{
            font-family: Gilroy;
            font-size: 14px;
            font-style: normal;
            font-weight: 300;
            line-height: 19px;
            letter-spacing: 0em;
            text-align: left;

        }

        & .text-bold{
            font-family: Gilroy;
            font-size: 14px;
            font-style: normal;
            font-weight: 800;
            line-height: 19px;
            letter-spacing: 0em;
            text-align: left;
            &.title{
                margin-x: 15px;
            }
        }

        & input:checked ~ .text-bold{
            color: var(--purple);
        }

        &.odd{
            background: #EDEDED;
        }
        & .year{
            display: flex;
            & .sale-off{
                margin-top: -20px;
                margin-left: -35px;
                margin-right: -20px;
            }
            & .fire{
                margin-left: 10px;
            }
        }

        & .padded {
            padding: 0px 5px;
        }
    }


</style>




