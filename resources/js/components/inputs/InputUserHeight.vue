<template>
    <div v-if="this.$useMetricSystem">
        <base-input
            v-bind:class="[className]"
            v-model="form.height.value"
            :name="'height'"
            :placeHolder="'Height (cm)'"
            :errorMessage="form.height.validation.message"
            :validation-callback="form.height.validation.callback"
            :validation-state="form.height.validation.validated"
            :required="form.height.required"
        />
    </div>
    <div class="heightFieldDouble" v-else>
        <base-input
            v-bind:class="[className, 'heightFieldFt m-r-15 mr-10']"
            v-model="form.heightFt.value"
            @focus="onFocusCallback"
            :name="'heightFt'"
            :placeHolder="'Height (ft)'"
            :errorMessage="form.heightFt.validation.message"
            :validation-callback="validationCallback"
            :validation-state="form.heightFt.validation.validated"
        />
        <base-input
            v-bind:class="[className, 'heightFieldIn']"
            v-model="form.heightIn.value"
            @focus="onFocusCallback"
            :name="'heightIn'"
            :placeHolder="'Height (in)'"
            :errorMessage="form.heightIn.validation.message"
            :validation-callback="validationCallback"
            :validation-state="form.heightIn.validation.validated"
        />
    </div>
</template>

<script>
    import BaseInput from "../../../js/components/inputs/BaseInput";

    export default {
        name: "InputUserHeight",
        components: {
            BaseInput
        },
        props: {
            className: {
                type: String,
                required: false,
            },
            initValue: {
                type: Number,
                required: true,
                default: ''
            },
        },
        data() {
            let params = {
                form: {
                    height: {
                        value: this.initValue,
                        validation: {
                            validated: 0,
                        }
                    }
                }
            };

            if (this.$useMetricSystem) {
                params.form.height = {
                    required: true,
                    value: this.initValue,
                    validation: {
                        rules: {
                            min: this.$phrase.minHeight,
                            max: this.$phrase.maxHeight,
                        },
                        message: '',
                        validated: 0,
                        callback: (event) => {
                            if (this.form.height.value.length < 1) {
                                this.form.height.validation.validated = 2;
                                this.form.height.validation.message = '*Enter your height'
                            } else if (this.form.height.validation.rules.min > this.form.height.value || this.form.height.validation.rules.max < this.form.height.value) {
                                this.form.height.validation.validated = 2;
                                this.form.height.validation.message = '*Min ' + this.form.height.validation.rules.min + ' -  max ' + this.form.height.validation.rules.max
                            } else {
                                this.form.height.validation.validated = 1;
                            }

                            this.validated();
                        }
                    },
                };
            } else {
                const inchesHeight = this.convertCMtoInches(this.initValue);

                params.form.heightFt = {
                    value: inchesHeight.ft ? (inchesHeight.ft + '\'') : '',
                    validation: {
                        message: '',
                        validated: 0
                    }
                };

                params.form.heightIn = {
                    value: inchesHeight.inRest ? (inchesHeight.inRest + '"') : '',
                    validation: {
                        rules: {
                            min: this.$phrase.minHeight,
                            max: this.$phrase.maxHeight,
                        },
                        message: '',
                        validated: 0
                    },
                };
            }

            return params;
        },
        created() {

        },
        methods: {
            validated() {
                this.form.height.value = this.$useMetricSystem ? this.form.height.value : this.convertToCentimeters(this.form.heightFt.value, this.form.heightIn.value);

                this.$emit('validation', this.form.height.validation.validated, this.form.height.value);
            },
            onFocusCallback(event) {
                const val = this.convertToFloat(event.target.value);

                event.target.value = val ? val : '';
            },
            validationCallback(event) {
                const heightFtVal = this.convertToFloat(this.form.heightFt.value);
                const heightInVal = this.convertToFloat(this.form.heightIn.value);

                const baseHeight = this.convertCMtoInches(this.convertToCentimeters(heightFtVal, heightInVal));

                let isError = false;

                if (heightFtVal < 1 && heightInVal < 1) {
                    isError = '*Enter your height';
                } else if (baseHeight.in < this.form.heightIn.validation.rules.min) {
                    isError = '*It seems quite short!';
                } else if (baseHeight.in > this.form.heightIn.validation.rules.max) {
                    isError = '*It seems quite tall!';
                }

                if (isError) {
                    this.form.heightFt.validation.message = isError;

                    this.form.heightIn.validation.validated = 2;
                    this.form.heightFt.validation.validated = 2;
                    this.form.height.validation.validated = 2;
                } else {
                    this.form.heightIn.validation.validated = 1;
                    this.form.heightFt.validation.validated = 1;
                    this.form.height.validation.validated = 1;
                }

                const val = this.convertToFloat(event.target.value);
                event.target.value = val ? (val + (event.target.name == 'heightIn' ? '"' : '\'')) : '';

                this.validated();
            },
        },
        computed: {}
    }
</script>

<style scoped>
    .heightFieldDouble {
        display: flex;
        flex-direction: row;
    }

    .heightFieldIn {
        flex-basis: 50%;
    }

    .heightFieldFt {
        flex-basis: 50%;
    }
</style>
