<template>
    <input-number
        :placeHolder="'Card number'"
        :errorMessage="'*Card number'"
        :validationState="validationState"
        :maskCode="'#### #### #### ####'"
        @validation="validationData"
    />
</template>

<script>
    export default {
        data() {
            return {
                validationState: 0,
                name: 'number'
            }
        },
        methods: {
            validationData(value) {
                const CHAR_NUMBER = 19;
                let cardNumber = '';
                let isValid = false;

                if (value.length > CHAR_NUMBER) {
                    cardNumber = value.slice(0, -1);
                } else {
                    cardNumber = value;
                }

                if (cardNumber.length === CHAR_NUMBER) {
                    this.validationState = 1;
                    isValid = true;
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, cardNumber);
            },
        }
    }
</script>



