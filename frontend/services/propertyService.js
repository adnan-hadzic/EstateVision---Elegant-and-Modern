window.PropertyService = {
    create: function(payload) {
        return window.ApiClient.request('/properties', {
            method: 'POST',
            body: JSON.stringify(payload)
        });
    }
};
