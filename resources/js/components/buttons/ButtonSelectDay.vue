<template>
    <div class="button-select-day" :class="beforeClass"
         @click="$emit('select', name)">
        <div :class="{ 'is-selected': this.selectedDay == this.name }">
            {{ name | nameTrim(windowWidth) }}
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                windowWidth: window.outerWidth,
            }
        },
        props: {
            name: {
                type: String,
                required: true,
                default: 'Day of week'
            },
            selectedDay: {
                type: String,
                required: true,
                default: ''
            },
            dkey: {}
        },
        computed: {
            validationState() {
                return this.$store.state.planner.validation.days[this.dkey]
            },

            beforeClass() {
                if(this.validationState.is_valid) {
                    return 'ok'
                } else if(Math.sign(this.validationState.diff) === 1 ) {
                    return 'overdo'
                } else if(Math.sign(this.validationState.diff) === -1 || this.validationState.diff === undefined) {
                    return 'lack'
                }
            }

        },
        mounted: function() {
            window.addEventListener('resize', () => {
                this.windowWidth = window.outerWidth
            })
        },
        filters: {
            nameTrim: function (value, width) {
                if (width <= 991) {
                    value = value.substr(0, 3)
                }
                return value
            }
        }
    }
</script>

<style scoped lang="scss">
    .button-select-day {
        position: relative;
        font-size: 14px;
        color: #929292;
        cursor: pointer;
        -moz-user-select: none;
        -ms-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        div {
            padding: 10px;
            transition: 0.35s ease-in-out;
            @media screen and (max-width: 350px) {
                padding: 10px 7px;
            }
            &.is-selected {
                color: #000;
            }
        }

        &:after {
            content: "";
            width: 100%;
            display: block;
            height: 5px;
            background: #929292;
            position: absolute;
            bottom: 0;
            left: 0;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            opacity: 0;
            pointer-events: none;
            transition: 0.2s linear;
        }

        &:hover:after {
            cursor: pointer;
            opacity: 1;
        }
    }
</style>


