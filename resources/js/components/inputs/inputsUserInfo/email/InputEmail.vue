<template>
    <input-text
        :placeHolder="'Email'"
        :errorMessage="'*Email'"
        :validationState="validationState"
        @validation="validationData"
    />
</template>
<script>
    export default {
        data() {
            return {
                validationState: 0,
                name: 'email'
            }
        },
        methods: {
            validationData(value) {
                const pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                const isValid = pattern.test(String(value).toLowerCase());

                if (isValid) {
                    this.validationState = 1;
                } else {
                    this.validationState = 2;
                }

                this.$emit('validation', this.name, isValid, value);
            },
        }
    }
</script>



