<template>
    <div>
        <Base>
            <div class="accordion" role="tablist">
                <div class="col-md-6">
                    <b-card no-body class="mb-1">
                        <b-card-header
                            header-tag="header"
                            class="p-1"
                            role="tab"
                            v-b-toggle.estoque-baixo
                            align="center"
                            style="cursor: pointer;"
                        >
                            <b-icon
                                icon="exclamation-circle-fill"
                                scale="1"
                                variant="warning"
                                aria-hidden="true"
                                align-start
                            ></b-icon>

                            Produtos abaixo de 100 unidades
                        </b-card-header>
                        <b-collapse
                            id="estoque-baixo"
                            accordion="my-accordion"
                            role="tabpanel"
                        >
                            <b-card-body>
                                <b-card-text>asdq</b-card-text>
                            </b-card-body>
                        </b-collapse>
                    </b-card>
                </div>
            </div>
        </Base>
    </div>
</template>
<script>
import Base from "../../components/Base/index.vue";
export default {
    components: {
        Base
    },
    mounted() {
        this.$root.$on('bv::collapse::state',(id,state) =>{
            if(id === 'estoque-baixo' && state){
                console.log('chamada de api')
            }
            
        })
    }
};
</script>
