import Base from "../../components/Base/index.vue";
import {mapState} from 'vuex'
export default {
    components: {
        Base
    },
    data(){
        return {
            loading: {
                estoque_baixo: false,
                estoque_produtos: false,
            }
        }
    },
    mounted() {
        this.$root.$on("bv::collapse::state", async (id, state) => {
            if (state) {
                if (id === "estoque-baixo") {
                    try{
                        this.loading.estoque_baixo = true;
                        await this.$store.dispatch("estoque_baixo")
                    }
                    catch(error){
                        console.error(error)
                    }
                    finally{
                        this.loading.estoque_baixo = false;
                    }
                }
                else if (id === "estoque-produtos") {
                    try{
                        this.loading.estoque_produtos = true;
                        await this.$store.dispatch("estoque")
                        console.log(this.estoque)
                    }
                    catch(error){
                        console.error(error)
                    }
                    finally{
                        this.loading.estoque_produtos = false;
                    }
                    
                }
            }

        });
    },
    computed: {
        ...mapState({
            estoque_baixo: (state) => state.estoque.estoque_baixo,
            estoque: (state) => state.estoque.estoque
        })
    }
};