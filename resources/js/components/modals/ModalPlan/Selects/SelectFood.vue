<template>
    <div class="select-food">
        <div class="select-food__header">
            <h3>Select and drag your foods into your meal planner</h3>
            <button-add-item
                :title="'Add custom food'"
                @click="actionsModal({
                    name: 'modalFood',
                    action: 'open'
                }), clearMessages()"
            />
        </div>
        <!--TODO : Why two emits?-->
        <search-params
            @showMessage="showMessage = true"
            @update="showMessage = false"
        />
        <div class="select-food__message">
            <warning-message v-if="showMessage"
                :message="'You already choose this product'"
            />
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from "vuex";
    import ButtonAddItem from "../../../buttons/ButtonAddItem";
    import SearchParams from "../../../searchs/SearchParams";
    import WarningMessage from "../../../messages/WarningMessage";

    export default {
        components: {
            ButtonAddItem,
            SearchParams,
            WarningMessage
        },
        data() {
            return {
                showMessage: false,
            }
        },
        computed: {
            ...mapGetters({
                store: 'stores/getSelectedStore',
            })
        },
        methods: {
            ...mapActions({
                actionsModal: 'modals/actionsModal',
                clearMessages: 'food/clearMessages'
            }),

            /*updateWarning() {
                if (this.showMessage) {
                    this.showMessage = false;
                }
            },*/
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../../sass/mixins';

    .select-food {
        display: flex;
        flex-direction: column;

        h3 {
            @include head-20-font;
        }

        &__header {
            @include head-20-font;

            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        &__message {
            min-height: 36px;
            padding: 8px;

            .warning-message {
                justify-content: start;
            }
        }
    }
</style>


