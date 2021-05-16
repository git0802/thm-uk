<template>
    <div v-if="id !== 0" class="store-container">
        <button :disabled="name === 'Grouped Food'" @click="chooseStore(id)" class="store-container__button"
                :class="{ 'choosed': isChoosedStore}">
            <img :src="image" alt="storeAvatar" class="store-container__image">
        </button>
        <img v-if="isCustom" :src="typeIcon" alt="typeIcon" class="store-container__icon">
        <div @click="deleteStore(id)" v-if="isCustom" class="store-container__icon--white-bg">
            <div>
                <svg width="14" height="16" viewBox="0 0 14 16" fill="#DF2C65" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.13764 5.51644L5.31334 5.4705L5.56824 12.8856L4.39254 12.9314L4.13764 5.51644Z"/>
                    <path d="M6.41177 5.49347H7.58823V12.9086H6.41177V5.49347Z"/>
                    <path d="M8.43197 12.8854L8.68686 5.47028L9.86256 5.51625L9.60764 12.9314L8.43197 12.8854Z"/>
                    <path
                        d="M14 2.40209V3.65534H12.7741L11.8016 15.4283C11.7749 15.7518 11.5206 16 11.2157 16H2.80412C2.49921 16 2.2447 15.7517 2.21824 15.4281L1.24569 3.65534H0V2.40209H14ZM3.34292 14.7467H10.6771L11.5931 3.65534H2.42664L3.34292 14.7467Z"/>
                    <path
                        d="M5.07843 0H8.92157C9.46216 0 9.90196 0.4685 9.90196 1.04437V3.02872H8.7255V1.25325H5.2745V3.02872H4.09804V1.04437C4.09804 0.4685 4.53784 0 5.07843 0Z"/>
                </svg>
            </div>

        </div>
        <div class="store-container__title">
            <icon-selected
                v-if="isChoosedStore"
                class="m-r-8"
            />
            {{ name }}
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    props: {
        id: {
            type: Number,
            required: true,
            default: 0
        },
        image: {
            type: String,
            required: true,
            default: ''
        },
        name: {
            type: String,
            required: true,
            default: 'Store name'
        },
        isCustom: {
            type: Boolean,
            required: true,
            default: false
        }
    },
    data() {
        return {
            isChoosedStore: false,
        }
    },
    computed: {
        ...mapGetters({
            choosedStores: 'stores/getChoosedStores',
            stores: 'stores/getStores',
        }),
        typeIcon() {
            return this.isCustom ? '/images/user-icon-bg-shadow.png' : '/images/lock-icon-bg-shadow.png'
        }
    },
    mounted() {
        this.checkIsChoosed();
    },
    watch: {
        choosedStores() {
            this.checkIsChoosed();
        }
    },
    methods: {
        ...mapActions({
            deleteStore: 'stores/deleteStore'
        }),
        checkIsChoosed() {
            for (let i = 0; i < this.choosedStores.length; i++) {
                if (this.id === this.choosedStores[i].id) {
                    this.isChoosedStore = true;
                }
            }
        },
        chooseStore(id) {
            let store = false
            for (let i = 0; i < this.choosedStores.length; i++) {
                if (this.choosedStores[i].id === id) {
                    store = true
                }
            }
            if (!store) {
                this.isChoosedStore = true;
                this.$emit('choosed', this.id, this.isChoosedStore);
            } else {
                this.isChoosedStore = false;
                this.$emit('choosed', this.id, this.isChoosedStore);
            }
        }
    }
}
</script>

<style scoped lang="scss">
@import '../../../sass/mixins';

.store-container {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    max-width: 130px;
    margin-left: 20px;
    margin-bottom: 20px;

    @media screen and (max-width: 767px) {
        margin-left: 10px;
        margin-right: 10px;
        max-width: 110px;
    }

    &__button {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        width: 130px;
        height: 130px;
        background: var(--white);
        border: 1px solid rgba(178, 125, 255, 0.3);
        border-radius: 50%;
        box-shadow: 0px 4.19251px 14.6738px rgba(0, 0, 0, 0.07);
        margin-bottom: 6px;

        &:hover {
            border: 2px solid var(--purple);
            transition: 400ms;
            cursor: pointer;
        }

        @media screen and (max-width: 767px) {
            width: 110px;
            height: 110px;
        }
    }

    .choosed {
        border: 2px solid var(--purple);
    }

    &__image {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: contain;
        @media screen and (max-width: 767px) {
            width: 90px;
            height: 90px;
        }
    }

    &__icon {
        position: absolute;
        top: 3px;
        right: 3px;
        width: 35px;
        height: 35px;

        @media screen and (max-width: 767px) {
            top: 1px;
            right: 1px;
        }
    }

    &__icon--white-bg {
        cursor: pointer;
        position: absolute;
        top: 93px;
        right: 7px;

        div {
            box-shadow: 0 0 7px -3px black;
            border-radius: 50%;
            height: 25px;
            width: 25px;
            background: #fff;
            align-items: center;
            display: flex;
            justify-content: center;
        }

        @media screen and (max-width: 767px) {
            top: 80px;
            right: 6px;
        }
    }

    &__title {
        @include head-16-font;

        color: var(--purple);
        text-align: center;

        svg {
            display: inline-block;
            min-width: 17px;
        }
    }
}

.m-r-8 {
    margin-right: 8px;
}
</style>
