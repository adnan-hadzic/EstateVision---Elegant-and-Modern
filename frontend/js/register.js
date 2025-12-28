function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

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

        const email = emailEl.value.trim();
        const password = passEl.value;
        if (!email || !password) {
            showMessage('loginMessage', 'Email and password are required.', false);
            return;
        }
        if (!isValidEmail(email)) {
            showMessage('loginMessage', 'Enter a valid email address.', false);
            return;
        }
        if (password.length < 4) {
            showMessage('loginMessage', 'Password must be at least 4 characters.', false);
            return;
        }
        const originalText = btn.textContent;
        
        btn.disabled = true;
        btn.textContent = 'Logging in...';

        try {
            const result = await window.AuthService.login(email, password);
            if (result.ok) {
                const data = result.data || {};
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
                showMessage('loginMessage', result.data?.message || result.data?.error || 'Login failed', false);
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

        const name = document.getElementById('registerName').value.trim();
        const email = document.getElementById('registerEmail').value.trim();
        const password = document.getElementById('registerPassword').value;
        const roleEl = document.getElementById('registerRole');
        const role = roleEl ? roleEl.value : 'buyer';
        const btn = document.getElementById('registerBtn');

        if (!name || !email || !password) {
            showMessage('registerMessage', 'Name, email, and password are required.', false);
            return;
        }
        if (!isValidEmail(email)) {
            showMessage('registerMessage', 'Enter a valid email address.', false);
            return;
        }
        if (password.length < 4) {
            showMessage('registerMessage', 'Password must be at least 4 characters.', false);
            return;
        }

        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Creating...';

        try {
            const result = await window.AuthService.register({
                name: name,
                email: email,
                password: password,
                role: role
            });
            if (result.ok) {
                showMessage('registerMessage', 'Success! Please login.', true);
                setTimeout(() => { window.location.hash = '#login'; }, 1000);
            } else {
                showMessage('registerMessage', result.data?.message || result.data?.error || 'Failed', false);
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
