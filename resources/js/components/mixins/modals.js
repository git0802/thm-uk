import { mapGetters } from "vuex";

export const modals = {
    data() {
        return {
            zIndex: 0,
            filterValue: 'none'
        }
    },
    computed: {
        ...mapGetters({
            activeModals: 'modals/getActiveModals',
        }),
    },
    methods: {
        findActiveModal(name) {
            const result = this.activeModals.find((item) => {
                return item.name === name;
            });

            if (result) {
                this.zIndex = result.zIndex;
                this.setFilter(name);
            }

            return !!result;
        },
        setFilter(name) {
            const index = this.activeModals.findIndex((item) => {
                return item.name === name;
            });

            this.filterValue = index !== this.activeModals.length - 1 ? 'blur(4px)' : 'none';
        }
    }
}
