<template>
    <td>
        <span class="thin" :hidden="!is_mobile">
            {{this.column}}
        </span>
        <span :class="{text: is_mobile}" class="totalize">
            <span v-if="subText && !is_mobile">
                {{subText}}
            </span>
            <span class="span" :class="{text: is_mobile}">
                {{this.text}}
            </span>
        </span>
    </td>
</template>

<script>
    export default {
        props: ['column', 'is_mobile', 'text', 'subText'],
        name: "total-td"
    }
</script>

<style scoped lang="scss">

    .text {
        margin-left: auto;
        max-width: 60%;
        word-break: break-all;
        font-family: Gilroy;
    }
    .thin {
        font-family: Gilroy;
        font-weight: 900;
    }

    .totalize {
        font-family: Gilroy;

        .span {
            color: #DF2C65;
        }
    }
</style>
