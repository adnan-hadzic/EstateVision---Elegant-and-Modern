const API_BASE_URL = 'http://localhost/EstateVision/backend';

function showMessage(elementId, message, isSuccess) {
    const msgElement = document.getElementById(elementId);
    if (!msgElement) { alert(message); return; }
    msgElement.textContent = message;
    msgElement.className = isSuccess ? 'alert alert-success' : 'alert alert-danger';
    msgElement.style.display = 'block';
    if (isSuccess) setTimeout(() => { msgElement.style.display = 'none'; }, 3000);
}

document.addEventListener('submit', async function(e) {
    
    if(e.target && e.target.id === 'loginForm') {
        e.preventDefault();
        
        const emailEl = document.getElementById('loginEmail');
        const passEl = document.getElementById('loginPassword');
        const btn = document.getElementById('loginBtn');

        if (!emailEl || !passEl) {
            console.error("GRESKA: Ne mogu da nadjem input polja!");
            return;
        }

        const email = emailEl.value;
        const password = passEl.value;
        const originalText = btn.textContent;
        
        btn.disabled = true;
        btn.textContent = 'Logging in...';

        try {
            const response = await fetch(`${API_BASE_URL}/auth/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            const data = await response.json();

            if (response.ok) {
                let token = data.token;
                if (data.data && data.data.token) token = data.data.token;
                
                if (token) {
                    localStorage.setItem('token', token);
                    localStorage.setItem('user', JSON.stringify(data.data || data)); 
                    
                    showMessage('loginMessage', 'Login successful!', true);
                    
                    $('#nav-login, #nav-register').addClass('d-none');
                    $('#nav-dashboard, #nav-profile, #nav-logout').removeClass('d-none');

                    setTimeout(() => { window.location.hash = '#dashboard'; }, 500);
                } else {
                    showMessage('loginMessage', 'Login OK but no token?', false);
                }
            } else {
                showMessage('loginMessage', data.message || data.error || 'Login failed', false);
            }
        } catch (err) {
            showMessage('loginMessage', 'Error: ' + err.message, false);
        } finally {
            if(btn) {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
    }

    if(e.target && e.target.id === 'registerForm') {
        e.preventDefault();

        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const roleEl = document.getElementById('registerRole');
        const role = roleEl ? roleEl.value : 'buyer';
        const btn = document.getElementById('registerBtn');

        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Creating...';

        try {
            const response = await fetch(`${API_BASE_URL}/auth/register`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    name: name, 
                    email: email, 
                    password: password,
                    role: role 
                })
            });
            const data = await response.json();

            if (response.ok) {
                showMessage('registerMessage', 'Success! Please login.', true);
                setTimeout(() => { window.location.hash = '#login'; }, 1000);
            } else {
                showMessage('registerMessage', data.message || data.error || 'Failed', false);
            }
        } catch (err) {
            showMessage('registerMessage', 'Error: ' + err.message, false);
        } finally {
            if(btn) {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
    }
});
