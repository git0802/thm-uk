<template>
    <div class="gdpr" v-if="!accepted">
        <div class="relative">
            <h4 v-text="'Cookie Policy'" />
            <p class="gdpr__caption__text" v-text="'This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies.'" />
            <button
                class="button btn__w--240" style="width: 100% !important; max-width: unset; padding: 8px 0 8px;"
                @click="$store.commit('publicUi/gdprAccepted')"
                v-text="'I agree'"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    computed: {
        accepted: {
            get() {
                return this.$store.state.publicUi.gdpr
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.gdpr {
    bottom: 0;
    left: 0;
    background: white;
    width: 100%;
    padding: 24px;
    z-index: 20;
    position: fixed;
    box-shadow: 0 0 10px 0px #00000066;
    border-radius: 10px;

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

@media all and(min-width: 600px) {
    .gdpr {
        bottom: 1rem;
        left: 1rem;
        width: calc(100% - 2rem);
        max-width: 475px;
    }
}
</style>
