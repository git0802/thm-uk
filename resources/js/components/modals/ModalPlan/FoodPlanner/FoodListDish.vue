<template>
    <div class="food-list">
        <Container
            :group-name="'food'"
            :remove-on-drop-out="true"
            :drop-placeholder="dropPlaceholderOptions"
            drag-class="card-ghost"
            drop-class="card-ghost-drop"
            @drag-start="(e) => {handleDrag(e)}"
            :get-child-payload="getCardPayload(dishItems)"
        >
            <Draggable
                v-for="(food, index) in dishItems"
                :key="food.index">
                <food-item-dish
                    :id="food.id"
                    :image="food.image ? food.image : '/images/no-product-image.jpg'"
                    :name="food.name"
                    :servingSize="food.serving_size"
                    :calories="food.calories"
                    :cost="Math.ceil(food.cost * 100)/100"
                    :dish="food.dish"
                />
            </Draggable>
        </Container>


    </div>
</template>

<script>
import vueCustomScrollbar from 'vue-custom-scrollbar';
import {mapGetters, mapMutations} from "vuex";
import FoodListMob from "./FoodListMob";
import {Container, Draggable} from "vue-smooth-dnd";
import FoodItemProduct from "./FoodItemProduct";
import FoodItemDish from "./FoodItemDish";


export default {
    components: {
        FoodItemDish,
        FoodItemProduct,
        FoodListMob,
        vueCustomScrollbar,
        Container,
        Draggable
    },
    data() {
        return {
            settingsScroll: {
                wheelPropagation: false,
                suppressScrollX: true,
            },
            dropPlaceholderOptions: {
                className: 'drop-preview',
                animationDuration: '150',
                showOnTop: true
            },
            drag: false,
        }
    },
    computed: {
        ...mapGetters({
            myFood: 'food/getMyFood',
        }),
        dishItems() {
            return this.myFood.filter((e) => {
                return e.is_dish === true
            })
        },
        productItems() {
            return this.myFood.filter((e) => {
                return e.is_dish === false
            })
        },
    },
    watch: {
        drag(newVal, oldVal) {
            if(!newVal) {
                let body = document.body;
                body.classList.remove('smooth-dnd-no-user-select');
                body.classList.remove('smooth-dnd-disable-touch-action');
            }
        },
    },
    methods: {
        ...mapMutations({
            setDragStartFood: 'food/setDragStartFood'
        }),
        killDrag() {
            this.drag = !this.drag;
        },
        dragstart(item) {
            this.setDragStartFood(item)
        },
        handleDrag(e) {
            this.dragstart(e.payload)
        },
        getCardPayload(graph) {
            return index => {
                return graph[index]
            }
        }
    },
}
</script>

<style scoped lang="scss">
@import '../../../../../sass/mixins';


.food-list {
    @include small-font;

    min-height: 282px;
    border: 1px solid var(--grey-5);
    border-radius: 7px;
    background: var(--white);
    display: flex;
    flex-direction: column;
    padding: 11px 10px;
    @media screen and (max-width: 767px) {
        min-height: 382px;
    }

    &__mob {
        @media screen and (min-width: 768px) {
            display: none;
        }
    }

    &__desktop {
        padding: 0 16px;
        @media screen and (max-width: 767px) {
            display: none;
        }
    }

    &__header {
        display: flex;
        flex-direction: row;
        margin-bottom: 12px;
        padding-left: 16px;
        padding-right: 16px;
        @media screen and (max-width: 767px) {
            display: none;
        }
    }

    &__empty {
        color: var(--black-dark);
        text-align: center;
        margin-top: 30px;
    }

    &__param {
        flex-basis: 17.5%;
        text-align: center;
        &:first-child {
            flex-basis: 30%;
        }
    }
}

.food-item {
    &__wrapper {
        overflow-y: auto;
        padding-left: 16px;
        padding-right: 16px;
    }
}
</style>

