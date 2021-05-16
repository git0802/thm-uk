<template>

    <div class="food-block">
        <div class="food-block__main">
            <img :src="image" alt="" class="food-block__img">

            <div class="food-block__text">
                <span class="food-block__name food-block__name--height-40">{{ name }}   </span>
                <button type="button" class="food-block__details"
                        v-on:click="details = !details"
                        :class="{active: details}"
                >
                    Details
                </button>
            </div>

            <label class="food-block__checked">
                <input type="checkbox" v-model="isChosen">
                <svg width="15" height="10" viewBox="0 0 15 10" fill="#DF2C65" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.7399 0.253771C14.3932 -0.0846007 13.831 -0.0845998 13.4843 0.25383L5.64331 7.90759L1.51579 3.87862C1.16908 3.54019 0.606842 3.54019 0.260075 3.87862C-0.0866917 4.21705 -0.0866917 4.76586 0.260075 5.10435L5.01542 9.74616C5.1888 9.91541 5.41605 10 5.64325 10C5.87044 10 6.09775 9.91535 6.27107 9.74616L14.7399 1.47951C15.0867 1.14108 15.0867 0.592259 14.7399 0.253771Z"/>
                </svg>
            </label>

        </div>
        <div class="food-block__show"
             v-if="details"
        >
            <div class="food-block__show-group food-block__show-group--four">
                <span class="title">Store</span>
                <span class="text">{{ storeName }}</span>
            </div>

            <div class="food-block__show-group food-block__show-group--four" v-tooltip="package_size">
                <span class="title">Serving size</span>
                <span class="text" v-tooltip="item.package_size">{{ servingSize }}</span>
            </div>

            <div class="food-block__show-group food-block__show-group--four">
                <span class="title">Calories per serving</span>
                <span class="text">{{ calories }}</span>
            </div>

            <div class="food-block__show-group food-block__show-group--four">
                <span class="title">Avg cost</span>
                <span class="text">{{ phrase.currencySm }}{{ cost }}</span>
            </div>
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
        storeName: {
            type: String,
            required: true,
            default: 'Default store'
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
        package_size: {

        },
        url: {

        }
    },
    data() {
        return {
            details: false
        }
    },
    computed: {
        ...mapGetters({
            choosedFood: 'food/getChoosedFood',
        }),
        isChosen: {
            get() {
                return this.choosedFood.includes(this.id);
            },
            set(value) {
                if(value) {
                    this.choose(this.id);
                } else {
                    let index = this.choosedFood.findIndex((item) => {
                        return item === this.id;
                    });
                    this.cancelChoose(index);
                }
            }
        }
    },
    methods: {
        ...mapMutations({
            choose: 'food/choose',
            cancelChoose: 'food/cancelChoose'
        })
    }
}
</script>

<style scoped lang="scss">
    .food-block {
        margin-bottom: 17px;
    }
</style>


