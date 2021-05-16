<template>
    <input-text
        :placeHolder="'Confirm email'"
        :errorMessage="'*Confirm email'"
        :validationState="validationState"
        @validation="validationData"
    />
</template>

<script>
    export default {
        props: {
            email: {
                type: String,
                required: true,
                default: '',
            }
        },
        data() {
            return {
                validationState: 0,
                name: 'confirm_email'
            }
        },
        methods: {
            validationData(value) {
                let isValid = false;

                if (value && value === this.email) {
                    this.validationState = 1;
                    isValid = true;
                } else {
                    this.validationState = 2;
                    isValid = false;
                }

                this.$emit('validation', this.name, isValid, value);
            },
        }
    }
</script>



