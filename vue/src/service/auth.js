import jwt_decode from 'jwt-decode';

const auth = {

    login(token) {
        localStorage.setItem('token', token);
    },

    logout() {
        localStorage.removeItem('token');
    },

    isLogged() {
        if (localStorage.getItem('token') === null) {
            return false;
        }
        return true;
    },

    getToken() {
        if (this.isLogged() === false) {
            return null;
        }
        const token = localStorage.getItem('token');
        return jwt_decode(token);
    },

    getJwt() {
        return localStorage.getItem('token');
    }
}

export default auth;