<template>
    <div class="select-country">
<!--        <select-params-->
<!--            :options="['United Kingdom']"-->
<!--            :placeHolder="'Choose country'"-->
<!--            :errorMessage="'*Choose country'"-->
<!--            :disabled="false"-->
<!--            :preselectValue="'United Kingdom'"-->
<!--            :searchable="false"-->
<!--        />-->
        <div class="select-container">
            <v-select
                :clearable="false"
                :placeholder="'Choose country'"
                :options="['United Kingdom']"
                :value="'United Kingdom'"
                :disabled="false"
                :searchable="false"
            />
        </div>
    </div>
</template>

<script>
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';
    export default {
        name: "SelectCountry",
        components: {
            'v-select':  vSelect,
        },
    }
</script>

<style scoped lang="scss">
    .select-country {
        max-width: 480px;
        z-index: 0;
        margin-bottom: 41px;
    }
</style>
