export default function admin({ next, store }) {
    if (store.getters['auth/user'] && store.getters['auth/user'].is_admin) {
        return next();
    }
    return next({ name: 'access-denied' })
}
