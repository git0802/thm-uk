<template>
    <input-number
        :placeHolder="'Exp MM/YY'"
        :errorMessage="'*Exp MM/YY'"
        :validationState="validationState"
        :maskCode="'##/##'"
        @validation="validationData"
    />
</template>

<script>
import InputNumber from "../../InputNumber";
    export default {
        components: {InputNumber},
        data() {
            return {
                validationState: 0,
                name: 'exp'
            }
        },
        methods: {
            validationData(value) {
                const CHAR_NUMBER = 5;
                let cardExp = '';
                let isValid = false;

                if (value.length > CHAR_NUMBER) {
                    cardExp = value.slice(0, -1);
                } else {
                    cardExp = value;
                }

                if (cardExp.length === CHAR_NUMBER) {
                    const DIVIDER = '/';
                    const expValue = cardExp.split(DIVIDER);
                    const MM = expValue[0];
                    const YY = expValue[1];
                    const MIN_MM = 1;
                    const MAX_MM = 12;
                    const MIN_YY = 20;
                    const MAX_YY = 99;

                    const formatMM = this.formatValueWithLeadZero(MM);

                    if (formatMM >= MIN_MM && formatMM <= MAX_MM && YY >= MIN_YY && YY <= MAX_YY) {
                        this.validationState = 1;
                        isValid = true;
                    } else {
                        this.validationState = 2;
                        isValid = false;
                    }
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, cardExp);
            },
            formatValueWithLeadZero(value) {
                if (value[0] === '0') {
                    return value.substr(1);
                } else {
                    return value;
                }
            }
        }
    }
</script>



