<template>
    <input-number
        :placeHolder="'CVC'"
        :errorMessage="'*CVC'"
        :validationState="validationState"
        :maskCode="'###'"
        @validation="validationData"
    />
</template>

<script>
    export default {
        data() {
            return {
                validationState: 0,
                name: 'cvc'
            }
        },
        methods: {
            validationData(value) {
                const CHAR_NUMBER = 3;
                let cvcNumber = '';
                let isValid = false;

                if (value.length > CHAR_NUMBER) {
                    cvcNumber = value.slice(0, -1);
                } else {
                    cvcNumber = value;
                }

                if (cvcNumber.length === CHAR_NUMBER) {
                    this.validationState = 1;
                    isValid = true;
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, cvcNumber);
            },
        }
    }
</script>



