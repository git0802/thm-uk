<template>
    <div class="select-store">
        <v-select
            class="meal-setup__select"
            :options="stores_list"
            v-model="selectedStore"
            label="name"
            :searchable="false"
        >
            <template v-slot:option="{ name }">
                {{ name }}
            </template>
        </v-select>
    </div>
</template>

<script>
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';
import {mapActions} from "vuex";
import findIndex from 'lodash/findIndex'

export default {
    props: {
        stores: {
            type: Array,
            required: true,
        }
    },
    components: {
        'v-select': vSelect,
    },
    data() {
        return {
            stores_list: [...this.stores]
        }
    },
    mounted() {
        this.moveArrayItemToNewIndex()


        if(window.location.pathname === '/store' || this.selectedStore.id === 0){
            this.$store.commit('stores/setSelectedStore', this.stores_list[0]);
        } else if(this.selectedStore.id === 0 || Object.keys(this.selectedStore).length === 0){
            this.$store.commit('stores/setSelectedStore', this.getStores[0]);
        }
    },
    computed: {
        getStores(){
            return this.$store.getters['stores/getStores']
        },
        choosedStores() {
            return this.$store.getters['stores/getChoosedStores']
        },
        selectedStore: {
            get() {
                return this.$store.state.stores.selectedStore
            },
            set(value) {
                if (value.id === 0) {
                    this.actionsModal({
                        name: 'modalStore',
                        action: 'open'
                    });
                } else {
                    this.$store.commit('stores/setSelectedStore', value)
                    this.pushBackSelectedStore(value)
                }
            }
        }
    },
    watch: {
        stores(newValue, oldValue) {
            this.stores_list = this.stores
            if(window.location.pathname === '/store' || this.selectedStore.id === 0){
                this.$store.commit('stores/setSelectedStore', this.stores[0]);
            } else if(this.selectedStore.id === 0 || Object.keys(this.selectedStore).length === 0){
                this.$store.commit('stores/setSelectedStore', this.getStores[0]);
            }
        }
    },
    methods: {
        ...mapActions({
            actionsModal: 'modals/actionsModal',
        }),
        moveArrayItemToNewIndex() {
            this.stores_list = this.stores_list.filter((item) => {
                return findIndex(this.choosedStores, (o) => { return o.name == item.name }) === -1
           })
            this.stores_list.unshift(...this.choosedStores)
        },
        pushBackSelectedStore(store) {
            let storeIndex = undefined
            let storeInList = this.choosedStores.find((e, index) => {
                if(e.id === store.id) {
                    storeIndex = index
                    return true
                } else {
                    return false
                }
            })
            if(!storeInList) {
                this.$store.commit('stores/choose', store)
            } else {
                this.$store.commit('stores/cancelChoose', store.id)
                this.$store.commit('stores/choose', store)
            }
        }
    },
}
</script>

