import Axios from "axios";

const baseUrl = "http://localhost:8000/api/v1";
const api = Axios.create({
    baseURL: baseUrl,
});
api.interceptors.request.use((config) => {
    if (config.url !== "/auth/login") {
        let { access_token } = JSON.parse(localStorage.getItem("token"));
        config.headers["Authorization"] = `Bearer ${access_token}`;
    }
    return config;
});

api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response.status === 401) {
            if (error.response.config.url !== '/auth/login') {
                alert("Sessão expirada!");
                localStorage.removeItem("token");
                window.location = "/";
            }
        }
        throw error;
    }
);
export default api;
