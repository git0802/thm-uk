<template>
    <input-number
        :placeHolder="'Phone number'"
        :errorMessage="'*Phone number'"
        :validationState="validationState"
        :maskCode="'(###)#### ####'"
        @validation="validationData"
    />
</template>

<script>
    export default {
        data() {
            return {
                validationState: 0,
                name: 'phone'
            }
        },
        methods: {
            validationData(value) {
                const CHAR_NUMBER = 14;
                let phoneNumber = '';
                let isValid = false;

                if (value.length > CHAR_NUMBER) {
                    phoneNumber = value.slice(0, -1);
                } else {
                    phoneNumber = value;
                }

                if (phoneNumber.length === CHAR_NUMBER) {
                    this.validationState = 1;
                    isValid = true;
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, phoneNumber);
            },
        }
    }
</script>


