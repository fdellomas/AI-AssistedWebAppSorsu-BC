import { useAuthStore } from "../stores/auth";

export function authMiddleware(to, from, next) {
    const store = useAuthStore();
    const isAuthenticated = store.user;
    if (to.meta.requiresAuth) {
        if (!isAuthenticated) {
            next('/sign-in');
        } else {
            next();
        }
    }
    else if (to.meta.requiresGuest) {
        if (isAuthenticated) {
            next('/');
        } else {
            next();
        }
    }
    else {
        next();
    }
}