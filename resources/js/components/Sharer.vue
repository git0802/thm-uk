<template>
    <div class="social-publish">
        <div class="social-publish__group">
            <div  class="social-publish__button social-publish__button--facebook" @click="buildPopUp('fb')">
                <svg class="icon" width="11" height="24" viewBox="0 0 11 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5254 12.1851H7.31349V23.625H2.44723V12.1851H0.132812V8.16459H2.44723V5.5629C2.44723 3.7024 3.35624 0.789062 7.35681 0.789062L10.9614 0.803724V4.70627H8.34604C7.91704 4.70627 7.31382 4.91465 7.31382 5.80216V8.16833H10.9505L10.5254 12.1851Z" fill="white"/>
                </svg>
            </div>

            <div  class="social-publish__button social-publish__button--linkedin" @click="buildPopUp('lk')">
                <svg class="icon" width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.7547 11.4961V18.7109H16.4521V11.9796C16.4521 10.2894 15.8309 9.13518 14.2731 9.13518C13.0843 9.13518 12.3781 9.91226 12.0662 10.6647C11.9529 10.9336 11.9237 11.307 11.9237 11.6842V18.7106H7.62092C7.62092 18.7106 7.67868 7.30994 7.62092 6.12982H11.9241V7.91263C11.9154 7.92667 11.9032 7.9404 11.8955 7.95381H11.9241V7.91263C12.4959 7.05726 13.5156 5.8344 15.8017 5.8344C18.6324 5.8344 20.7547 7.6325 20.7547 11.4961ZM3.1198 0.0654297C1.64798 0.0654297 0.685059 1.00472 0.685059 2.23881C0.685059 3.4467 1.62006 4.41313 3.06332 4.41313H3.09124C4.59194 4.41313 5.52502 3.4467 5.52502 2.23881C5.49646 1.00472 4.59194 0.0654297 3.1198 0.0654297ZM0.940789 18.7109H5.24201V6.12982H0.940789V18.7109Z" fill="#F1F2F2"/>
                </svg>
            </div>

            <div  class="social-publish__button social-publish__button--twitter" @click="buildPopUp('tw')">
                <svg class="icon" width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.2196 2.57678C20.4696 2.90932 19.6629 3.13424 18.8169 3.23469C19.6807 2.71716 20.3436 1.89829 20.6565 0.920946C19.8482 1.40042 18.9526 1.74824 18.0002 1.93572C17.2372 1.12309 16.15 0.615234 14.9462 0.615234C12.6365 0.615234 10.7633 2.4885 10.7633 4.79819C10.7633 5.12605 10.8004 5.44518 10.8721 5.75151C7.39576 5.57713 4.31337 3.91194 2.25012 1.38045C1.89013 1.99811 1.68362 2.71716 1.68362 3.48363C1.68362 4.93451 2.42263 6.21538 3.54441 6.96531C2.85905 6.94379 2.21362 6.75568 1.64993 6.44155C1.64961 6.45933 1.64961 6.47711 1.64961 6.49458C1.64961 8.52133 3.09208 10.2118 5.00527 10.5958C4.65464 10.6919 4.28435 10.7427 3.90346 10.7427C3.63331 10.7427 3.37159 10.7168 3.11641 10.6682C3.6486 12.3296 5.19307 13.5391 7.02391 13.5731C5.59206 14.6952 3.78866 15.3637 1.82836 15.3637C1.49146 15.3637 1.15767 15.344 0.831055 15.305C2.68124 16.4923 4.88049 17.1845 7.24259 17.1845C14.9366 17.1845 19.1442 10.8107 19.1442 5.28265C19.1442 5.10141 19.1401 4.92079 19.132 4.74173C19.9499 4.15214 20.659 3.41562 21.2196 2.57678Z" fill="#F1F2F2"/>
                </svg>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Sharer",
    data: function() {
        return {
            popUpOptions: {
                target: 'popUpWindow',
                features:
                    'height=800,width=650,left=50,top=50,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes'
            },
        }
    },
    computed: {
        url() {
            return window.location.href
        },
        title() {
            return document.title
        },
        links() {
            return {
                fb: `https://www.facebook.com/sharer/sharer.php?u=${this.url}`,
                lk: `https://www.linkedin.com/sharing/share-offsite/?url=${this.url}`,
                tw: `https://twitter.com/intent/tweet?url=${this.url}&text=${this.title}`,
            }
        },
    },
    methods: {
        buildPopUp(link) {
            window.open(
                this.links[link],
                this.popUpOptions.target,
                this.popUpOptions.features,
            )
        }
    }
}
</script>

<style scoped>

</style>
