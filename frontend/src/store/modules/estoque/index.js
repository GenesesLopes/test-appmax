import actions from "./actions";
import mutations from "./mutations";

export default {
    state: {
        errors:[],
        estoque_baixo: [],
        estoque: {
            data: []
        }
    },
    actions,
    mutations
}