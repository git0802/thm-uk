<template>
    <input-text
        :placeHolder="'First name'"
        :errorMessage="'*First name'"
        :validationState="validationState"
        @validation="validationData"
    />
</template>

<script>
    export default {
        data() {
            return {
                validationState: 0,
                name: 'name'
            }
        },
        methods: {
            validationData(value) {
                const MIN_CHAR_NUMBER = 2;
                const MAX_CHAR_NUMBER = 24;
                let isValid = false;

                if (value.length >= MIN_CHAR_NUMBER && value.length <= MAX_CHAR_NUMBER) {
                    this.validationState = 1;
                    isValid = true;
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, value);
            }
        }
    }
</script>



