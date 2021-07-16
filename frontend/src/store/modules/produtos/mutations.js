export default {
    SET_ERROS_PRODUTOS(state, data = []){
        if(!data.length)
            state.data = []
        else
            state.data = [...state.data, ...data]
    },
    SET_DATA_PRODUTOS(state, data = {
        data: []
    }){
        state.data = {...state.data, ...data}
    }

}