import api from "../../../services/api";

export default {
    async estoque_baixo(context) {
        try {
            context.commit("SET_ERROS_ESTOQUE")
            // context.commit("SET_ESTOQUE_BAIXO")
            let { data } = await api.get('/estoque-baixo')
            context.commit("SET_ESTOQUE_BAIXO", data)
        } catch (error) {
            console.error(error.response)
            throw error
        }
    },
    async estoque(context) {
        try {

            context.commit("SET_ERROS_ESTOQUE")
            const { data } = await api.get('/estoque')
            context.commit("SET_ESTOQUE", data)
        } catch (error) {
            console.error(error.response)
            throw error
        }
    },
    async estoqueAdd(context, dataAdd) {
        try {

            context.commit("SET_ERROS_ESTOQUE")
            const { data } = await api.post('/adicionar-produtos', dataAdd)
            context.commit("SET_ESTOQUE", data)
        } catch (error) {
            console.error(error.response)
            throw error
        }
    },
    async estoqueBaixa(context, dataAdd) {
        try {
            context.commit("SET_ERROS_ESTOQUE")
            const { data } = await api.put('/baixar-produtos', dataAdd)
            context.commit("SET_ESTOQUE", data)
        } catch (error) {
            console.error(error.response)
            throw error
        }
    },

    async relatorio(context, dataAdd) {

        context.commit("SET_ERROS_ESTOQUE")
        const { data } = await api.get('/relatorio', {
            params: { ...dataAdd }
        })
        
        context.commit("SET_RELATORIO", data)

    }

}