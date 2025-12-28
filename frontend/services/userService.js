window.UserService = {
    getAll: function() {
        return window.ApiClient.request('/users', { method: 'GET' });
    },
    create: function(payload) {
        return window.ApiClient.request('/users', {
            method: 'POST',
            body: JSON.stringify(payload)
        });
    },
    delete: function(id) {
        return window.ApiClient.request('/users/' + id, { method: 'DELETE' });
    }
};
