<template>
    <div class="food-items">
        <div class="food-items__btn"
             v-on:click="foodShows = !foodShows"
        >
            <div class="food-items__img">
                <div class="food-item__choose">
                    <icon-choose @click.native="cancelChooseFoodItem(id)" v-if="isChoosed == id"/>
                    <icon-not-choose @click.native="chooseFoodItem(id)" v-else/>
                </div>
                <img :src="image" alt="">
            </div>
            <span class="food-items__main-title">{{ name }}</span>
        </div>

        <div class="food-shows"
             v-show="foodShows"
        >
            <span class="food-shows__text">Store <b>{{ storeName }}</b></span>
            <span class="food-shows__text">Serving size <b>{{ servingSize }}</b></span>
            <span class="food-shows__text">Calories Per Serving <b>{{ calories }}</b></span>
            <span class="food-shows__text">Avg cost <b>{{ phrase.currencySm }}{{ cost }}</b></span>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from "vuex";
    import IconChoose from "../../../svg/IconChoose"
    import IconNotChoose from "../../../svg/IconNotChoose"

    export default {
        components: {
            IconChoose,
            IconNotChoose
        },
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
            }
        },
        data() {
            return {
                isChoosed: '',
                foodShows: false
            }
        },
        computed: {
            ...mapGetters({
                choosedFood: 'food/getChoosedFood',
            }),
        },
        methods: {
            ...mapMutations({
                choose: 'food/choose',
                cancelChoose: 'food/cancelChoose'
            }),
            chooseFoodItem() {
                this.isChoosed = this.id;
                this.choose(this.id);
            },
            cancelChooseFoodItem(id) {
                let index = this.choosedFood.findIndex((item) => {
                    return item === id;
                });
                this.isChoosed = '';
                this.cancelChoose(index);
            }
        }
    }
</script>

<style scoped lang="scss">
    .food-items__wrapper:nth-child(odd) {
        .food-items {
            background: #b27dff1a;
        }
    }

    .food-items {
        display: flex;
        flex-direction: column;
        &__btn {
            display: flex;
            align-items: center;
            min-height: 42px;
            position: relative;
            padding: 6px 36px 6px 16px;
            &:after {
                content: "";
                position: absolute;
                right: 16px;
                top: 50%;
                transform: translateY(-50%);
                border-style: solid;
                border-width: 6px 5px 0px 6px;
                border-color: #000 transparent transparent transparent;
            }
        }
        &__img {
            height: 32px;
            width: 32px;
            min-width: 32px;
            background: #f9f9f9;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 16px;
            position: relative;
            .food-item__choose {
                position: absolute;
                width: 100%;
                height: 100%;
                svg.icon-not-choose {
                    opacity: 0;
                }
                svg {
                    width: 100%;
                    height: 100%;
                    opacity: .8;
                }
                circle {
                    width: 100%;
                    height: 100%;
                }
            }
            img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
        &__main-title {
            font-size: 16px;
            font-weight: 700;
        }
    }

    .food-shows {
        display: flex;
        flex-direction: column;
        padding: 20px 16px;
        &__text {
            font-size: 14px;
            margin-bottom: 20px;
            color: #292D34;
            display: flex;
            justify-content: space-between;
            &:last-child {
                margin-bottom: 0;
            }
            b {
                font-weight: 700;
                text-align: right;
                padding-left: 10px;
            }
        }
    }
</style>
