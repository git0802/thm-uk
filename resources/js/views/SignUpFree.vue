<template>
    <app-layout>
        <div class="steps-container">
            <div class="steps-container__content">
                <div class="steps-container__steps">
                    <app-caption
                        class="steps-container__current"
                        :title="currentStepText"
                        :description="currentStepDescription"
                    />
                    <create-account
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
                currentStepText: 'Signup',
                currentStepDescription: 'Create a FREE account'
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
