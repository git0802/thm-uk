<template>
    <div class="add-image">
        <div class="add-image__picture" :class="{ 'hasImage': hasImage }">
            <div v-if="!hasImage && !originalImage">
                <slot/>
            </div>
            <img v-else alt="itemImage" class="add-image__image"
                :src="image || originalImage"
            >
        </div>
        <h3>{{ text }}</h3>
        <image-uploader class="add-image__button"
            outputFormat="file"
            @input="setImage"
            @onComplete="uploadComplete"
        >
            <label for="fileInput" slot="upload-label">
                <figure class="add-image__figure">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.7635 6.63769L1.2365 6.6377C0.758268 6.6377 0.362305 7.01919 0.362305 7.49994C0.362305 7.98068 0.758268 8.36218 1.2365 8.36218L13.7635 8.36217C14.2417 8.36217 14.6377 7.98068 14.6377 7.49993C14.6377 7.01919 14.2417 6.63769 13.7635 6.63769Z" fill="#454545" stroke="#454545" stroke-width="0.5"/>
                        <path d="M8.3623 13.7635L8.3623 1.2365C8.3623 0.758268 7.98081 0.362305 7.50006 0.362305C7.01932 0.362305 6.63782 0.758268 6.63782 1.2365V13.7635C6.63782 14.2417 7.01932 14.6377 7.50006 14.6377C7.98081 14.6377 8.3623 14.2417 8.3623 13.7635Z" fill="#454545" stroke="#454545" stroke-width="0.5"/>
                    </svg>
                </figure>
            </label>
        </image-uploader>
    </div>
</template>

<script>
    import ImageUploader from 'vue-image-upload-resize'

    export default {
        name: "ButtonAddStoreImage",
        props: {
            text: {
                type: String,
                required: true,
                default: 'Add image'
            },
            originalImage: {
            }
        },
        components: {
            ImageUploader
        },
        data() {
            return {
                hasImage: false,
                image: ''
            }
        },
        methods: {
            setImage(imageFile) {
                this.$emit('saveImage', imageFile);
            },
            uploadComplete() {
                //Added nextTick, because he does not have time to change src
                this.$nextTick(() => {
                    this.image = document.getElementsByClassName('img-preview')[0].getAttribute('src');
                    this.hasImage = true;
                });
            }
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/_mixins.scss';

    .add-image {
        position: relative;
        display: flex;
        flex-direction: row;
        align-items: center;

        h3 {
            @include head-24-font;

            /*white-space: nowrap;*/
        }

        #fileInput {
            display: none;
        }

        &__picture {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 114px;
            min-width: 114px;
            height: 114px;
            background: var(--grey-4);
            border-radius: 50%;
            margin-right: 26px;
            @media screen and (max-width: 767px) {
                width: 80px;
                min-width: 80px;
                height: 80px;
                margin-right: 20px;
            }
        }

        &__image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        &__figure {
            height: 15px;
        }

        .hasImage {
            border: 2px solid var(--primary);
            background: transparent;
            box-shadow: 0px 4.19251px 14.6738px rgba(0, 0, 0, 0.07);
        }

        &__button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 29px;
            height: 29px;
            background: var(--white);
            box-shadow: 0px 1px 6px rgba(0, 0, 0, 0.25);
            border-radius: 50%;
            position: absolute;
            left: 85px;
            top: 85px;

            @media screen and (max-width: 767px) {
                left: 60px;
                top: 60px;
            }

            &:hover, label {
                cursor: pointer;
                transition: 400ms;
            }

            img {
                display: none;
            }
        }
    }
</style>
