export default {
    SET_ERROS_PRODUTOS(state, data = {}) {
        if (data !== {}) {
            if ('errors' in data) {
                data = data['errors']
            }
            for (let fields in data) {
                state.erros[fields] = data[fields]
            }
        }
        else{
            state.erros = {}
        }

    },
    SET_DATA_PRODUTOS(state, data = {
        data: []
    }) {
        state.data = { ...state.data, ...data }
    }

}