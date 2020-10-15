export default async function checkAuth({ next, store }) {

    if (store.getters['auth/isLoggedIn']) {
        await store.dispatch('auth/getUser');
    }

    return next();
}
