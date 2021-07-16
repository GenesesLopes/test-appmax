import { required, integer } from "vuelidate/lib/validators";
import { mapState } from 'vuex'
export default {
    data() {
        return {
            form: {
                quantidade: "",
                produto_id: null,
                acao: 'Adicionar'
            },
            loading: false
        };
    },
    methods: {
        async salvar() {
            let validationQuantidade = this.$v.form.quantidade;
            if (validationQuantidade.$invalid) {
                if (validationQuantidade['integer']) {
                    this.$bvToast.toast("Campo de ser um numero", {
                        title: "Erro no campo abaixo",
                        variant: "danger",
                        solid: true
                    });
                } else {
                    this.$bvToast.toast("Campo quantidade obrigatório", {
                        title: "Erro no campo abaixo",
                        variant: "danger",
                        solid: true
                    });
                }

            } else if ((this.form.produto_id == undefined || this.form.produto_id == '') && this.form.acao == 'adição') {
                this.$bvToast.toast("É necessário escolher um produto para essa ação abaixo", {
                    title: "Erro no campo abaixo",
                    variant: "danger",
                    solid: true
                });
            }
            else {

                try {
                    this.loading = true;
                    if (this.form.acao == 'adição') {
                        if ('id' in this.form)
                            delete this.form.id
                        await this.$store.dispatch("estoqueAdd", this.form);
                    }
                    else {
                        await this.$store.dispatch('estoqueBaixa',this.form);
                    } 
                    this.$bvModal.hide('modal-estoque');
                    this.$root.$emit('bv::refresh::table', 'tabela_estoques')
                    this.$bvToast.toast("Operação realizaca com sucesso", {
                        title: "Operação realizada",
                        variant: "success",
                        solid: true
                    });

                } catch (error) {
                    let {response} = error
                    if (response !== undefined) {
                        for (let value in response.data.errors) {
                            if(value == 'quantidade'){
                                response.data.errors[value].map((message) => {
                                    this.$bvToast.toast(message, {
                                        title: "Erro no envio dos dados",
                                        variant: "danger",
                                        solid: true
                                    });
                                })
                            }
                        }
                    }else{
                        this.$bvToast.toast("erro ao inserir informações", {
                            title: "Erro no envio dos dados",
                            variant: "danger",
                            solid: true
                        });
                    }
                } finally {
                    this.loading = false;
                }
            }
        }

    },
    mounted() {
        this.$root.$on("open-modal-estoque", data => {
            this.form.produto_id = data.id;
            this.form.acao = data.acao;
            this.form.quantidade = ""
        });


    },
    computed: {
        ...mapState({
            errors: (state) => state.estoque.errors,
            produtos: state => state.produtos.data.data.map((data) => {
                return {
                    value: data.id,
                    text: data.nome
                }
            })
        })
    },
    validations: {
        form: {
            quantidade: {
                required,
                integer
            }
        }
    }
};