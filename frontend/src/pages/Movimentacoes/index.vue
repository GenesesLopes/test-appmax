<template>
    <Base>
        <b-card
            title="Movimentações de estoque"
            header-tag="header"
            footer-tag="footer"
        >
            <b-card-text>
                <b-button-toolbar>
                    <b-input-group size="sm">
                        <b-form-datepicker
                            v-model="start_date"
                            class="mb-2"
                        ></b-form-datepicker>
                    </b-input-group>
                    <b-input-group size="sm" class="ml-2">
                        <b-form-datepicker
                            v-model="end_date"
                            class="mb-2"
                        ></b-form-datepicker>
                    </b-input-group>
                    <b-button-group size="sm" class="ml-1">
                        <b-button
                            variant="primary"
                            @click="estoque()"
                            :disabled="loading"
                        >
                            <b-icon
                                icon="search"
                                scale="1"
                                aria-hidden="true"
                                align-start
                                title="Pesquisar"
                            ></b-icon>
                        </b-button>
                    </b-button-group>
                </b-button-toolbar>
            </b-card-text>
            <b-overlay :show="loading" rounded="sm">
                <b-card
                    no-body
                    class="mb-1"
                    v-for="(dates, index) in Object.keys(this.relatorio)"
                    :key="index"
                >
                    <b-card-header
                        header-tag="header"
                        class="p-1"
                        role="tab"
                        v-b-toggle="dates"
                        style="cursor: pointer;"
                    >
                        <b-icon
                            icon="calendar2-date"
                            scale="1"
                            class="ml-3"
                            aria-hidden="true"
                            align-start
                        ></b-icon>
                        {{ dates | moment }}
                    </b-card-header>
                    <b-collapse :id="dates" :accordion="dates" role="tabpanel">
                        <b-card-body
                            v-for="(data, index) in relatorio[dates]"
                            :key="index"
                        >
                            <ul>
                                <li>Nome: {{ data.nome }}</li>
                                <li>Sku: {{ data.sku }}</li>
                                <li>Origem: {{ data.origem }}</li>
                                <li>Ação: {{ data.acao }}</li>
                                <li>Quantidade: {{ data.quantidade }}</li>
                            </ul>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </b-overlay>
        </b-card>
    </Base>
</template>
<script src="./index.js" />
