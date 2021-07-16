import Base from "../../components/Base/index.vue";
import FormProduto from '../../components/Produtos/form.vue'
import { mapState } from 'vuex'
export default {
    components: {
        Base,
        FormProduto
    },
    data () {
      return {
        isBusy: false,
        perPage: 15,
        currentPage: 1,
      }
    },
    methods: {
        async getData(ctx){
            try {
                this.isBusy = true
                await this.$store.dispatch('listProduto',{
                    per_page: ctx.perPage,
                    page: ctx.currentPage
                })
                this.currentPage = this.dataProdutos.current_page
                return this.dataProdutos.data.map((item) => {
                    return {
                        id: item.id,
                        nome: item.nome,
                        sku: item.sku
                    }
                })
            } catch (error) {
                console.error(error)
                return []
            }
            finally{
                  this.isBusy = false
            }
        },
        editar(data){
            this.$root.$emit("open-modal",data)
            this.$bvModal.show('modal-produto')
        },
        async excluir(data){
            try {
                await this.$store.dispatch('deleteProduto',data.id)
                this.$root.$emit('bv::refresh::table', 'tabela_produtos')
                this.$bvToast.toast("Operação realizada com sucesso!", {
                    title: "Sucesso!",
                    variant: "success",
                    solid: true
                });
            } catch (error) {
                this.$bvToast.toast("Erro ao efetivar a operação", {
                    title: "Error!",
                    variant: "danger",
                    solid: true
                });
            }
        },
        adicionar(){
            this.$root.$emit("open-modal",{})
            this.$bvModal.show('modal-produto')
        }
    },
    computed: {
        ...mapState({
            dataProdutos:(state) => state.produtos.data
        })
    },
    mounted() {
        this.getData()
    },
};