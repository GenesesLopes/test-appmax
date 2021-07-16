import Base from "../../components/Base/index.vue";
import FormEstoque from '../../components/Estoque/form.vue'
import { mapState } from 'vuex'
export default {
    components: {
        Base,
        FormEstoque
    },
    data () {
      return {
        isBusy: false,
      }
    },
    methods: {
        async getData(){
            try {
                this.isBusy = true
                await this.$store.dispatch('estoque')
                return this.dataEstoque
            } catch (error) {
                console.error(error)
                return []
            }
            finally{
                  this.isBusy = false
            }
        },
        async baixa(data){
            await this.$store.dispatch('listProduto')
            this.$root.$emit("open-modal-estoque",{
                ...data,
                acao: 'remocao'
            })
            this.$bvModal.show('modal-estoque')
            // try {
            //     await this.$store.dispatch('deleteProduto',data.id)
            //     this.$root.$emit('bv::refresh::table', 'tabela_produtos')
            //     this.$bvToast.toast("Operação realizada com sucesso!", {
            //         title: "Sucesso!",
            //         variant: "success",
            //         solid: true
            //     });
            // } catch (error) {
            //     this.$bvToast.toast("Erro ao efetivar a operação", {
            //         title: "Error!",
            //         variant: "danger",
            //         solid: true
            //     });
            // }
        },
        async adicionar(){
            await this.$store.dispatch('listProduto')
            this.$root.$emit("open-modal-estoque",{
                acao: 'adição'
            })
            this.$bvModal.show('modal-estoque')
        }
    },
    computed: {
        ...mapState({
            // dataProduto:(state) => state.produtos.data,
            dataEstoque: (state) => state.estoque.estoque
        })
    }
};