<template>
    <div class="no-copy" id="no-copy" style="overflow: auto;height: 100vh;">
    <app-layout :style="{ 'filter': filterValue }">
            <main class="grocery-store">
                <stepper
                    v-if="router_path == '/store'"
                    class="steps-container__stepper"
                    :currentStep="currentStep"
                    :stepperContent="stepperContent"
                    @choose="chooseStep"
                />
                <div class="grocery-store__wrapper">
                    <div v-if="currentStep == 0" class="grocery-store__main">
                        <div class="grocery-store__wrapper">
                            <div class="user-info">
                                <div class="user-avatar">
                                    <label for="userpic_input">
                                        <user-pic/>
                                        <div class="user-avatar__edit">
                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M13.7625 6.63772L1.23553 6.63773C0.757291 6.63773 0.361328 7.01922 0.361328 7.49997C0.361328 7.98071 0.757291 8.36221 1.23553 8.36221L13.7625 8.3622C14.2408 8.3622 14.6367 7.98071 14.6367 7.49996C14.6367 7.01922 14.2408 6.63772 13.7625 6.63772Z"
                                                    fill="#454545" stroke="#454545" stroke-width="0.5"/>
                                                <path
                                                    d="M8.36133 13.7634L8.36133 1.23644C8.36133 0.758207 7.97984 0.362244 7.49909 0.362244C7.01834 0.362244 6.63685 0.758207 6.63685 1.23644V13.7634C6.63685 14.2417 7.01834 14.6376 7.49909 14.6376C7.97984 14.6376 8.36133 14.2417 8.36133 13.7634Z"
                                                    fill="#454545" stroke="#454545" stroke-width="0.5"/>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                                <p>Welcome <span class="purple">{{ user.name }}</span>, now let's set up your meal plan
                                    for the week!</p>
                            </div>
                            <app-caption
                                :title="'Stores'"
                            />
                            <h2>Choose your grocery store</h2>
                            <select-country/>
                            <div class="grocery-store__message">
                                <store-message/>
                            </div>
                            <stores-list/>
                            <div class="grocery-store__weekly-plan">
                                <button-base
                                    :disabled="!checkIfChoosedStore"
                                    :text="'NEXT STEP'"
                                    @click="nextStep"
                                />
                                <!-- if they want to return the store selection -->
                                <!--:disabled="checkIsMyDihes() && choosedStores.l"-->
                            </div>
                        </div>
                    </div>

                    <div v-if="currentStep == 1">
                        <app-caption class="m-b-16"
                                     :title="'Choose your meal'"
                        />

                        <drag-drop
                            :stores="$store.state.stores.stores"
                        />

                        <div class="meal-planner__save">
                            <button-base
                                :text="'NEXT STEP'"
                                @click="nextStep()"
                            />
                        </div>
                        <div v-if="tryToSave && notValidDays.length > 0" class="meal-planner__validation">
                            <span>
                                Your setup is incorrect. A purple checkmark will be displayed when you correctly fill in the Daily Calorie Goals for:
                                <span v-for="(days, index) in notValidDays">{{days}}<template v-if="notValidDays.length - 1 !== index">;</template>&nbsp;</span>
                            </span>
                        </div>


                    </div>

                    <review-list
                        v-if="currentStep == 2"
                        @next="nextStep"
                        :text="'NEXT STEP'"
                        next
                    />

                    <div v-if="currentStep == 3">
                        <app-caption class="m-b-16"
                                     :title="'Start your free trial'"
                        />
                        <payment-details @next="finishUserSetup"></payment-details>
                    </div>


                </div>
                <overlay-component v-if="isLoading"/>
            </main>
        </app-layout>
        <!--Block for all modal windows on grocery store page-->
        <modal-plan/>
        <modal-store/>
        <modal-food/>
        <modal-dish/>
        <modal-review-list/>
        <modal-weekly-goals/>
        <!--Block for all modal windows on grocery store page-->
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex"
import ModalReviewList from "../components/modals/ModalReviewList/ModalReviewList"
import ModalStore from "../components/modals/ModalStore/ModalStore"
import axios from "axios";
import {Container} from "vue-smooth-dnd";
import UserPic from '../../dashboard/components/user/Userpic'
import DragDrop from "../components/DragDrop";


export default {
    components: {DragDrop, ModalReviewList, ModalStore, Container, UserPic},
    data() {
        return {
            tryToSave: false,
            currentStep: 0,
            router_path: this.$route.path,
            stepperContent: [
                {
                    content: '01',
                },
                {
                    content: '02',
                },
                {
                    content: '03',
                },
                {
                    content: '04',
                }
            ]
        }
    },
    computed: {
        ...mapGetters({
            activeModals: 'modals/getActiveModals',
            choosedStores: 'stores/getChoosedStores',
            user: 'auth/user',
            isLoading: 'stores/getIsBusy',
            validation: 'planner/getValidation',
            notValidDays: 'planner/getNotValidationDays',
            plannerId: 'planner/getPlannerId',
        }),
        filterValue() {
            return this.activeModals.length ? 'blur(4px)' : 'none';
        },
        checkIfChoosedStore() {
            let found;
            for(let i = 0; i < this.choosedStores.length; i++) {
                if (this.choosedStores[i].is_custom === false) {
                    found = true;
                    break;
                }
            }
            return found;
        }
    },
    async mounted() {
        await this.fetchGetInitial()
        this.overrideNavbarStyles()
    },
    beforeDestroy() {
        this.overrideNavbarStyles(true)
    },
    methods: {
        checkIsMyDihes() {
            return this.choosedStores.some((y) => {
                return y.name === 'Add your store';
            });
        },
        overrideNavbarStyles(remove = false) {
            let nav = document.getElementsByClassName('nav-bar')[0];
            if (remove) {
                nav.classList.remove('nav-bar__wrapper--sticky');
            } else {
                nav.classList.add('nav-bar__wrapper--sticky');
            }
        },
        async fetchGetInitial() {
            if (this.router_path == '/store') {
                try {
                    const res = await axios.get(`/api/planner/initial`);
                    this.$store.commit('planner/setCaloriesGoal', res.data.data.calories_goal)
                    this.$store.commit('planner/setGoal', res.data.data.goal)
                } catch (error) {
                    if (error.response.data.success == false) {
                        window.location.href = '/meal-planner/settings'
                    }
                }
            }
        },
        async finishSetup(skip)
        {
            try {
                let res = await this.$http.post(`/api/planner/${this.plannerId}/finish`, {skip_setup: skip})
                console.log(res);
                if(res.data.success) {
                    // this.$notify({
                    //     group: 'planner',
                    //     title: 'Yay!',
                    //     type: 'success',
                    //     text: res.data.message,
                    // });
                    return true
                }
            } catch(e) {
                console.log(e)
                return false;

            }

        },

        async finishUserSetup() {
            let status = await this.finishSetup(false)
            window.location.href = '/meal-planner/planner/'
        },

        async nextStep() {
            if (this.currentStep == 1) {
                if (!this.validation.is_valid) {
                    this.tryToSave = true;
                } else {
                    let status = await this.finishSetup(true)
                    if(status) {
                        this.currentStep++
                        window.scrollTo(0, 0)
                        document.getElementById('no-copy').scrollTo(0, 0)
                    }

                }
            } else {
                if (this.router_path == '/store') {
                    this.currentStep++;
                    window.scrollTo(0, 0)
                    document.getElementById('no-copy').scrollTo(0, 0)
                } else {
                    this.actionsModal({
                        name: 'modalPlan',
                        action: 'open'
                    })
                }
            }
        },
        chooseStep(index) {
            this.currentStep = index;
        },
        ...mapActions({
            actionsModal: 'modals/actionsModal',
        }),
    }
}
</script>

<style lang="scss">
@import '../../sass/_mixins.scss';

.no-copy {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}

.grocery-store {
    position: relative;
    padding: 140px 30px;
    background: var(--white);
    min-height: 100vh;

    @media screen and (max-width: 767px) {
        padding: 90px 20px;
    }

    &__main {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        background: var(--white);
        min-height: 100%;
    }

    &__wrapper {
        width: 100%;
        max-width: 980px;
        margin: 0 auto;

        .user-info {
            display: flex;
            flex-direction: row;
            align-items: center;

            @media screen and (max-width: 480px) {
                flex-direction: column;
                margin-bottom: 40px;
                p {
                    margin-left: 0;
                    text-align: center;
                }
            }

            p {
                @include head-24-font;

                margin-left: 33px;
            }
        }

        .user-avatar {
            position: relative;
            padding: 69px 0px;

            @media screen and (max-width: 480px) {
                padding: 0px 0px 22px 0px;
            }

            &:hover {
                cursor: pointer;
            }

            .userpic__auth, .userpic__notauth {
                width: 114px;
                height: 114px;
            }

            .userpic {
                svg {
                    width: 114px;
                    height: 114px;
                }
            }

            &__edit {
                position: absolute;
                left: 82px;
                bottom: 67px;
                height: 29px;
                width: 29px;
                background: var(--white);
                box-shadow: 0px 1px 6px rgba(0, 0, 0, 0.25);
                border-radius: 50%;
                justify-content: center;
                display: flex;
                align-items: center;

                @media screen and (max-width: 480px) {
                    left: 84px;
                    bottom: 22px;
                }

                &:hover {
                    cursor: pointer;
                }
            }
        }
    }

    h2 {
        @include title-font;

        margin-bottom: 35px;
    }

    &__message {
        display: flex;
        justify-content: center;
        margin-bottom: 35px;
    }

    &__weekly-plan {
        display: flex;
        justify-content: center;

        @media screen and (max-width: 600px) {
            flex-direction: column;
        }
    }
}

.steps-container {
    display: flex;
    flex-direction: column;
    min-height: calc(100% - 126px);
    background: var(--white);
    padding-top: 76px;

    @media screen and (max-width: 980px) {
        min-height: calc(100vh - 126px);
    }

    &__stepper {
        padding: 29px 23px;

        @media screen and (max-width: 600px) {
            padding-top: 2px;
            padding-bottom: 37px;
        }
    }

    &__content {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        padding: 21px 25px;
    }

    &__steps {
        width: 980px;

        @media screen and (max-width: 980px) {
            width: 100%;
        }
    }

    &__current {
        margin-bottom: 46px;

        @media screen and (max-width: 600px) {
            margin-bottom: 36px;
        }
    }
}

.purple {
    color: #B47EFB;
}
</style>
