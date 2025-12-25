const API_BASE_URL = 'http://localhost/EstateVision/backend';

async function apiRequest(path, options) {
    const token = localStorage.getItem('token');
    const headers = Object.assign(
        { 'Content-Type': 'application/json' },
        options && options.headers ? options.headers : {}
    );

    if (token && !headers.Authorization) {
        headers.Authorization = 'Bearer ' + token;
    }

    const response = await fetch(API_BASE_URL + path, Object.assign({}, options, { headers }));
    const text = await response.text();
    let data = null;
    if (text) {
        try {
            data = JSON.parse(text);
        } catch (e) {
            data = { message: text };
        }
    }

    return { ok: response.ok, status: response.status, data: data };
}

window.ApiClient = { request: apiRequest };
