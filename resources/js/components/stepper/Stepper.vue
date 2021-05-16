<template>
    <div class="stepper-container">
        <one-step v-for="step in stepperData" :key="step.content"
            :index="step.index"
            :content="step.content"
            :isLast="step.isLast"
            :isCurrentStep="step.isCurrentStep"
            :isChecked="step.isChecked"
            :currentStep="computedCurrentStep"
            @choose="chooseStep"
        />
    </div>
</template>

<script>
    export default {
        props: {
            currentStep: {
                type: Number,
                required: true
            },
            stepperContent: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                stepperData: [],
            }
        },
        computed: {
            computedCurrentStep() {
                this.changeCurrentStep();
                return this.currentStep;
            },
        },
        created() {
            this.changeCurrentStep();
        },
        methods: {
            changeCurrentStep() {
                this.stepperData = [];
                this.stepperData = [ ...this.stepperContent ];

                this.stepperData.forEach((item, index, array) => {
                    item.index = index;
                    item.isCurrentStep = (this.currentStep === index);
                    item.isChecked = (this.currentStep > index);
                    item.isLast = (index === array.length - 1);
                });
            },
            chooseStep(index) {
                this.$emit('choose', index);
            }
        }
    }
</script>

<style scoped lang="scss">
    .stepper-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
</style>
