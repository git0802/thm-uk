<template>
    <div class="toggle">
        <input :checked="value" :id="`switch-${uid}`" @click.prevent="$emit('change', $event.target.checked)" type="checkbox" class="switch">
        <label :for="`switch-${uid}`">Eaten</label>
    </div>
</template>

<script>
    export default {
        name: "input-switch",
        props: {
            value: {},
            checked: {}
        },
        data: function () {
            return {
                uid: null
            }
        },
        mounted() {
            this.uid = this._uid
        },
        computed: {
            // checked: {
            //     get() {
            //         return this.isEaten;
            //     },
            //     set(val) {
            //         console.log(val)
            //         this.$emit('change', val)
            //     }
            // }
        }
    }
</script>

<style scoped lang="scss">
    input[type='checkbox'],
    input[type='radio'] {
        --active: #E4D4F7;
        --active-inner: var(--purple);
        --focus: 2px rgba(39, 94, 254, .3);
        --border: #929292;
        --border-hover: #275EFE;
        --background: #DFDFDF;
        --disabled: #929292;
        --disabled-inner: var(--purple);
        -webkit-appearance: none;
        -moz-appearance: none;
        height: 21px;
        outline: none;
        display: inline-block;
        vertical-align: top;
        position: relative;
        margin: 0;
        cursor: pointer;
        border: 1px solid transparent;
        background: var(--b, var(--background));
        transition: background .3s, border-color .3s, box-shadow .2s;
        &:after {
            content: '';
            display: block;
            left: 0;
            top: 0;
            position: absolute;
            transition: transform var(--d-t, .3s) var(--d-t-e, ease), opacity var(--d-o, .2s);
        }
        &:checked {
            --b: var(--active);
            --bc: var(--active);
            --d-o: .3s;
            --d-t: .6s;
            --d-t-e: cubic-bezier(.2, .85, .32, 1.2);
            & + label {
                color: var(--purple);
            }
        }
        &:disabled {
            --b: var(--disabled);
            cursor: not-allowed;
            opacity: .9;
            &:checked {
                --b: var(--disabled-inner);
                --bc: var(--border);
            }
            & + label {
                cursor: not-allowed;
            }
        }
        &:hover {
            &:not(:checked) {
                &:not(:disabled) {
                    --bc: var(--border-hover);
                }
            }
        }
        /*&:focus {
            box-shadow: 0 0 0 var(--focus);
        }*/
        &:not(.switch) {
            width: 21px;
            &:after {
                opacity: var(--o, 0);
            }
            &:checked {
                --o: 1;
            }
        }
        & + label {
            font-size: 14px;
            line-height: 21px;
            display: inline-block;
            vertical-align: top;
            cursor: pointer;
            margin-left: 4px;
            font-weight: 700;
            color: #929292;
        }
    }
    input[type='checkbox'] {
        &:not(.switch) {
            border-radius: 7px;
            &:after {
                width: 5px;
                height: 9px;
                border: 2px solid var(--active-inner);
                border-top: 0;
                border-left: 0;
                left: 7px;
                top: 4px;
                transform: rotate(var(--r, 20deg));
            }
            &:checked {
                --r: 43deg;
            }
        }
        &.switch {
            width: 36px;
            border-radius: 11px;
            &:after {
                left: 4px;
                top: 4px;
                border-radius: 50%;
                width: 12px;
                height: 12px;
                background: var(--ab, var(--border));
                transform: translateX(var(--x, 0));
            }
            &:checked {
                --ab: var(--active-inner);
                --x: 15px;
            }
            &:disabled {
                &:not(:checked) {
                    &:after {
                        opacity: .6;
                    }
                }
            }
        }
    }
    input[type='radio'] {
        border-radius: 50%;
        &:after {
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background: var(--active-inner);
            opacity: 0;
            transform: scale(var(--s, .7));
        }
        &:checked {
            --s: .5;
        }
    }

</style>
