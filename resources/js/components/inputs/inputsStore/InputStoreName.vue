<template>
    <div class="store-name-wrapper">
        <input-text
            :placeHolder="'Store name'"
            :errorMessage="'*Store name'"
            :validationState="validationState"
            @validation="validationData"
        />
    </div>
</template>

<script>
    import InputText from "../InputText";

    export default {
        name: "InputStoreName",
        components: {
            InputText
        },
        data() {
            return {
                validationState: 0,
                isValid: false,
                name: 'storeName'
            }
        },
        methods: {
            validationData(value) {
                const MIN_CHAR_NUMBER = 1;
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

<style scoped lang="scss">
    .store-name-wrapper {
        max-width: 480px;
    }
</style>

