window.AuthService = {
    login: function(email, password) {
        return window.ApiClient.request('/auth/login', {
            method: 'POST',
            body: JSON.stringify({ email: email, password: password })
        });
    },
    register: function(payload) {
        return window.ApiClient.request('/auth/register', {
            method: 'POST',
            body: JSON.stringify(payload)
        });
    }
};
