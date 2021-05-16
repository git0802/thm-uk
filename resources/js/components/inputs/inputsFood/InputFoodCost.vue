<template>
    <input-text
        :placeHolder="'Avg. Cost of item:'"
        :errorMessage="'*Avg. Cost of item:'"
        :validationState="validationState"
        :isDisabled="isDisabled"
        :init-value="initValue"
        @validation="validationData"
    />
</template>

<script>
    import InputText from "../InputText";

    export default {
        name: "InputFoodCost",
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
                name: 'cost'
            }
        },
        methods: {
            validationData(value) {
                if (Number(value)) {
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

