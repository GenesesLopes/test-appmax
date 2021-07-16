<template>
    <b-modal id="modal-produto" hide-footer="true">
        <template #modal-header>
            <h5>Dados de produto</h5>
        </template>
        <b-form inline @submit.prevent="salvar">
            <label class="sr-only" for="nome">Nome</label>
            <b-form-input
                id="nome"
                class="mb-2 mr-sm-2 mb-sm-0"
                placeholder="Nome do produto"
                v-model="form.nome"
            ></b-form-input>

            <label class="sr-only" for="sku">Sku</label>

            <b-form-input
                id="sku"
                v-model="form.sku"
                placeholder="código"
            ></b-form-input>

            <b-button
                type="submit"
                :disabled="loading"
                class="mt-2"
                variant="primary"
                >Salvar</b-button
            >
            <b-button
                class="mt-2 ml-2"
                @click="
                    () => {
                        this.$bvModal.hide('modal-produto');
                    }
                "
                variant="danger"
                >Cancelar</b-button
            >
        </b-form>
    </b-modal>
</template>
<script src="./form.js" />
