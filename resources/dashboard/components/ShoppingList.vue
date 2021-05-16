<template>
    <table class="shopping-table" :class="{'shopping-table__mobile': is_mobile}">
        <thead>
        <tr>
            <th>
                Food
            </th>
            <th>

            </th>
            <th>
                Store
            </th>
            <th>
                Serving size
            </th>
            <th>
                Calories Per<br>Serving
            </th>
            <th>
                Total # of<br>Servings
            </th>
            <th>
                Quantity
            </th>
            <th>
                Cost
            </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in list" :class="{'mobile-tr': is_mobile}">
            <shopping-td :column="'Food'" :item_url="item.url" :is_mobile="is_mobile" :text="item.name"/>
            <td v-if="!is_mobile">
                <a v-if="item.url" :href="item.url" target="_blank" class="food__links food__links-group">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.253 6.61464L1.27006 8.59758C0.456853 9.41078 0 10.5137 0 11.6638C0 12.8138 0.456853 13.9168 1.27006 14.73C2.08326 15.5432 3.1862 16 4.33625 16C5.48629 16 6.58923 15.5432 7.40243 14.73L10.0444 12.0865C10.5348 11.5961 10.9004 10.9951 11.1105 10.3342C11.3205 9.6732 11.369 8.97148 11.2518 8.2879C11.1345 7.60433 10.855 6.95886 10.4367 6.40565C10.0184 5.85245 9.4735 5.40767 8.84773 5.10864L8.00079 5.95558C7.91479 6.04171 7.84001 6.13835 7.77821 6.2432C8.2617 6.38219 8.70054 6.64496 9.05136 7.00553C9.40217 7.3661 9.65281 7.81198 9.7785 8.2991C9.90418 8.78622 9.90056 9.2977 9.76799 9.78299C9.63542 10.2683 9.37849 10.7106 9.02261 11.0661L6.38206 13.7081C5.83967 14.2505 5.10403 14.5552 4.33697 14.5552C3.56991 14.5552 2.83427 14.2505 2.29188 13.7081C1.74949 13.1657 1.44477 12.4301 1.44477 11.663C1.44477 10.896 1.74949 10.1603 2.29188 9.61795L3.438 8.47328C3.27629 7.86731 3.21381 7.23914 3.253 6.61319V6.61464Z" fill="#DF2C65"/>
                        <path d="M4.69338 7.21877L4.93474 6.9774C5.29282 6.618 5.73959 6.35971 6.22972 6.22874C6.3607 5.7386 6.61898 5.29183 6.97838 4.93376L7.21975 4.69239C6.49272 4.65152 5.76708 4.79416 5.10962 5.10719C4.79166 5.77203 4.65291 6.49901 4.69338 7.21732V7.21877Z" fill="#DF2C65"/>
                        <path d="M5.95723 3.91205C5.46685 4.4025 5.10128 5.00343 4.8912 5.6644C4.68112 6.32537 4.63266 7.02709 4.74989 7.71066C4.86712 8.39424 5.14663 9.03971 5.56494 9.59291C5.98325 10.1461 6.52816 10.5909 7.15393 10.8899L8.27404 9.76838C7.78401 9.63694 7.3372 9.37884 6.97853 9.02001C6.61985 8.66118 6.36193 8.21427 6.2307 7.72419C6.09946 7.2341 6.09953 6.71811 6.23089 6.22806C6.36225 5.73801 6.62028 5.29116 6.97905 4.93243L9.6196 2.29043C10.162 1.74804 10.8976 1.44333 11.6647 1.44333C12.4317 1.44333 13.1674 1.74804 13.7098 2.29043C14.2522 2.83283 14.5569 3.56847 14.5569 4.33552C14.5569 5.10258 14.2522 5.83822 13.7098 6.38061L12.5637 7.52528C12.7255 8.13231 12.7877 8.76101 12.7487 9.38538L14.7316 7.40243C15.5448 6.58923 16.0017 5.48629 16.0017 4.33625C16.0017 3.1862 15.5448 2.08326 14.7316 1.27006C13.9184 0.456853 12.8155 0 11.6654 0C10.5154 0 9.41243 0.456853 8.59923 1.27006L5.95723 3.91205Z" fill="#DF2C65"/>
                        <path d="M10.8924 10.8897C11.2067 10.2326 11.3499 9.50676 11.3086 8.77954L11.0672 9.0209C10.7092 9.3803 10.2624 9.63859 9.77225 9.76957C9.64128 10.2597 9.38299 10.7065 9.02359 11.0645L8.78223 11.3059C9.50926 11.3468 10.2349 11.2041 10.8924 10.8911V10.8897Z" fill="#DF2C65"/>
                    </svg>
                    <span class="food__tooltip">Check website</span>
                </a>
            </td>
            <shopping-td :column="'Store'" :is_mobile="is_mobile" :text="item.store"/>
            <shopping-td :column="'Serving size'" :is_mobile="is_mobile" :text="item.serving_size"/>
            <shopping-td :column="'Calories Per Serving'" :is_mobile="is_mobile" :text="item.calories + 'cal'"/>
            <!--<shopping-td :column="'Per serving'" :is_mobile="is_mobile" :text="item.servings"/>-->
            <shopping-td :column="'Total # of Servings'" :is_mobile="is_mobile" :text="item.servings"/>
            <td>
                <span class="thin" :hidden="!is_mobile">Quantity</span>
                <div class="qty">
                    <button type="button" class="qty-btn" @click="decreaseQuantity(item.id)">
                        <span>-</span>
                    </button>
                    <span class="qty-number">{{item.quantity}}</span>
                    <button type="button" class="qty-btn" @click="increaseQuantity(item.id)">
                        <span>+</span>
                    </button>
                </div>
            </td>
            <shopping-td :column="'Cost'" :is_mobile="is_mobile" :text="'£' + (item.cost).toFixed(2)"/>
        </tr>
        </tbody>
        <tfoot>
        <tr class="totalize">
            <!--                        <td class="nowrap" colspan="5">Total: <span class="span">{{ flatten_shop_list.length }} {{ pluralize('item', flatten_shop_list.length) }}</span></td>-->
            <!--                        <td class="span nowrap"> {{total_calories}}cal</td>-->
            <!--                        <td class="span nowrap"> {{ Math.ceil(total_cost) }} $</td>-->
            <total-td colspan="4" :column="'Total items'" :is_mobile="is_mobile" :text="list.length + ' ' + pluralize('item', list.length)" :subText="'Total:'" />
            <total-td colspan="3" :column="'Total calories'" :is_mobile="is_mobile" :text="total_calories + 'cal'" />
            <total-td :column="'Avg price'" :is_mobile="is_mobile" :text="'£' + (total_cost).toFixed(2)" />
        </tr>
        </tfoot>
    </table>

</template>

<script>
import TotalTd from "./planner/shopping/total-td";
import ShoppingTd from "./planner/shopping/shopping-td";
import pluralize from "pluralize";

export default {
    name: "ShoppingList",
    components: {TotalTd, ShoppingTd},
    props: ['list', 'plannerId'],
    data: function() {
        return {
            wind_width: window.innerWidth,
        }
    },
    computed: {
        is_mobile: function () {
            return this.wind_width <= 680;
        },
        total_calories: function() {
            let a = this.list;
            let calories = 0;
            a.forEach((item) => {
                calories += (parseFloat(item.calories) * item.servings);
            })
            return calories;
        },
        total_cost: function() {
                let a = this.list;
                let cost = 0;
                a.forEach((item) => {
                    cost += parseFloat(item.cost);
                })
                return cost;
        },
    },
    mounted() {
        window.addEventListener('resize', this.widthResize)
    },
    methods: {
        widthResize: function () {
            this.wind_width = window.innerWidth;
        },
        async increaseQuantity(id) {
            try {
                let res = await this.$http.post(`/api/planner/${this.plannerId}/shopping/actions`, {
                    action: 1,
                    id: id,
                });

                if(res.data.success) {
                    this.$notify({
                        group: 'planner',
                        title: 'Yay!',
                        type: 'success',
                        text: res.data.message,
                    });

                    await this.$emit('update')
                }

            } catch (e) {
                this.$notify({
                    group: 'planner',
                    title: 'Error!',
                    type: 'error',
                    text: e.response.data.message,
                });
            }
        },
        async decreaseQuantity(id) {
            try {
                let res = await this.$http.post(`/api/planner/${this.plannerId}/shopping/actions`, {
                    action: 0,
                    id: id,
                });

                if(res.data.success) {
                    this.$notify({
                        group: 'planner',
                        title: 'Yay!',
                        type: 'success',
                        text: res.data.message,
                    });
                    await this.$emit('update')
                }


            } catch (e) {
                this.$notify({
                    group: 'planner',
                    title: 'Error!',
                    type: 'error',
                    text: e.response.data.message,
                });
            }
        },
        pluralize: pluralize,
    }

}
</script>

<style scoped lang="scss">
.qty {
    margin-left: -6px;
}

.mobile-tr {
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.25);
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    span.text {
        padding-left: 6px;
        margin-left: auto;
    }
    &:last-child {
        margin-bottom: 0;
    }
    .qty {
        margin-left: auto;
    }
}

.thin {
    font-family: Gilroy;
    font-weight: 900;
}

.totalize {
    font-family: Gilroy;
    .span {
        color: #DF2C65;
    }

}
.container__content__table {
    margin: 0 10px 15px 10px;
    &--mobile {
        margin: unset;
        padding: unset;
    }
}

.shopping-table {
    /*border-bottom: 1px solid #DFDFDF;*/
    font-family: Gilroy;
    width: 100%;

    &__mobile {
        display:flex;
        flex-direction: column;
        thead {
            display: none
        }
        tr {
            display: flex;
            flex-direction: column;
        }
        td {
            display: flex;
        }
    }

    td {
        font-weight: 500;
    }
    th {
        white-space: nowrap;
        font-weight: 400;
        opacity: .7;
    }


    thead {
        font-size: 13px;
        text-align: justify;
        th {
            padding: 0 10px 10px 10px;
        }
    }
    tbody {
        font-size: 14px;
        td {
            padding: 10px;
        }
        tr:last-child {
            td {
                padding: 10px 10px 18px 10px;
            }
        }
        tr:nth-child(even) {
            background: rgb(178 125 255 / .1);
        }
    }
    tfoot {
        border-top: 1px solid #DFDFDF;
        td {
            padding: 18px 10px 10px 10px;
        }
    }
}

</style>
