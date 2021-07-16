export default {
    SET_ERROS_ESTOQUE(state, data = []) {
        if (data.length)
            state.errors = [...state.errors, ...data]
        else
            state.errors = data
    },
    SET_ESTOQUE_BAIXO(state, data = []) {
        state.estoque_baixo = data
    },
    SET_ESTOQUE(state, data = []) {
        state.estoque = data
    }
}