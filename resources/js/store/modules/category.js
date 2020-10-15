export const namespaced = true;

export const mutations = {

};

export const actions = {
    store({commit}, payload) {
        return payload.post('/api/admin/categories', payload);
    },
    get(state, payload) {
        return axios.get(`/api/admin/categories/${payload}`);
    },
    update({ commit }, payload) {
        return payload.patch(`/api/admin/categories/${payload.slug}`, payload);
    },
    delete({ commit }, payload) {
        return axios.delete(`/api/admin/categories/${payload.slug}`);
    },
    categories({ commit }, payload) {
        return axios.get(`/api/admin/categories?page=${payload}`);
    }
};
