<template>
    <div class="userpic">
        <div v-if="user.avatar">
            <div
                @click="openFileInput"
                v-bind:style="{ background: `url('${user.avatar}') center/cover` }"
                class="userpic__auth"
            >
            </div>
        </div>
        <div class="userpic__notauth">
            <div @click="openFileInput"
                 class="userpic__female"
            >
                <icon-female/>
            </div>
        </div>
        <input
            ref="fileInput"
            type="file"
            @change="onFileChange"
            accept="image/*"
        >
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import Cookies from 'js-cookie';

export default {
    name: "Userpic",
    data() {
        return {
            image: ''
        }
    },
    computed: {
        ...mapGetters({
            user: 'auth/user'
        }),
    },
    methods: {
        openFileInput() {
            this.$refs.fileInput.click()
        },
        async checkToken() {
            const token = Cookies.get('token')
            await this.$store.dispatch('auth/checkToken', token)
                .then((res) => {
                    this.$store.dispatch('auth/storeUserData', res.user)
                })
        },
        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
            this.image = files[0]

            let formData = new FormData();
            formData.append('image', this.image);
            window.axios.post('/api/user/avatar', formData)
                .then(() => {
                    this.checkToken();
                })
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    }
}
</script>

<style scoped lang="scss">
input[type='file'] {
    display: none;
}

.userpic {
    &__auth {
        height: 80px;
        width: 80px;
        background-size: cover!important;
        border-radius: 100%;
        cursor: pointer;
    }
}
</style>
