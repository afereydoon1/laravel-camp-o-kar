export const namespaced = true;

export const state = {
    memberships: {}
};

export const mutations = {
    SET_MEMBERSHIPS(state, payload) {
        state.memberships = payload;
    },
    DELETE_MEMBERSHIP(state, payload) {
        state.memberships.data.splice(payload, 1);
    }
};

export const actions = {
    store({ state }, payload) {
        return payload.post('/api/admin/membership', payload);
    },
    update({ state }, payload) {
        return payload.patch(`/api/admin/membership/${payload.id}`, payload);
    },
    getMembership({ commit }, payload = 1) {
        axios.get(`/api/admin/membership?page=${payload}`)
            .then(({ data }) => {
                commit('SET_MEMBERSHIPS', data);
                window.history.pushState('membership', 'Membership', `/admin/membership?page=${payload}`)
            })
    },
    async get({ state }, payload) {
        if (! _.isEmpty(state.memberships)) {
            let membership = state.memberships.data.find(membership => membership.id === payload);
            if (membership) {
                return membership;
            }
        }
        let { data } = await axios.get(`/api/admin/membership/${payload}`);
        return data;
    },
    deleteMembership({ commit }, payload) {
        axios.delete(`/api/admin/membership/${payload.id}`)
            .then(({ data}) => {
                commit('DELETE_MEMBERSHIP', payload.index);
            })
    }
};
