export const namespaced = true;

export const state = {
    user: JSON.parse(localStorage.getItem('user')),
    isLoggedIn: !! localStorage.getItem('token'),
    token: localStorage.getItem('token'),
    refresh_token: localStorage.getItem('refresh_token'),
};

export const getters = {
    user(state) {
         return state.user
    },
    isLoggedIn(state) {
         return state.isLoggedIn
    },
    token(state) {
         return state.token
    },
};

export const mutations = {
    SET_USER(state, payload) {
        state.user = payload;
        state.isLoggedIn = true;

        localStorage.setItem('user', JSON.stringify(payload));
    },
    SET_TOKEN(state, payload) {
        state.token = payload;

        localStorage.setItem('token', payload);
    },
    SET_TOKEN_REFRESH_TOKEN(state, payload) {
        state.token = payload.access_token;
        state.refresh_token = payload.refresh_token;

        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + payload.access_token;

        localStorage.setItem('token', payload.access_token);
        localStorage.setItem('refresh_token', payload.refresh_token);
    },
    LOGOUT_USER(state) {
        state.user = null;
        state.isLoggedIn = false;
        state.token = null;

        localStorage.removeItem('token');
        localStorage.removeItem('user');
    }
};

export const actions = {
    login({ state, commit }, form) {
        return form.post('/api/login', form)
            .then(({ data }) => {
                commit('SET_USER', data.data);
                commit('SET_TOKEN', data.data.token);

                if(form.remember) {
                    localStorage.setItem('refresh_token', data.data.refresh_token)
                }

                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.token;
            })
    },
    register({ state, commit }, payload) {
        return payload.post('/api/register', payload)
            .then(({ data }) => {
                commit('SET_USER', data.data);
                commit('SET_TOKEN', data.data.token);

                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.token;
            })
    },
    logout({ commit }) {
        commit('LOGOUT_USER')
    },

    profile({ commit }, form) {
        return form.patch(`/api/dashboard/profile/${form.id}`)
            .then(({ data }) => {
                commit('SET_USER', data);
            })
    },

    getUser({ commit, state }) {
        return axios.get('/api/me')
            .then(({ data }) => {
                commit('SET_USER', data);
            })
            .catch(({ response }) => {
                if (response.status === 401) {
                    axios.post('/api/refresh_token', {
                        refresh_token: state.refresh_token
                    }).then(({ data }) => {
                        commit('SET_TOKEN_REFRESH_TOKEN', data.data);
                        location.reload();
                    })
                    .catch(() => {
                        commit('LOGOUT_USER');
                    })
                }
            })
    }
};
