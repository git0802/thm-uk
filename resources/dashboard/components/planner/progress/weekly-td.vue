<template>
    <td>
        <span class="thin" :hidden="!is_mobile">{{this.column}}</span>
        <span class="bold" :class="{text: is_mobile}">{{this.text}}</span>
    </td>
</template>

<script>
export default {
    props: ['column', 'is_mobile', 'text'],
    name: "week-td"
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
    font-weight: 500;
}
.bold {
    font-weight: 900;
}

</style>
