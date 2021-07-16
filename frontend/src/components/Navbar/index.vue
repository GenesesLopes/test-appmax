<template>
    <div>
        <b-navbar toggleable="sm" type="dark" variant="dark">
            <b-navbar-toggle target="nav-text-collapse"></b-navbar-toggle>

            <router-link :to="{ name: 'dashboard' }">
                <b-navbar-brand>Estoque App Max</b-navbar-brand>
            </router-link>

            <b-collapse id="nav-text-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item href="#" @click.prevent="produtos"
                        >Produtos</b-nav-item
                    >
                    <b-nav-item-dropdown>
                        <template #button-content>
                            <em>Estoques</em>
                        </template>
                        <b-dropdown-item href="#" @click.prevent="getEstoque"
                            >Listagem</b-dropdown-item
                        >
                        <b-dropdown-item href="#" @click.prevent="movimentacoes"
                            >Historico de movimentações</b-dropdown-item
                        >
                    </b-nav-item-dropdown>
                </b-navbar-nav>
                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown right>
                        <!-- Using 'button-content' slot -->
                        <template #button-content>
                            <em>Usuario</em>
                        </template>
                        <b-dropdown-item href="#" @click.prevent="sair"
                            >Sair</b-dropdown-item
                        >
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>
<script>
export default {
    methods: {
        async sair() {
            await this.$store.dispatch("logout");
            this.$router.push("/");
        },
        async produtos() {
            this.$router.push({
                name: "produtos"
            });
        },
        async getEstoque() {
            await await this.$store.dispatch("estoque");
            this.$router.push({
                name: "estoque"
            });
        },
        async movimentacoes() {
            console.log("movimentações");
            //   await this.$store.dispatch("getHistorico");
            //   this.$bvModal.show("modal-historico");
        }
    }
};
</script>
