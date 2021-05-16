<template>
    <div class="verification">
        <div class="verification__cont">
            <a href="/" class="verification__logo">
                <svg viewBox="0 0 212 138" fill="none">
                    <g clip-path="url(#clip0)">
                        <path
                            d="M0 109.448H212C208.941 125.924 193.065 138 174.461 138H37.5386C18.9352 138 3.05839 125.924 0 109.448Z"
                            fill="#FF657A"/>
                        <path
                            d="M106 23.7931C114.781 23.7931 121.9 18.4668 121.9 11.8966C121.9 5.32627 114.781 0 106 0C97.2187 0 90.1 5.32627 90.1 11.8966C90.1 18.4668 97.2187 23.7931 106 23.7931Z"
                            fill="#FF8E99"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M212 99.9382V99.9458H0C0 64.2409 47.7 28.5552 106 28.5605C164.3 28.5605 212 61.8616 212 99.9382ZM54.9684 61.5214C57.6863 60.5454 59.0081 57.7759 57.9211 55.3356C56.834 52.8954 53.7494 51.7086 51.0316 52.6846C43.5389 55.3756 35.6675 60.582 29.3773 66.2296C23.2034 71.7729 17.7366 78.473 16.0583 84.5008C15.3483 87.0504 17.0749 89.6339 19.9145 90.2711C22.7543 90.9087 25.6318 89.3584 26.3417 86.8087C27.3134 83.3192 31.1217 78.1228 36.8727 72.9592C42.5075 67.8998 49.2111 63.589 54.9684 61.5214Z"
                              fill="url(#paint0_linear)"/>
                        <path d="M111.3 9.51758H100.7V33.3107H111.3V9.51758Z" fill="#FF8E99"/>
                    </g>
                    <defs>
                        <linearGradient id="paint0_linear" x1="106" y1="28.5605" x2="106" y2="99.9458"
                                        gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FF8E99"/>
                            <stop offset="1" stop-color="#FF657A"/>
                        </linearGradient>
                        <clipPath id="clip0">
                            <rect width="212" height="138" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
                <span>TheHotMeal</span>
            </a>

            <div class="preloader-itd">
                <svg class="preloader-itd__spinner" viewBox="0 0 66 66"
                     xmlns="http://www.w3.org/2000/svg">
                    <circle class="preloader-itd__circle" fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                </svg>
                <svg class="preloader-itd__spinner-bg" viewBox="0 0 66 66"
                     xmlns="http://www.w3.org/2000/svg">
                    <circle class="preloader-itd__circle-bg" fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                </svg>
            </div>

            <span class="verification__text">please wait Your data is being processed</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "verification"
    }
</script>

<style lang="scss" scoped>
    .preloader-itd {
        margin-top: 40px;
    }
    .verification {
        display: flex;
        height: 100%;
        justify-content: center;
        align-items: center;
    }
    .verification__cont {
        display: flex;
        flex-direction: column;
        max-width: 600px;
        width: 100%;
        align-items: center;
        padding: 20px 25px;
        min-height: 400px;
    }
    .verification__logo {
        display: flex;
        flex-direction: column;
        svg {
            width: 140px;
            height: 92px;
        }
        span {
            font-size: 25px;
            font-weight: 800;
            color: #ff7082;
            margin-top: 10px;
        }
    }
    .verification__text {
        font-size: 28px;
        text-align: center;
        max-width: 400px;
        margin-top: 40px;
    }
</style>
