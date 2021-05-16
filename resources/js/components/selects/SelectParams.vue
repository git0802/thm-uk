<template>
    <div class="select-container" :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
        <v-select
            @search:blur="handleBlur"
            v-model="selectedValue"
            :clearable="false"
            :placeholder="selectPlaceHolder"
            :options="options"
            :disabled="disabled"
            :searchable="searchable"
        />
    </div>
</template>

<script>
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';

    export default {
        components: {
            'v-select':  vSelect,
        },
        props: {
            errorMessage: {
                type: String,
            },
            options: {
                type: Array,
                required: true
            },
            disabled: {
                type: Boolean,
                required: true
            },
            placeHolder: {
                type: String,
                required: true
            },
            name: {
                type: String,
                default: ''
            },
            preselectValue: {
                type: String,
                default: ''
            },
            isLock: {
                type: Boolean,
                default: false
            },
            searchable: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                selectedValue: '',
                isValid: false,
                validationState: 0,
                selectPlaceHolder: '',
            }
        },
        computed: {
            user(){
                return this.$store.getters['auth/user']
            }
        },
        created() {
            this.selectPlaceHolder = this.placeHolder;

            this.setPreselectValue(this.preselectValue);
        },
        mounted() {
            if(window.location.pathname === '/weekly-plan'
                || window.location.pathname === '/meal-planner/setup'
                || window.location.pathname === '/meal-planner/planner/'
                || window.location.pathname === '/store'){
                this.setOldGoal()
            }
        },
        methods: {
            setOldGoal(){
                let goalList = this.$phrase.goalList;
                const index = this.$phrase.goalListValues.findIndex((item) => {
                    return item == this.user.goal;
                });
                this.setPreselectValue(goalList[index])
            },
            handleBlur() {
                this.isValid = this.validationData();

                if (this.isValid) {
                    this.validationState = 1;
                } else {
                    this.validationState = 2;
                    this.selectPlaceHolder = this.errorMessage;
                }

                if (this.isLock) {
                    this.selectedValue = this.preselectValue;
                }

                this.$emit('validation', this.name, this.isValid, this.selectedValue);
            },
            validationData() {
                if (this.selectedValue) {
                    return true;
                }

                return false;
            },
            setPreselectValue(value) {
                if (value) {
                    this.selectedValue = value;
                    this.handleBlur();
                }
            }
        }
    }
</script>



