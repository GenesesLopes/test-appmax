import api from "../../../services/api";
export default {
    async login(contex, form) {
        try {

            form = { ...form, password: form.senha };
            delete form["senha"];
            let { data } = await api.post("/auth/login", form);
            localStorage.setItem("token", JSON.stringify(data));
        } catch (error) {
            let { response } = error;
            if (response !== undefined) {
                contex.commit("SET_ERROR_USER", {
                    login: [response.data],
                });
            }
            console.error(error);
        }
    },
    async logout() {
        try {
          await api.post("/auth/logout");
          localStorage.removeItem("token");
        } catch (error) {
          console.log(error);
        } 
      }
}