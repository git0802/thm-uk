<template>
    <div class="play">
        <svg width="30" height="39" viewBox="0 0 30 39" fill="#121212" xmlns="http://www.w3.org/2000/svg">
            <path d="M29.3999 19.1545L-9.89493e-05 38.3091L-9.72748e-05 -1.28511e-06L29.3999 19.1545Z"/>
        </svg>
    </div>
</template>

<script>
    export default {
        name: "ButtonPlay"
    }
</script>

<style scoped lang="scss">
    .play {
        width: 100%;
        height: 100%;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 250ms;
        svg {
            margin-left: 8px;
            transition: 250ms;
        }
        &:hover {
            background: var(--primary);
            svg {
                fill: #fff;
            }
        }
    }
</style>
