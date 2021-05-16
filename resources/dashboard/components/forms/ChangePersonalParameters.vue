<template>
    <div>
        <form  @submit.prevent="handleForm">
            <h3 class="mb-1 color-6 input-title">
                Weight
            </h3>
            <base-input
                v-model="form.weight.value"
                :name="'weight'"
                :placeHolder="'Weight'"
                :errorMessage="form.weight.validation.message"
                :validation-callback="form.weight.validation.callback"
                :validation-state="form.weight.validation.validated"
                :required="form.weight.required"
            />
            <h3 class="mb-1 color-6 input-title">
                Height
            </h3>
            <InputUserHeight :initValue="form.height.value" @validation="validateHeight"></InputUserHeight>

            <h3 class="mb-1 color-6 input-title">
                Age
            </h3>
            <base-input
                v-model="form.age.value"
                :name="'age'"
                  :placeHolder="'Age'"
                :errorMessage="form.age.validation.message"
                :validation-callback="form.age.validation.callback"
                :validation-state="form.age.validation.validated"
                :required="form.age.required"
            />
            <h3 class="mb-1 color-6 input-title">
                Sex
            </h3>
            <base-select
                :options="form.gender.options"
                v-model="form.gender.value"
                :disabled="false"
                :name="'gender'"
                :placeHolder="'Sex'"
                :errorMessage="form.gender.validation.message"
                :validation-callback="form.gender.validation.callback"
                :validation-state="form.gender.validation.validated"
                :required="form.gender.required"
            />

            <button-base
                :text="'Update personal parameters'"
                :type="'submit'"
                :disabled="loading || !validationForms()"
                :classes="'button--purple'"
            />
            <overlay-component v-if="loading" />

        </form>

    </div>

</template>

<script>
import BaseInput from "../../../js/components/inputs/BaseInput";
import BaseSelect from "../../../js/components/inputs/BaseSelect";
import ButtonBase from "../../../js/components/buttons/ButtonBase";
import OverlayComponent from "../../../js/components/overlay/OverlayComponent";
import InputUserHeight from '../../../js/components/inputs/InputUserHeight';

export default {
    name: "ChangePersonalParameters",
    components: {ButtonBase, BaseInput, BaseSelect, OverlayComponent, InputUserHeight},
    data: function () {
        return {
            loading: false,
            message: '',
            form: {
                weight: {
                    required: true,
                    value: this.$store.state.auth.user.weight,
                    validation: {
                        rules: {
                            min: this.$phrase.minWeight,
                            max: this.$phrase.maxWeight,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.weight.value.length < 1) {
                                this.form.weight.validation.validated = 2;
                                this.form.weight.validation.message = '*Enter your weight'
                            } else if (this.form.weight.validation.rules.min > this.form.weight.value || this.form.weight.validation.rules.max < this.form.weight.value) {
                                this.form.weight.validation.validated = 2;
                                this.form.weight.validation.message = `*Min ${this.form.weight.validation.rules.min} -  max ${this.form.weight.validation.rules.max}`
                            } else {
                                this.form.weight.validation.validated = 1;
                            }
                        }
                    },
                },
                height: {
                    required: true,
                    value: this.$store.state.auth.user.height,
                    validation: {
                        rules: {
                            min: 50,
                            max: 255,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.height.value.length < 1) {
                                this.form.height.validation.validated = 2;
                                this.form.height.validation.message = '*Enter your height'
                            } else if (this.form.height.validation.rules.min > this.form.height.value || this.form.height.validation.rules.max < this.form.height.value) {
                                this.form.height.validation.validated = 2;
                                this.form.height.validation.message = '*Min 50 -  max 255'

                            } else {
                                this.form.height.validation.validated = 1;
                            }
                        }
                    },
                },
                age: {
                    required: true,
                    value: this.$store.state.auth.user.age,
                    validation: {
                        rules: {
                            min: 12,
                            max: 120,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.age.value.length < 1) {
                                this.form.age.validation.validated = 2;
                                this.form.age.validation.message = '*Enter your age'
                            } else if (this.form.age.validation.rules.min > this.form.age.value || this.form.age.validation.rules.max < this.form.age.value) {
                                this.form.age.validation.validated = 2;
                                this.form.age.validation.message = '*Min 12 -  max 120'

                            } else {
                                this.form.age.validation.validated = 1;
                            }
                        }
                    },
                },
                gender: {
                    required: true,
                    value: this.$store.state.auth.user.gender.charAt(0).toUpperCase() + this.$store.state.auth.user.gender.slice(1),
                    options: [
                        'Male',
                        'Female',
                    ],
                    validation: {
                        rules: {
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if(this.form.gender.value) {
                                this.form.gender.validation.validated = 1;
                            } else {
                                this.form.gender.validation.message = 'Please select issue!';
                                this.form.gender.validation.validated = 2;

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
        validateHeight(validated, value) {
            this.form.height.validation.validated = validated == 1 ? true : false;
            this.form.height.value = value;
        },
        validationForms() {
            let form = this.form
            return form.age.validation.validated == 1 || form.height.validation.validated == 1 || form.weight.validation.validated == 1 || form.gender.validation.validated == 1;
        },
        clearForm() {
            Object.keys(this.form).forEach(f => {
                this.form[f].value = ''
                this.form[f].validation.validated = 0;
            });
        },
        async handleForm(event) {
            event.preventDefault()
            this.loading = true;
            try {
                let res = await axios.post(`/api/user/settings/change-metrics`, {
                    weight: this.form.weight.value,
                    height: this.form.height.value,
                    age: this.form.age.value,
                    gender: this.form.gender.value.toLowerCase(),
                })
                this.loading = false;
                this.$store.commit('auth/user', res.data.user)
                this.message = res.data.message
                this.$notify({
                    group: 'planner',
                    title: `Change personal parameters`,
                    type: 'success',
                    text: this.message,
                });
            } catch (e) {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>
form {
    position: relative;
}
</style>
