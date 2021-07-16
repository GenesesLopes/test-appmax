import Base from "../../components/Base/index.vue";
import { mapState } from 'vuex'
export default {
    components: {
        Base
    },
    data () {
      return {
        isBusy: false,
        perPage: 15,
        currentPage: 1,
        optionsPage: [
            1,
            10,
            15,
            30
        ]
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
            console.log(data)
        },
        excluir(data){
            console.log(data)
        },
        adicionar(){
            console.log('add')
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