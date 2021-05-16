<template>
    <input-text
        :placeHolder="'Calories Per Serving:'"
        :errorMessage="'*Calories Per Serving:'"
        :validationState="validationState"
        :isDisabled="isDisabled"
        :init-value="initValue"
        @validation="validationData"
    />
</template>

<script>
    import InputText from "../InputText";

    export default {
        name: "InputServingCalories",
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
                name: 'calories'
            }
        },
        methods: {
            validationData(value) {
                if (value && Number.isInteger(Number(value))) {
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

