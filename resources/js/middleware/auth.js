export default function auth({ next, store }) {
    if (! store.getters['auth/isLoggedIn']) {
        return next({ name: 'auth', params: { url: 'login' } });
    }

    return next();
}
