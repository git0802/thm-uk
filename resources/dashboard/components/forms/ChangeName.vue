<template>
    <div>
        <form  @submit.prevent="handleForm">
            <h3 class="mb-1 color-6 input-title">
                First name
            </h3>
            <base-input
                v-model="form.name.value"
                :name="'name'"
                :placeHolder="'First name'"
                :errorMessage="form.name.validation.message"
                :validation-callback="form.name.validation.callback"
                :validation-state="form.name.validation.validated"
                :required="form.name.required"
                :min="form.name.validation.rules.min"
                :max="form.name.validation.rules.max"
            />
            <h3 class="mb-1 color-6 input-title">
                Last name
            </h3>
            <base-input
                v-model="form.lastName.value"
                :name="'lastname'"
                :placeHolder="'Last name'"
                :errorMessage="form.lastName.validation.message"
                :validation-callback="form.lastName.validation.callback"
                :validation-state="form.lastName.validation.validated"
                :required="form.lastName.required"
                :min="form.lastName.validation.rules.min"
                :max="form.lastName.validation.rules.max"
            />


            <button-base
                :text="'Update name'"
                :type="'submit'"
                :disabled="loading || !validationForm()"
                :classes="'button--purple'"
            />
            <overlay-component v-if="loading" />

        </form>

    </div>

</template>

<script>
import BaseInput from "../../../js/components/inputs/BaseInput";
import ButtonBase from "../../../js/components/buttons/ButtonBase";
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";

export default {
    name: "ChangeName",
    components: {ButtonBase, BaseInput, OverlayComponent},
    data: function () {
        return {
            loading: false,
            message: '',
            form: {
                name: {
                    required: true,
                    value: this.$store.state.auth.user.name,
                    validation: {
                        rules: {
                            min: 2,
                            max: 120,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.name.value.length < 1) {
                                this.form.name.validation.validated = 2;
                                this.form.name.validation.message = '*Enter your name'
                            } else if (this.form.name.validation.rules.min > this.form.name.value.length || this.form.name.validation.rules.max < this.form.name.value.length) {
                                this.form.name.validation.validated = 2;
                                this.form.name.validation.message = '*Min 2 -  max 120 characters'
                            } else {
                                this.form.name.validation.validated = 1;
                            }
                        }
                    },
                },
                lastName: {
                    required: true,
                    value: this.$store.state.auth.user.last_name,
                    validation: {
                        rules: {
                            min: 2,
                            max: 120,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.lastName.value.length < 1) {
                                this.form.lastName.validation.validated = 2;
                                this.form.lastName.validation.message = '*Enter your lastname'
                            } else if (this.form.lastName.validation.rules.min > this.form.lastName.value.length || this.form.lastName.validation.rules.max < this.form.lastName.value.length) {
                                this.form.lastName.validation.validated = 2;
                                this.form.lastName.validation.message = '*Min 2 -  max 120 characters'

                            } else {
                                this.form.lastName.validation.validated = 1;
                            }
                        }
                    },
                },
            }
        }

    },
    mounted() {
    },
    methods: {
        validationForm() {
            return this.form.name.validation.validated == 1 || this.form.lastName.validation.validated == 1;
        },
        async handleForm(event) {
            event.preventDefault()
            this.loading = true;
            try {
                let res = await axios.post(`/api/user/settings/change-name`, {
                    name: this.form.name.value,
                    last_name: this.form.lastName.value,
                })
                this.loading = false;
                this.$store.commit('auth/userInfo', res.data.user)
                this.message = res.data.message
                this.$notify({
                    group: 'planner',
                    title: `Change Name`,
                    type: 'success',
                    text: this.message,
                });
                this.clearForm();
            } catch (e) {
                this.loading = false;
            }
        },
    }
}
</script>

<style scoped>
form {
    position: relative;
}
</style>
