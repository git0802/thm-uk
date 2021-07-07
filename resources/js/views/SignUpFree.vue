<template>
    <app-layout>
        <div class="steps-container">
            <stepper
                class="steps-container__stepper"
                :currentStep="computedCurrentStep"
                :stepperContent="stepperContent"
                @choose="chooseStep"
            />
            <div class="steps-container__content">
                <div class="steps-container__steps">
                    <app-caption
                        class="steps-container__current"
                        :title="currentStepText"
                        :description="currentStepDescription"
                    />
                    <enter-details
                        v-if="currentStep === 0"
                        @next="nextStep"
                    />
                    <calculate-calorie
                        v-if="currentStep === 1"
                        @next="nextStep"
                    />
                    <generate-plan
                        v-if="currentStep === 2"
                        @next="nextStep"
                    />
                    <create-account
                        v-if="currentStep === 3"
                    />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    export default {
        data() {
            return {
                currentStep: 0,
                currentStepText: 'Step 01',
                currentStepDescription: 'Enter your details',
                stepperContent: [
                    {
                        content: '01',
                    },
                    {
                        content: '02',
                    },
                    {
                        content: '03',
                    },
                    {
                        content: '04',
                    },
                ]
            }
        },
        computed: {
            computedCurrentStep() {
                switch (this.currentStep) {
                    case 0:
                        this.currentStepText = 'Step 01';
                        this.currentStepDescription = 'Enter your details';
                        break;
                    case 1:
                        this.currentStepText = 'Step 02';
                        this.currentStepDescription = 'Calculate your daily calorie goal';
                        break;
                    case 2:
                        this.currentStepText = 'Step 03';
                        this.currentStepDescription = 'We’re generating your weekly plan!';
                        break;
                    case 3:
                        this.currentStepText = 'Step 04';
                        this.currentStepDescription = 'Create a FREE account';
                        break;
                }

                return this.currentStep;
            },
        },
        methods: {
            nextStep() {
                this.currentStep++;
            },
            chooseStep(index) {
                this.currentStep = index;
            }
        }
    }
</script>

<style scoped lang="scss">
    .steps-container {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 99px);
        background: var(--white);
        padding-top: 76px;
        @media screen and (max-width: 980px) {
            min-height: calc(100vh - 86px);
        }


        &__stepper {
            padding: 29px 23px;

            @media screen and (max-width: 600px) {
                padding-top: 2px;
                padding-bottom: 37px;
            }
        }

        &__content {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 21px 25px;
        }

        &__steps {
            max-width: 980px;
            width: 100%;
        }

        &__current {
            margin-bottom: 46px;

            @media screen and (max-width: 600px) {
                margin-bottom: 36px;
            }
        }
    }
</style>
