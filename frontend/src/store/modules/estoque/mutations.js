export default {
    SET_ERROS_ESTOQUE(state, data = []) {
        if (data.length)
            state.errors = [...state.errors, ...data]
        else
            state.errors = data
    },
    SET_ESTOQUE_BAIXO(state, data = []) {
        if (data.length)
            state.estoque_baixo = [...state.estoque_baixo, ...data]
        else
            state.estoque_baixo = []
    },
    SET_ESTOQUE(state, data = {
        data: []
    }) {
        state.estoque = { ...state.estoque, ...data }

    }
}