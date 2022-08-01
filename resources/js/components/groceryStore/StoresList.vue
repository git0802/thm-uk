<template>
    <div class="stores-list">
        <div v-for="(store, index) in stores" :key="store.id">
            <store-view
                :id="store.id"
                :image="store.image"
                :name="store.name"
                :isCustom="store.is_custom"
                @choosed="chooseStore"
            />

        </div>
        <button-add-new-store v-if="$store.state.auth.guest"
            @click="actionsModal({
                name: 'modalRegister',
                action: 'open'
            })"
        />
        <button-add-new-store v-else
            @click="actionsModal({
                name: 'modalStore',
                action: 'open'
            }), clearMessages()"
        />
    </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";

export default {
    name: "SoresList",
    data() {
        return {
            isChoosed: false,
            myDishes: false
        }
    },
    computed: {
        ...mapGetters({
            stores: 'stores/getStores',
            choosedStores: 'stores/getChoosedStores',
        }),
    },
    async created() {
        await this.getStores();
        await this.checkIsMyDihes();
        if (!this.myDishes || this.choosedStores.length < 0) {
            await this.chooseMyDishesStore('Grouped Food');
            await this.chooseMyDishesStore('Add your store');
        }
    },
    methods: {
        ...mapMutations({
            choose: 'stores/choose',
            choosePush: 'stores/choosePush',
            cancelChoose: 'stores/cancelChoose',
        }),
        ...mapActions({
            getStores: 'stores/getStoresList',
            actionsModal: 'modals/actionsModal',
            clearMessages: 'stores/clearMessages'
        }),

        checkIsMyDihes() {
            this.stores.forEach((x) => {
                const isThereAMatch = this.choosedStores.some((y) => {
                    return y.id === x.id;
                });
                this.myDishes = isThereAMatch === true;
            });
        },
        async chooseMyDishesStore(value) {
            for (let i = 0; i < this.stores.length; i++) {
                if (this.stores[i].name == value) {
                    const isThereAMatch = this.choosedStores.some((y) => {
                        return y.name === this.stores[i].name;
                    });
                    if(!isThereAMatch){
                        this.choosePush(this.stores[i])
                        this.myDishes = true
                    } else {
                        this.myDishes = false
                    }
                }
            }
        },
        chooseStore(id, state) {
            const store = this.findStore(id);
            if (store) {
                if (state) {
                    this.choose(store);
                } else {
                    this.findIndexStore(store.id, store);
                }
            }
        },
        findStore(id) {
            return this.stores.find((item) => {
                return item.id === id;
            })
        },
        findIndexStore(id) {
            const storeIndex = this.choosedStores.findIndex((item) => {
                return item.id === id;
            })

            if (storeIndex !== -1) {
                this.cancelChoose(id);
            }
        },
    }
}
</script>

<style scoped lang="scss">
.stores-list {
    position: relative;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-bottom: 82px;
    width: calc(100% + 20px);
    margin-left: -20px;

    @media screen and (max-width: 767px) {
        justify-content: center;
        width: 100%;
        margin-left: 0;
    }
}
</style>
