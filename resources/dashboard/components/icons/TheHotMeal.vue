<template>
    <div class="company-logo">
        <div class="company-logo__icon">
            <svg width="19" height="14" viewBox="0 0 40 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="20" cy="2.5" rx="3" ry="2.5" fill="#21090D"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M40 21.0016V21.0032H0C0 13.5 9 6.00082 20 6.00195C31 6.00195 40 13 40 21.0016ZM10.3714 12.9285C10.8842 12.7234 11.1336 12.1414 10.9285 11.6286C10.7234 11.1158 10.1414 10.8664 9.62861 11.0715C8.21488 11.637 6.72972 12.7311 5.54289 13.9179C4.37799 15.0828 3.34652 16.4908 3.02986 17.7575C2.89591 18.2933 3.22167 18.8362 3.75746 18.9701C4.29326 19.1041 4.83619 18.7783 4.97014 18.2425C5.15348 17.5092 5.87201 16.4172 6.95711 15.3321C8.02028 14.2689 9.28512 13.363 10.3714 12.9285Z" fill="#21090D"/>
                <path d="M0 23H40V23C39.4229 26.4623 36.4273 29 32.9172 29H7.08276C3.57267 29 0.577055 26.4623 0 23V23Z" fill="#21090D"/>
                <rect x="19" y="2" width="2" height="5" fill="#21090D"/>
            </svg>

        </div>
        <div class="company-logo__caption">
            TheHotMeal
        </div>
    </div>
</template>

<script>
    export default {
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
            font-family: Gilroy;
        }
    }

</style>
