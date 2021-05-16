<template>
    <div class="food-block food-group">
        <div class="food-block__main">

            <figure class="food-block__group-img">
                <template v-for="(product, index) in dish">
                    <img :key="index" v-if="index <= 3" :alt="product.name  + ' ' + index"
                         :src="product.image"
                         onerror="this.src='/images/no-product-image.jpg?t=0'"
                    />
                </template>
            </figure>

            <div class="food-block__text">
                <span class="food-block__name food-block__name--height-40"> {{ name }} </span>
                <button type="button" class="food-block__details"
                        v-on:click="details = !details"
                        :class="{active: details}"
                >
                    Details
                </button>
            </div>

            <div class="food-block__info">
                <div class="food-block__show-group food-block__show-group--three">
                    <span class="title">Serving size</span>
                    <span class="text">{{ servingSize }}</span>
                </div>

                <div class="food-block__show-group food-block__show-group--three">
                    <span class="title">Cal per Serv</span>
                    <span class="text">{{ calories }}</span>
                </div>

                <div class="food-block__show-group food-block__show-group--three">
                    <span class="title">Avg cost</span>
                    <span class="text">{{ phrase.currencySm }} {{ cost }}</span>
                </div>
            </div>


            <button type="button" class="food-block__delete" @click="deleteFoodIdChoosed(id)">
                <svg width="18" height="21" viewBox="0 0 18 21" fill="currentColor">
                    <path d="M5.31983 7.24032L6.83144 7.18003L7.15916 16.9124L5.64755 16.9724L5.31983 7.24032Z"/>
                    <path d="M8.2437 7.21018H9.7563V16.9426H8.2437V7.21018Z"/>
                    <path d="M10.8411 16.9121L11.1688 7.17974L12.6804 7.24008L12.3527 16.9724L10.8411 16.9121Z"/>
                    <path d="M18 3.15275V4.79764H16.4239L15.1735 20.2497C15.1392 20.6743 14.8122 21 14.4202 21H3.6053C3.21327 21 2.88605 20.674 2.85203 20.2494L1.6016 4.79764H0V3.15275H18ZM4.29805 19.3551H13.7276L14.9055 4.79764H3.11996L4.29805 19.3551Z"/>
                    <path d="M6.5294 0H11.4706C12.1656 0 12.7311 0.614906 12.7311 1.37074V3.97519H11.2185V1.64489H6.7815V3.97519H5.26891V1.37074C5.26891 0.614906 5.83436 0 6.5294 0Z"/>
                </svg>
            </button>



        </div>
        <div class="food-block__show food-block__show--colums"
             v-if="details"
        >
            <template v-for="item in dish">
                <div class="food-group__wrap">
                    <div class="food-group__block">
                        <img
                            :src="item.image"
                            onerror="this.src='/images/no-product-image.jpg?t=0'"
                        >
                        <div class="food-group__block-group">
                            <span class="name" v-tooltip="item.name"><a v-if="item.url" :href="item.url" target="_blank" class="food__links food__links-group">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.253 6.61464L1.27006 8.59758C0.456853 9.41078 0 10.5137 0 11.6638C0 12.8138 0.456853 13.9168 1.27006 14.73C2.08326 15.5432 3.1862 16 4.33625 16C5.48629 16 6.58923 15.5432 7.40243 14.73L10.0444 12.0865C10.5348 11.5961 10.9004 10.9951 11.1105 10.3342C11.3205 9.6732 11.369 8.97148 11.2518 8.2879C11.1345 7.60433 10.855 6.95886 10.4367 6.40565C10.0184 5.85245 9.4735 5.40767 8.84773 5.10864L8.00079 5.95558C7.91479 6.04171 7.84001 6.13835 7.77821 6.2432C8.2617 6.38219 8.70054 6.64496 9.05136 7.00553C9.40217 7.3661 9.65281 7.81198 9.7785 8.2991C9.90418 8.78622 9.90056 9.2977 9.76799 9.78299C9.63542 10.2683 9.37849 10.7106 9.02261 11.0661L6.38206 13.7081C5.83967 14.2505 5.10403 14.5552 4.33697 14.5552C3.56991 14.5552 2.83427 14.2505 2.29188 13.7081C1.74949 13.1657 1.44477 12.4301 1.44477 11.663C1.44477 10.896 1.74949 10.1603 2.29188 9.61795L3.438 8.47328C3.27629 7.86731 3.21381 7.23914 3.253 6.61319V6.61464Z" fill="#DF2C65"/>
                                    <path d="M4.69338 7.21877L4.93474 6.9774C5.29282 6.618 5.73959 6.35971 6.22972 6.22874C6.3607 5.7386 6.61898 5.29183 6.97838 4.93376L7.21975 4.69239C6.49272 4.65152 5.76708 4.79416 5.10962 5.10719C4.79166 5.77203 4.65291 6.49901 4.69338 7.21732V7.21877Z" fill="#DF2C65"/>
                                    <path d="M5.95723 3.91205C5.46685 4.4025 5.10128 5.00343 4.8912 5.6644C4.68112 6.32537 4.63266 7.02709 4.74989 7.71066C4.86712 8.39424 5.14663 9.03971 5.56494 9.59291C5.98325 10.1461 6.52816 10.5909 7.15393 10.8899L8.27404 9.76838C7.78401 9.63694 7.3372 9.37884 6.97853 9.02001C6.61985 8.66118 6.36193 8.21427 6.2307 7.72419C6.09946 7.2341 6.09953 6.71811 6.23089 6.22806C6.36225 5.73801 6.62028 5.29116 6.97905 4.93243L9.6196 2.29043C10.162 1.74804 10.8976 1.44333 11.6647 1.44333C12.4317 1.44333 13.1674 1.74804 13.7098 2.29043C14.2522 2.83283 14.5569 3.56847 14.5569 4.33552C14.5569 5.10258 14.2522 5.83822 13.7098 6.38061L12.5637 7.52528C12.7255 8.13231 12.7877 8.76101 12.7487 9.38538L14.7316 7.40243C15.5448 6.58923 16.0017 5.48629 16.0017 4.33625C16.0017 3.1862 15.5448 2.08326 14.7316 1.27006C13.9184 0.456853 12.8155 0 11.6654 0C10.5154 0 9.41243 0.456853 8.59923 1.27006L5.95723 3.91205Z" fill="#DF2C65"/>
                                    <path d="M10.8924 10.8897C11.2067 10.2326 11.3499 9.50676 11.3086 8.77954L11.0672 9.0209C10.7092 9.3803 10.2624 9.63859 9.77225 9.76957C9.64128 10.2597 9.38299 10.7065 9.02359 11.0645L8.78223 11.3059C9.50926 11.3468 10.2349 11.2041 10.8924 10.8911V10.8897Z" fill="#DF2C65"/>
                                </svg>
                                <span class="food__tooltip bottom">Check website</span>
                            </a> {{ item.name }}</span>
                        </div>
                        <span class="text">{{ item.store.name }}</span>
                        <span class="text" v-tooltip="item.package_size">{{ item.serving_size }}</span>
                        <span class="text">{{ item.calories }}</span>
                        <span class="text">{{ phrase.currencySm }} {{ item.cost }}</span>
                    </div>
                </div>

            </template>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";

export default {
    props: {
        id: {
            type: Number,
            required: true,
            default: 0
        },
        image: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true,
            default: 'Food name'
        },
        servingSize: {
            required: true,
            default: '0'
        },
        calories: {
            type: Number,
            required: true
        },
        cost: {
            type: Number,
            required: true,
            default: 0.0
        },
        dish: {

        }
    },
    data() {
        return {
            details: false
        }
    },
    computed: {
        ...mapGetters({
            food: 'food/getMyFood',
        }),
    },
    methods: {
        ...mapMutations({
            deleteFoodIdChoosed: 'food/deleteFoodIdChoosed'
        }),
        removeDish() {
            let index = this.food.findIndex((item) => {
                return item.id === this.id;
            });
            this.$store.commit('food/deleteItem', index)
        }
    }
}
</script>

<style scoped lang="scss">
    .food-block {
        margin-bottom: 17px;
    }
    .food {
        &__links {
            margin-top: 0;
        }
    }
</style>


