<template>
    <td :class="{mainer: is_mobile, 'is-opened': is_opened }" @click="$emit('click')">
       <span class="bold" :class="{text: is_mobile}">{{this.text}}</span>
        <span class="svg" v-if="is_mobile">
            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 5.33301L0 0.333008H10L5 5.33301Z" fill="#292D34"/>
            </svg>
        </span>
    </td>
</template>

<script>
export default {
    props: ['column', 'is_mobile', 'text', 'is_opened'],
    name: "week-td"
}
</script>

<style scoped lang="scss">
.text {
    word-break: break-all;
}
.thin {
    font-weight: 500;
}
.bold {
    font-weight: 900;
}

.mainer {
    @media screen and (max-width: 960px) {
        cursor: pointer;
    }
    &.is-opened {
        .svg {

            transform: rotate(180deg);
        }
    }
}

.svg {
    margin-right: 5px;
    margin-left: auto;
    transition: all 0.2s ease-in-out;
}


</style>
