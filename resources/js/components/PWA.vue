<template>
    <div class="pwa" v-if="route_path === '/' && supported && !installed && !closed && gdprAccepted">
        <div class="pwa__relative">
            <h4 v-text="'The Hot Meal - Meal Planner'" />
            <a class="pwa__relative__close" @click="close()">×</a>
            <p class="pwa__relative__caption__text" v-text="'Get our free app. It won\'t take up space on your phone.'" />
            <button
                class="button btn__w--240 install-pwa" style="width: 100% !important; max-width: unset; padding: 8px 0 8px;"
                @click="install()"
                v-text="'Install'"
            />
        </div>
        <div class="pwa__mobile">
            <a class="pwa__mobile__close" @click="close()">×</a>
            <img src="/icons/apple-icon-60x60-dunplab-manifest-15727.png" alt="">
            <p class="pwa__mobile__caption__text" v-text="'Get our free app. It won\'t take up space on your phone.'" />
            <button
                class="button install-pwa"
                @click="install()"
                v-text="'Install'"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import store from '../../store'
export default {
    computed: {
        installed: {
            get() {
                return this.$store.state.publicUi.pwa
            }
        },
        supported: {
            get() {
                return this.$store.state.publicUi.pwa_support
            }
        },
        gdprAccepted: {
            get() {
                return this.$store.state.publicUi.gdpr
            }
        }
    },
    methods: {
        install() {
            this.defferedPrompt.prompt();

            this.defferedPrompt.userChoice.then(choice => {
                if(choice.outcome === 'accepted'){
                    store.commit('publicUi/pwaInstalled')
                }
                this.defferedPrompt = null;
            })
        },
        close() {
            this.closed = true;
        }
    },
    created() {
        window.addEventListener('beforeinstallprompt', event => {
            event.preventDefault();
            this.defferedPrompt = event
            store.commit('publicUi/pwaSupported')
            store.commit('publicUi/pwaNotInstalled')
        });
    },
    data() {
        return {
            closed: false,
            route_path: this.$route.path,
        }
    },
};
</script>

<style lang="scss" scoped>
@keyframes slideInFromTop {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideInFromLeft {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes wait {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(-100%);
        opacity: 0;
    }
}

.pwa {
    top: 0;
    background: white;
    width: 100%;
    padding: 5px 10px;
    z-index: 20;
    position: fixed;
    box-shadow: 0 0 10px 0px #00000066;

    animation: 3s ease-out 0s 1 wait, 0.21s ease-out 3s 1 slideInFromTop;

    &__mobile {
        display: flex;
        align-items: center;

        .install-pwa {
            width: 130px !important;
            margin-right: 10px;
            min-width: 80px !important;
        }

        h4 {
            font-weight: 600;
        }

        p {
            font-size: 16px;
            line-height: 24px;
            margin: 0 10px;
        }

        &__caption {
            &__text {
                margin: 1rem 0 1rem 0;
                font-weight: 500;
            }
        }

        &__close {
            transition: all 200ms;
            font-size: 30px;
            text-decoration: none;
            color: #333;
            cursor: pointer;
            margin-right: 10px;

            &:hover {
                color: var(--primary);
            }
        }

        &__agree {
            border: 1px solid var(--black);
            height: 48px;
            line-height: 48px;
            transition: background-color 250ms;
            cursor: pointer;

            &:hover {
                background-color: var(--white);
            }
        }
    }

    &__relative {
        display: none;
    }
}

@media all and(max-width: 380px) {
    .pwa__mobile {
        img {
            width: 50px;
        }

        p {
            font-size: 15px;
            line-height: 20px;
        }

        .install-pwa {
            min-width: 65px !important;
            font-size: 15px;
        }
    }
}

@media all and(min-width: 600px) {
    .pwa {
        bottom: 1rem;
        left: 1rem;
        top: auto;
        width: calc(100% - 2rem);
        max-width: 475px;
        border-radius: 10px;
        padding: 24px;

        animation: 3s ease-out 0s 1 wait, 0.21s ease-out 3s 1 slideInFromLeft;

        &__relative {
            display: block;

            h4 {
                font-weight: 600;
            }

            p {
                font-size: 16px;
                line-height: 24px;
            }

            &__caption {
                &__text {
                    margin: 1rem 0 1rem 0;
                    font-weight: 500;
                }
            }

            &__close {
                position: absolute;
                top: 17px;
                right: 24px;
                transition: all 200ms;
                font-size: 30px;
                text-decoration: none;
                color: #333;
                cursor: pointer;

                &:hover {
                    color: var(--primary);
                }
            }

            &__agree {
                border: 1px solid var(--black);
                height: 48px;
                line-height: 48px;
                transition: background-color 250ms;
                cursor: pointer;

                &:hover {
                    background-color: var(--white);
                }
            }
        }

        &__mobile {
            display: none;
        }
    }
}
</style>
