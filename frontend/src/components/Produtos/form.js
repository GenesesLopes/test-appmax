import { required } from "vuelidate/lib/validators";
import { mapState } from 'vuex'
export default {
    data() {
        return {
            form: {
                nome: "",
                sku: "",
                id: null
            },
            loading: false
        };
    },
    methods: {
        async salvar() {
            let validationNome = this.$v.form.nome;
            let validationSku = this.$v.form.sku;

            if (validationNome.$invalid) {
                this.$bvToast.toast("Campo nome obrigatório", {
                    title: "Erro no campo abaixo",
                    variant: "danger",
                    solid: true
                });
            } else if (validationSku.$invalid) {
                this.$bvToast.toast("Campo sku obrigatório", {
                    title: "Erro no campo abaixo",
                    variant: "danger",
                    solid: true
                });
            } else {

                try {
                    this.loading = true;
                    if (this.form.id === null) {
                        delete this.form.id;
                        await this.$store.dispatch("createProduto", this.form);
                    }else{
                        await this.$store.dispatch("updateProduto", this.form);
                    }
                    this.$bvModal.hide('modal-produto');
                    this.$root.$emit('bv::refresh::table', 'tabela_produtos')
                    this.$bvToast.toast("Operação realizaca com sucesso", {
                        title: "Operação realizada",
                        variant: "success",
                        solid: true
                    });

                } catch (error) {
                    console.error(error);
                } finally {
                    if (this.errors != {}) {
                        for (let value in this.errors) {
                            if (Array.isArray(this.errors[value])) {
                                this.errors[value].map((message) => {
                                    this.$bvToast.toast(message, {
                                        title: "Erro no envio dos dados",
                                        variant: "danger",
                                        solid: true
                                    });
                                })
                            } else {
                                this.$bvToast.toast(this.errors[value], {
                                    title: "Erro no envio dos dados",
                                    variant: "danger",
                                    solid: true
                                });
                            }
                        }
                    }
                    this.loading = false;
                }
            }
        }
    },
    mounted() {
        this.$root.$on("open-modal", data => {
            this.form.nome = data.nome;
            this.form.sku = data.sku;
            this.form.id = data.id;
        });
    },
    computed: {
        ...mapState({
            errors: (state) => state.produtos.erros
        })
    },
    validations: {
        form: {
            nome: {
                required
            },
            sku: {
                required
            }
        }
    }
};