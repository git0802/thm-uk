<template>
    <div class="btn-read-more">
        <span>Read more</span>
        <svg width="5" height="7" viewBox="0 0 5 7" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M-2.87115e-07 0.431579L4.92632 3.71579L0 7L-2.87115e-07 0.431579Z" fill="#DF2C65"/>
        </svg>
    </div>
</template>

<script>
    export default {
        name: "ButtonReadMore"
    }
</script>

<style scoped lang="scss">
    .btn-read-more {
        display: flex;
        align-items: center;
        font-weight: 800;
        font-size: 18px;
        line-height: 132.19%;
        color: var(--primary);
        &:hover svg {
            transform: translateX(4px);
        }
        svg {
            width: 6px;
            height: 10px;
            margin-left: 8px;
            margin-top: 2px;
            transition: .15s linear;
        }
    }
</style>
