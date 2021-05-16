<template>
    <input-text
        :placeHolder="'Serving size'"
        :errorMessage="'*Serving size'"
        :validationState="validationState"
        :isDisabled="isDisabled"
        :init-value="initValue"
        @validation="validationData"
    />
</template>

<script>
    import InputText from "../InputText";

    export default {
        name: "InputServingSize",
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
                name: 'servingSize'
            }
        },
        methods: {
            validationData(value) {
                const MIN_CHAR_NUMBER = 1;
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

