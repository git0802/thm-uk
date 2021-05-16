<template>
    <div class="preset-apply"  v-if="preset">
        <button-base :text="'AUTO-FILL FOODS FROM THIS STORE!'" :styles="{'padding': '8px 0 8px 0', 'max-width': '20em'}" @click="applyPreset" />
    </div>
</template>

<script>
import ButtonBase from "./buttons/ButtonBase";
export default {
    name: "ApplyPreset",
    components: {ButtonBase},
    data: function() {
        return {
            preset: null,
        }
    },
    mounted() {
      if(this.$store.state.stores.selectedStore) {
          this.fetchPresetForStore()
      }
    },
    computed: {
        selectedStore() {
            return this.$store.state.stores.selectedStore
        },
        plannerId() {
            return this.$store.state.planner.planner_id
        }
    },
    watch: {
        selectedStore(val) {
            this.fetchPresetForStore()
        },
    },
    methods: {
        async fetchPresetForStore() {
            this.$emit('loading', true)
            try {
                let res = await this.$http.get(`/api/preset/find/store`, {
                    params: {
                        store_id: this.selectedStore.id
                    }
                })
                if(res.data.success === false) {
                    this.preset = null
                } else {
                    this.preset = res.data.data
                }
            } catch(e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$store.commit('snackbar/pushMessage', {
                                message: z,
                                color: "error",
                                timeout: 5000,
                                right: true,
                                bottom: true
                            })
                        })

                    })
                } else if (e.response.data.message) {
                    this.$store.commit('snackbar/pushMessage', {
                        message: e.response.data.message,
                        color: "error",
                        timeout: 5000,
                        right: true,
                        bottom: true
                    })
                }
            }
            this.$emit('loading', false)

        },
        async applyPreset() {
            this.$emit('loading', true)

            try {
                let res = await this.$http.post(`/api/planner/${this.plannerId}/preset/${this.preset.id}`)

                if(res.data.success) {
                    this.$notify({
                        group: 'planner',
                        title: 'Yay!',
                        type: 'success',
                        text:
                            `Product(s) pasted. <b>`
                            + res.data.message.created
                            + '</b> item(s) - added, <b>'
                            + res.data.message.dupes
                            + '</b> item(s) - duplicates, '
                            + res.data.message.deleted
                            + '</b> item(s) - deleted'

                    });


                    await this.$store.dispatch('planner/fetchWeekPlan')
                } else {
                    this.$notify({
                        group: 'planner',
                        title: 'Yay!',
                        type: 'error',
                        text: res.data.message,
                    });

                }

            } catch(e) {
                if (e.response.data.errors) {
                    Object.keys(e.response.data.errors).forEach(i => {
                        e.response.data.errors[i].forEach(z => {
                            this.$notify({
                                group: 'planner',
                                title: 'Yay!',
                                type: 'error',
                                text: z
                            });
                        })
                    })
                } else if (e.response.data.message) {
                    this.$notify({
                        group: 'planner',
                        title: 'Yay!',
                        type: 'error',
                        text: e.response.data.message,
                    });
                }
            }
            this.$emit('loading', false)
        }
    }

}
</script>

<style scoped lang="scss">
.preset-apply {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
</style>
