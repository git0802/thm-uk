<template>
    <div class="select-container" @mouseenter="focused = true" @mouseleave="focused = false" :class="{ 'error' : validationState === 2, 'correct': validationState === 1 }">
        <v-select
            :value="value"
            :clearable="false"
            :placeholder="validationState === 2 && focused === false ? '' : placeHolder"
            :options="options"
            :disabled="disabled"
            @input="$emit('input', $event); validationCallback()"
            @blur="validationCallback"
            @search:blur="validationCallback"
        >
            <template #search="{attributes, events}">
                <input
                    v-bind="attributes"
                    v-on="events"
                    class="vs__search"
                    :required="isRequired"
                    :class="{'place-transparent': validationState === 2 && focused === false }"
                />
            </template>
        </v-select>
        <div class="select-container__error">
            <transition name="fade">
                <p v-show="validationState === 2 && focused === false ">
                    {{ errorMessage }}
                </p>
            </transition>
        </div>
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
        value: {

        },
        validationCallback: {
            type: Function
        },
        validationState: {
            type: Number,
        },
        required: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            focused: false,
        }
    },
    computed: {
      isRequired() {
          return (this.required && !this.value )
      }
    },
    created() {
    },
    methods: {
    }
}
</script>

<style scoped lang="scss">
.select-container {
    position: relative;
    margin-bottom: 20px;
}


.select-container__error {
    color: red;
    font-size: 16px;
    min-height: 20px;
    font-weight: 400;
    position: absolute;
    left: 20px;
    top: 1px;
    background-color: #fae6e6;
    height: 95%;
    align-items: center;
    display: flex;
    z-index: 4;
}
.forgotten-input {
    background: unset;
    padding-left: 5px;
    &::placeholder {
        color: black;
    }
}

.place-transparent {
    &::placeholder {
        color: transparent;
    }
}


</style>




