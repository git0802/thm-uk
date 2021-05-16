<template>
    <input-text
        :placeHolder="'Name of food item'"
        :errorMessage="'*Name of food item'"
        :validationState="validationState"
        :is-disabled="isDisabled"
        :init-value="initValue"
        @validation="validationData"
    />
</template>

<script>
    import InputText from "../InputText";

    export default {
        name: "InputFoodName",
        components: {
            InputText
        },
        props: {
            isDisabled: {
                type: Boolean,
                default: false
            },
            initValue: {
                type: String,
                required: false,
                default: ''
            }
        },
        data() {
            return {
                validationState: 0,
                isValid: false,
                name: 'name'
            }
        },
        methods: {
            validationData(value) {
                const MIN_CHAR_NUMBER = 2;
                const MAX_CHAR_NUMBER = 255;

                if (value.length >= MIN_CHAR_NUMBER && value.length <= MAX_CHAR_NUMBER) {
                    this.validationState = 1;
                    this.isValid = true;
                } else {
                    this.validationState = 2;
                    this.isValid = false;
                }

                this.$emit('validation', this.name, this.isValid, value);
            }
        }
    }
</script>

