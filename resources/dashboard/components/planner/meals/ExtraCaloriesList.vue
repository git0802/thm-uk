<template>
  <div>
    <div class="extra-items">
      <app-extra-consumed @change="getExtraCalories"/>
      <app-extra-exercise @change="getExtraCalories"/>
    </div>
    <div class="extra-items" v-if="entries.length > 0">
      <div class="extra-calories">
        <table class="extra-calories-table">
          <thead>
          <tr>
            <th>Calories</th>
            <th>Type</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="entry in entries">
              <td>{{entry.value}}</td>
              <td>{{entry.value > 0 ? 'Extra Food Consumed' : 'Exercise'}}</td>
              <td><a @click="deleteEntry(entry)">Delete</a></td>
            </tr>
          </tbody>
        </table>
      </div>
      <overlay-component v-if="loading" />
    </div>

  </div>
</template>

<script>
import OverlayComponent from '../../../../js/components/overlay/OverlayComponent'
import moment from 'moment'
import {mapGetters} from "vuex";

export default {
  name: "ExtraCaloriesList",
  components: {OverlayComponent},
  data: function () {
    return {
      entries: [],
      loading: false,
    }
  },
  computed: {
    ...mapGetters({
      selected_day: 'planner/getSelectedDay',
      plannerId: 'planner/getPlannerId',
    })
  },
  methods: {
    async getExtraCalories() {

      this.loading = true;
      try {
        let res  = await this.$http.get(` /api/planner/${this.plannerId}/extraCalories`, {
          params: {
            day: moment(this.selected_day, 'YYYY-MM-DD').format('DD-MM-YYYY'),
          }
        });
        this.entries = res.data;
        this.loading  = false;

      } catch (e) {
        let errResponse = e.response;
        Object.values(errResponse.data.errors).forEach(val => {
          val.forEach(nVal => {
            this.$notify({
              group: 'planner',
              title: 'Error!',
              type: 'error',
              text: nVal,
            });
          })
        });
        this.loading = false;
      }
    },
    async deleteEntry(entry) {
      this.loading = true;
      try {
        await this.$http.delete(` /api/planner/${this.plannerId}/extraCalories/${entry.id}`);
        this.$store.dispatch('plannerUi/fetchStats', this.$http);
        await this.getExtraCalories();
        this.loading  = false;

      } catch (e) {
        let errResponse = e.response;
        Object.values(errResponse.data.errors).forEach(val => {
          val.forEach(nVal => {
            this.$notify({
              group: 'planner',
              title: 'Error!',
              type: 'error',
              text: nVal,
            });
          })
        });
        this.loading = false;
      }
    }
  },
  watch: {
    selected_day: async function(newValue, oldValue) {
      await this.getExtraCalories();
    }
  },
  mounted() {
    this.getExtraCalories();
  }
}
</script>

<style scoped lang="scss">
    .extra-items {
      margin: 0 10px;
      display: flex;
      @media screen and (max-width: 600px) {
        flex-direction: column;
      }
    }
    .extra-calories {
      width: 100%;
      padding-top: 1.23rem;

      table {
        width: 100%;
        text-align: left;

        thead {
          font-size: 12px;
          color: #747e8f;
          font-family: Gilroy;
          text-align: left;
          th {
            padding: 10px;
          }
        }

        tbody {
          font-size: 12px;

          tr {
            background-color: #f7f2ff;
            &:nth-child(even) {
                  background-color: #ffffff;
              }

            td {
              padding: 10px;

              &:nth-child(1), &:nth-child(4), &:nth-child(6) {
                color: var(--purple);
              }

              @media screen and (max-width: 1020px) {
                &:nth-child(1) {
                  padding-left: 10px;
                }
                padding: 20px 10px 20px 18px;
              }
            }
          }
        }

      }
    }
</style>