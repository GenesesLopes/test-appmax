import actions from './actions';
export default {
    state: {
        error:{
            login: []
        },
        data: {
            name: null
        }
    },
    mutations: {
        SET_DATA_USER(state, data = {name: null}){
            state.data = {...state.data, ...data}
        },
        SET_ERROR_USER(state, error = {login: []}){
            state.error = {...state.error, ...error}
        }
    },
    actions

}
