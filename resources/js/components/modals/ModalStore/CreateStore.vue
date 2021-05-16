<template>
    <div class="create-store">
        <h2>Add new store</h2>
        <input-store-name
            @validation="validationParam"
        />
        <div class="create-store__message">
            <warning-message v-if="showMessage"
                :message="errors.length ? errors[0] : ''"
            />
<!--            <complete-message v-if="showSuccessMessage"-->
<!--                :message="successMessage"-->
<!--            />-->
        </div>
        <button-add-image class="m-b-50"
            :text="'Store image'"
            @saveImage="saveImage"
        >
            <icon-store/>
        </button-add-image>
        <div class="create-store__save">
            <button-save
                :isDisabled="isDisabledSaveButton || isBusy"
                @click="createStore({
                    name: storeName,
                    image: storeImage
                })"
            />
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from "vuex";
    import InputStoreName from "../../inputs/inputsStore/InputStoreName";
    import WarningMessage from "../../messages/WarningMessage";
    import CompleteMessage from "../../messages/CompleteMessage";
    import ButtonAddImage from "../../buttons/ButtonAddImage";
    import IconStore from "../../svg/IconStore"
    import ButtonSave from "../../buttons/ButtonSave";
    import ButtonCloseModal from "../../buttons/ButtonCloseModal";

    export default {
        components: {
            InputStoreName,
            WarningMessage,
            CompleteMessage,
            ButtonAddImage,
            IconStore,
            ButtonSave,
            ButtonCloseModal
        },
        data() {
            return {
                isDisabledSaveButton: true,
                storeName: '',
                storeImage: '',
                errorMessage: ''
            }
        },
        computed: {
            ...mapGetters({
                errors: 'stores/getErrors',
                isBusy: 'stores/getIsBusy',
                successMessage: 'stores/getSuccessMessage',
            }),

            showMessage() {
                return !!this.errors.length;
            },
            showSuccessMessage() {
                return this.successMessage;
            }
        },
        methods: {
            ...mapMutations({
                clearErrors: 'stores/clearErrors',
                setSuccessMessage: 'stores/setSuccessMessage'
            }),
            ...mapActions({
                createStore: 'stores/createStore',
            }),

            validationParam(name, state, value) {
                if (this.errors.length) {
                    this.clearErrors();
                }

                if (this.successMessage) {
                    this.setSuccessMessage('');
                }

                if (state) {
                    this.isDisabledSaveButton = false;
                    this.storeName = value;
                } else {
                    this.isDisabledSaveButton = true;
                    this.storeName = '';
                }
            },
            saveImage(file) {
                //Save file with image
                this.storeImage = file;
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '../../../../sass/_mixins.scss';

    .create-store {
        h2 {
            @include title-font;

            margin-bottom: 29px;
        }

        &__message {
            min-height: 36px;
            padding: 8px;

            .warning-message, .complete-message {
                justify-content: start;
            }
        }

        &__add {
            margin-bottom: 50px;
        }

        &__save {
            display: flex;
            flex-direction: row;
            justify-content: center;

            @media screen and (max-width: 600px) {
                flex-direction: column;
            }
        }
    }

    .m-b-50 {
        margin-bottom: 50px;
    }
</style>
