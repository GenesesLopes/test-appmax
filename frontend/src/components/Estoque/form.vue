<template>
    <b-modal id="modal-estoque" hide-footer="true">
        <template #modal-header>
            <h5>Dados de estoque</h5>
        </template>
        <b-form @submit.prevent="salvar">

            <label class="sr-only" for="quantidade">Quantidade</label>
            <b-form-input
                id="quantidade"
                class="mb-2 mr-sm-2 mb-sm-0"
                placeholder="Quantidade a ser manuseada"
                v-model="form.quantidade"
            ></b-form-input>
            
            <b-form-select v-model="form.produto_id" v-if="this.form.acao == 'adição'" :options="produtos" size="sm" class="mt-3"></b-form-select>
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
                        this.$bvModal.hide('modal-estoque');
                    }
                "
                variant="danger"
                >Cancelar</b-button
            >
        </b-form>
    </b-modal>
</template>
<script src="./form.js" />
