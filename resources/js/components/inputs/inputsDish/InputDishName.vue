<template>
    <input-text
        :placeHolder="'Name of dish'"
        :errorMessage="'*Name of dish'"
        :validationState="validationState"
        @validation="validationData"
    />
</template>

<script>
    import InputText from "../InputText";

    export default {
        name: "InputDishName",
        components: {
            InputText
        },
        data() {
            return {
                validationState: 0,
                isValid: false,
                name: 'dishName'
            }
        },
        methods: {
            validationData(value) {
                const MIN_CHAR_NUMBER = 2;
                const MAX_CHAR_NUMBER = 128;

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

