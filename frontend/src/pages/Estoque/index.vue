<template>
    <Base>
        <b-card
            title="Listagem de estoque"
            header-tag="header"
            footer-tag="footer"
        >
            <b-card-text>
                <b-button-toolbar>
                    <b-button-group size="sm" class="ml-1">
                        <b-button variant="primary" @click="adicionar()">
                            <b-icon
                                icon="plus-circle"
                                scale="1"
                                aria-hidden="true"
                                align-start
                                title="Adicionar"
                            ></b-icon>
                        </b-button>
                    </b-button-group>
                </b-button-toolbar>
            </b-card-text>
            <b-table
                id="tabela_estoques"
                responsive="sm"
                small
                caption-top
                hover
                :busy.sync="isBusy"
                :items="getData"
                :fields="['id', 'nome', 'sku', 'total_estoque', 'Baixa']"
            >
            <template #cell(Baixa)="row">

                    <b-button
                        size="sm"
                        class="mr-2"
                        @click="baixa(row.item)"
                        variant="danger"
                    >
                        <b-icon
                            icon="arrow-bar-down"
                            scale="1"
                            aria-hidden="true"
                            align-start
                            title="Baixa de estoque"
                        />
                    </b-button>
                </template>
                
            </b-table>
        </b-card>
        <form-estoque />
    </Base>
</template>
<script src="./index.js" />
