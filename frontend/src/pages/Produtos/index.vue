<template>
    <Base>
        <b-card
            title="Listagem de produtos"
            header-tag="header"
            footer-tag="footer"
        >
            <b-card-text>
                <b-button-toolbar>
                    <b-input-group size="sm" prepend="Por página">
                        <b-form-input
                            v-model="perPage"
                            lass="text-left"
                        ></b-form-input>
                    </b-input-group>
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
                id="tabela_produtos"
                responsive="sm"
                small
                caption-top
                hover
                :per-page="perPage"
                :current-page="currentPage"
                :busy.sync="isBusy"
                :items="getData"
                :fields="['id', 'nome', 'sku', 'Ações']"
            >
                <template #cell(Ações)="row">
                    <b-button
                        size="sm"
                        class="mr-2"
                        @click="editar(row.item)"
                        variant="info"
                    >
                        <b-icon
                            icon="arrow-clockwise"
                            scale="1"
                            aria-hidden="true"
                            align-start
                            title="Editar"
                        />
                    </b-button>
                    <b-button
                        size="sm"
                        class="mr-2"
                        @click="excluir(row.item)"
                        variant="danger"
                    >
                        <b-icon
                            icon="x-circle"
                            scale="1"
                            aria-hidden="true"
                            align-start
                            title="Editar"
                        />
                    </b-button>
                </template>
            </b-table>
            <template #footer>
                <b-pagination
                    v-model="currentPage"
                    :total-rows="dataProdutos.total"
                    :per-page="perPage"
                    aria-controls="tabela_produtos"
                />
            </template>
        </b-card>
        <form-produto />
    </Base>
</template>
<script src="./index.js" />
