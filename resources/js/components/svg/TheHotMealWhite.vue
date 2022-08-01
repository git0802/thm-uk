<template>
    <div class="company-logo">
        <div class="company-logo__icon">

            <svg  viewBox="0 0 212 138" fill="none" xmlns="http://www.w3.org/2000/svg"
                 :style="{height: svgHeight, width: svgWidth}"
            >
                <g clip-path="url(#clip0)">
                    <path d="M0 109.448H212C208.941 125.924 193.065 138 174.461 138H37.5386C18.9352 138 3.05839 125.924 0 109.448Z" fill="#FFFFFF"/>
                    <path d="M106 23.7931C114.781 23.7931 121.9 18.4668 121.9 11.8966C121.9 5.32627 114.781 0 106 0C97.2187 0 90.1 5.32627 90.1 11.8966C90.1 18.4668 97.2187 23.7931 106 23.7931Z" fill="#FFFFFF"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M212 99.9382V99.9458H0C0 64.2409 47.7 28.5552 106 28.5605C164.3 28.5605 212 61.8616 212 99.9382ZM54.9684 61.5214C57.6863 60.5454 59.0081 57.7759 57.9211 55.3356C56.834 52.8954 53.7494 51.7086 51.0316 52.6846C43.5389 55.3756 35.6675 60.582 29.3773 66.2296C23.2034 71.7729 17.7366 78.473 16.0583 84.5008C15.3483 87.0504 17.0749 89.6339 19.9145 90.2711C22.7543 90.9087 25.6318 89.3584 26.3417 86.8087C27.3134 83.3192 31.1217 78.1228 36.8727 72.9592C42.5075 67.8998 49.2111 63.589 54.9684 61.5214Z" fill="url(#paint0_linear)"/>
                    <path d="M111.3 9.51758H100.7V33.3107H111.3V9.51758Z" fill="#FFFFFF"/>
                </g>
                <defs>
                    <linearGradient id="paint0_linear" x1="106" y1="28.5605" x2="106" y2="99.9458" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#FFFFFF"/>
                        <stop offset="1" stop-color="#FFFFFF"/>
                    </linearGradient>
                    <clipPath id="clip0">
                        <rect width="212" height="138" fill="white"/>
                    </clipPath>
                </defs>
            </svg>

        </div>
        <div class="company-logo__caption" :style="{fontSize: textSize}">
            TheHotMeal
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            svgHeight: {
                type: String,
                default: '14px',
            },
            svgWidth: {
                type: String,
                default: '19px'
            },
            svgFill: {
                type: String,
                default: '#FFFFFF',
            },
            textSize: {
                type: String,
                default: '16px',
            },
            textColor: {
                type: String,
                default: '#FFFFFF'
            }
        },
        name: "TheHotMeal"
    }
</script>

<style scoped lang="scss">
    .company-logo {
        display: flex;
        justify-content: center;
        align-items: baseline;
        &__icon {

        }
        &__caption {
            margin-left: 5px;
            font-weight: 800 !important;
            color: #FFFFFF;
        }
    }

</style>
