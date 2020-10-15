import objectToFormData from "object-to-formdata";

export const namespaced = true;

export const state = {
    files: {}
};

export const mutations = {
    SET_FILES(state, payload) {
        state.files = payload;
    },
    DELETE_FILE(state, index) {
        state.files.data.splice(index, 1);
    }
};

export const actions = {
    getFiles({ commit }, queries) {
        return axios.get(`/api/admin/file`, {
                params: queries
            })
            .then(({ data }) => {
                commit('SET_FILES', data);
                window.history.pushState('files', 'FILES', `/admin/file?${data.meta.queries}`)
            });
    },
    getUserFiles({ commit }, queries) {
        return axios.get(`/api/file`, {
                params: queries
            })
            .then(({ data }) => {
                commit('SET_FILES', data);
            });
    },
    getTaggedFiles({ commit }, { queries, params}) {
        return axios.get(`/api/file/tagged/${params.slug}`, {
                params: queries
            })
            .then(({ data }) => {
                commit('SET_FILES', data);
            });
    },
    deleteFile({ commit }, payload) {
        axios.delete(`/api/admin/file/${payload.slug}`)
            .then(() => {
                commit('DELETE_FILE', payload.index);
                swal.message('فایل به درستی حذف شد.');
            })
    },
    store({ commit }, payload) {
        return payload.submit('post', '/api/admin/file', {
            transformRequest: [function (data, header) {
                return objectToFormData(data);
            }]
        })
    },
    update({ commit }, form) {
        return form.submit('post', '/api/admin/file/' + form.slug, {
            transformRequest: [function (data, header) {
                return objectToFormData(data);
            }]
        })
    },
    async get({ state }, slug) {
        if (! _.isEmpty(state.files)) {
            let file = state.files.data.find(file => file.slug === slug);
            if (file) {
                return file;
            }
        }
        let { data } = await axios.get('/api/admin/file/' + slug);
        return data;
    }
};
