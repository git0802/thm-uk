<template>
    <button class="button" :class="[{'disabled': disabled}, classes]"
            @click="$emit('click')"
            :style="styles"
            :type="type"
            :disabled="disabled"
            v-text="text"
    />
</template>

<script>
    export default {
        props: {
            text: {
                type: String,
                required: true,
            },
            styles: {
                type: Object,
                default: () => {},
            },
            classes: {
              default: null,
            },
            type: {
                type: String,
                default: ''
            },
            disabled: {
                type: Boolean,
                default: false,
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
