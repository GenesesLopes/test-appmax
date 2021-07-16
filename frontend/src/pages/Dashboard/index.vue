<template>
    <div>
        <Base>
            <b-row>
                <b-col cols="6">
                    <b-overlay :show="loading.estoque_baixo" rounded="sm">
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
                                accordion="estoque-baixo"
                                role="tabpanel-baixo"
                            >
                                <b-card-body>
                                    <b-card-text>
                                        <b-table
                                            responsive
                                            small
                                            caption-top
                                            hover
                                            :items="this.estoque_baixo"
                                        ></b-table>
                                    </b-card-text>
                                </b-card-body>
                            </b-collapse>
                        </b-card>
                    </b-overlay>
                </b-col>
                <b-col cols="6">
                    <b-overlay :show="loading.estoque_produtos" rounded="sm">
                        <b-card no-body class="mb-1">
                            <b-card-header
                                header-tag="header"
                                class="p-1"
                                role="tab"
                                v-b-toggle.estoque-produtos
                                align="center"
                                style="cursor: pointer;"
                            >
                                <b-icon
                                    icon="info-circle-fill"
                                    scale="1"
                                    variant="info"
                                    aria-hidden="true"
                                    align-start
                                ></b-icon>
                                Produtos do estoque
                            </b-card-header>
                            <b-collapse
                                id="estoque-produtos"
                                accordion="estoque-produtos"
                                role="tabpanel-produtos"
                            >
                                <b-card-body>
                                    <b-card-text>
                                        <b-table
                                            responsive
                                            small
                                            caption-top
                                            hover
                                            :items="this.estoque"
                                        ></b-table>
                                    </b-card-text>
                                </b-card-body>
                            </b-collapse>
                        </b-card>
                    </b-overlay>
                </b-col>
            </b-row>
        </Base>
    </div>
</template>
<script src="./index.js" />
