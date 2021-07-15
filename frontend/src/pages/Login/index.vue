<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">App Max</h5>
                        <p class="text-center">
                            Sistema para controle de estoque
                        </p>
                        <form class="form-signin" @submit.prevent="handleLogin">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label for="inputEmail"
                                        >Email de acesso</label
                                    >

                                    <input
                                        type="email"
                                        class="form-control"
                                        placeholder="Email de acesso"
                                        v-model="form.email"
                                        autofocus
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label-group">
                                    <label for="inputPassword">Senha</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        placeholder="Senha"
                                        v-model="form.senha"
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <button
                                    class="btn btn-lg btn-primary btn-block text-uppercase"
                                    type="submit"
                                    :disabled="loading"
                                >
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {
    required,
    email,
    minLength,
    maxLength
} from "vuelidate/lib/validators";
import { mapState } from "vuex";
export default {
    mounted() {
        document.querySelector("body").style.background = "#acbbcc80";
    },
    data() {
        return {
            form: {
                email: null,
                senha: null
            },
            loading: false
        };
    },
    methods: {
        async handleLogin() {
            let validationSenha = this.$v.form.senha;
            let validationEmail = this.$v.form.email;

            if (validationSenha.$invalid) {
                if (
                    validationSenha["maxLength"] ||
                    validationSenha["minLength"]
                ) {
                    this.$bvToast.toast(
                        "Senha deve conter entre 6 a 8 digitos",
                        {
                            title: "Erro no campo abaixo",
                            variant: "danger",
                            solid: true
                        }
                    );
                } else {
                    this.$bvToast.toast("Senha obrigatorio", {
                        title: "Erro no campo abaixo",
                        variant: "danger",
                        solid: true
                    });
                }
            } else if (validationEmail.$invalid) {
                if (validationEmail["email"]) {
                    this.$bvToast.toast("Email Invalido", {
                        title: "Erro no campo abaixo",
                        variant: "danger",
                        solid: true
                    });
                } else {
                    this.$bvToast.toast("Email Obrigatório", {
                        title: "Erro no campo abaixo",
                        variant: "danger",
                        solid: true
                    });
                }
            } else {
                this.$store.commit("SET_ERROR_USER", {
                    login: []
                });
                await this.$store.dispatch("login", this.form);

                try {
                    this.loading = true;
                    if (this.error.length) {
                        this.error.map(item => {
                            this.$bvToast.toast(item, {
                                title: "Erro no Login",
                                variant: "danger",
                                solid: true
                            });
                        });
                    } else {
                        // this.$router.push("/admin");
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    this.loading = false;
                }
            }
        }
    },
    validations: {
        form: {
            email: {
                required,
                email
            },
            senha: {
                required,
                minLength: minLength(6),
                maxLength: maxLength(8)
            }
        }
    },
    computed: {
        ...mapState({
            error: state => state.user.error.login
        })
    }
};
</script>
