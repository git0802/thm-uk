<template>
    <div class="change-phone">
        <form @submit.prevent="handleForm">
            <h3 class="mb-1 color-6 input-title">
                Phone
            </h3>

            <VuePhoneNumberInput
                :required="true"
                @update="onUpdate"
                clearable
                v-model="form.phone.value"
                no-use-browser-locale
                show-code-on-list
            />

            <button-base
                :text="'Update phone'"
                :type="'submit'"
                :disabled="loading || isValid"
                :classes="'button--purple'"
            />
            <overlay-component v-if="loading" />
        </form>
    </div>

</template>

<script>
import BaseInput from "../../../js/components/inputs/BaseInput";
import ButtonBase from "../../../js/components/buttons/ButtonBase";
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";
export default {
    name: "ChangePhone",
    components: {ButtonBase, BaseInput, VuePhoneNumberInput, OverlayComponent},
    data: function () {
        return {
            isValid: false,
            results: null,
            loading: false,
            phone_number: null,
            form: {
                phone: {
                    value: this.$store.state.auth.user.phone,
                },
            }
        }
    },
    methods: {
        onUpdate(payload) {
            this.results = payload;
            this.isValid = payload.isValid === false;
            payload.formattedNumber ? this.phone_number = payload.formattedNumber : this.phone_number = null
        },
        async handleForm(event) {
            event.preventDefault()
            this.loading = true;
            try {
                let res = await axios.post(`/api/user/settings/change-phone`, {
                    phone: this.phone_number,
                })
                this.loading = false;
                this.$store.commit('auth/userInfo', res.data.user)
                this.message = res.data.message
                this.$notify({
                    group: 'planner',
                    title: `Change phone`,
                    type: 'success',
                    text: this.message,
                });
            } catch (e) {
                this.loading = false;
            }
        },
    }
}
</script>

<style lang="scss">
    .change-phone {
        form {
            position: relative;
        }

        .country-selector.input-country-selector.has-value {
            z-index: 9;
        }

        .vue-phone-number-input {
            margin-bottom: 20px;

            .country-selector, .input-tel {
                height: 39px!important;;
                min-height: 39px!important;
            }
        }
    }
</style>
