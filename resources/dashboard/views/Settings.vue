<template>
    <div class="container__main__slot">
        <div class="container__head top-head top-head--sticky">
            <app-left-drawer-burger/>
            <div class="title">
                Account Settings
            </div>
        </div>
        <div class="container__content">
            <div class="max-w-30 form-blocks">
                <change-name/>
                <change-email/>
                <change-phone/>
                <change-personal-parameters />
                <ChangeWantedSpam/>
                <change-privacy-and-security/>
                <subscription />
            </div>
        </div>
    </div>

</template>

<script>
import ChangeWantedSpam from "../components/forms/ChangeWantedSpam";
export default {
name: "Settings",
    components: {ChangeWantedSpam},
}
</script>

<style scoped>
.form-blocks div {
    margin: 2rem 0;
}
</style>
