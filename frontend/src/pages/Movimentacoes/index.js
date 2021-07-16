import Base from "../../components/Base/index.vue";
import { mapState } from 'vuex'
import moment from 'moment'
export default {
    components: {
        Base
    },
    data() {
        return {
            start_date: null,
            end_date: null,
            loading: false
        }
    },
    methods: {
        async estoque() {
            try {
                this.loading = true;
                await this.$store.dispatch('relatorio', {
                    start_date: this.start_date,
                    end_date: this.end_date
                })
            } catch (error) {
                let { response } = error
                if (response != undefined) {
                    let dataError = response.data.errors
                    for (let field in dataError) {
                        let message = Array.isArray(dataError[field]) ? dataError[field][0] : dataError[field];
                        this.$bvToast.toast(message, {
                            title: "Error!",
                            variant: "danger",
                            solid: true
                        });
                    }
                } else {
                    this.$bvToast.toast("Erro ao efetivar a operação", {
                        title: "Error!",
                        variant: "danger",
                        solid: true
                    });
                }
            } finally {
                this.loading = false;
            }

        }
    },
    computed: {
        ...mapState({
            relatorio: (state) => state.estoque.relatorio
        })
    },
    filters: {
        moment: function(date){
            return moment(date).format('DD-MM-YYYY')
        }
    }
}