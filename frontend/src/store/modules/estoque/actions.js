import api from "../../../services/api";

export default {
    async estoque_baixo(context) {
        try {
            context.commit("SET_ERROS_ESTOQUE")
            context.commit("SET_ESTOQUE_BAIXO")
            const { data } = await api.get('/estoque-baixo')
            context.commit("SET_ESTOQUE_BAIXO", data)
        } catch (error) {
            console.error(error.response)
            throw error
        }
    },
    async estoque(context,dataSearch) {
        try {
            context.commit("SET_ERROS_ESTOQUE")
            context.commit("SET_ESTOQUE")
            const { data } = await api.get('/estoque',{
                params: {...dataSearch}
            })
            context.commit("SET_ESTOQUE", data)
        } catch (error) {
            console.error(error.response)
        }
    }
}