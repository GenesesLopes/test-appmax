import api from "../../../services/api";

export default {
    async listProduto(context, dataSearh) {
        try {
            context.commit('SET_DATA_PRODUTOS')
            const { data } = await api.get('/produto', {
                params: { ...dataSearh }
            })
            context.commit('SET_DATA_PRODUTOS', data)
        } catch (error) {
            let { response } = error;
            if (response !== undefined) {
                context.commit("SET_ERROR_PRODUTO", [response.data]);
            }
            console.error(error);
            throw error;
        }
    },
    async showProduto(context, id) {
        try {
            context.commit('SET_DATA_PRODUTOS')
            const { data } = await api.get(`/produto/${id}`)
            context.commit('SET_DATA_PRODUTOS', data)
        } catch (error) {
            let { response } = error;
            if (response !== undefined) {
                context.commit("SET_ERROR_PRODUTO", [response.data]);
            }
            console.error(error);
            throw error;
        }
    },
    async createProduto(context, dataCreate) {
        try {
            context.commit('SET_DATA_PRODUTOS')
            const { data } = await api.post('/produto', dataCreate)
            context.commit('SET_DATA_PRODUTOS', data)
        } catch (error) {
            let { response } = error;
            if (response !== undefined) {
                context.commit("SET_ERROR_PRODUTO", [response.data]);
            }
            console.error(error);
            throw error;
        }
    },
    async updateProduto(context, dataUpdate) {
        try {
            context.commit('SET_DATA_PRODUTOS')
            let { id } = dataUpdate;
            const { data } = await api.put(`/produto/${id}`, dataUpdate)
            context.commit('SET_DATA_PRODUTOS', data)
        } catch (error) {
            let { response } = error;
            if (response !== undefined) {
                context.commit("SET_ERROR_PRODUTO", [response.data]);
            }
            console.error(error);
            throw error;
        }
    },
    async updateDestroy(context, id) {
        try {
            context.commit('SET_DATA_PRODUTOS')
            const { data } = await api.delete(`/produto/${id}`)
            context.commit('SET_DATA_PRODUTOS', data)
        } catch (error) {
            let { response } = error;
            if (response !== undefined) {
                context.commit("SET_ERROR_PRODUTO", [response.data]);
            }
            console.error(error);
            throw error;
        }
    }
}