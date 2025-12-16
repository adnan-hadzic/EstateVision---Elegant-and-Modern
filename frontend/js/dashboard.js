document.addEventListener('DOMContentLoaded', () => {
    checkDashboard();
});

window.addEventListener('hashchange', () => {
    checkDashboard();
});

function checkDashboard() {
    if (window.location.hash === '#dashboard') {
        setTimeout(initDashboard, 100);
    }
}

function initDashboard() {
    const token = localStorage.getItem('token');
    const userStr = localStorage.getItem('user');
    
    if (!token || !userStr) {
        window.location.hash = '#login';
        return;
    }
    
    const user = JSON.parse(userStr);
    
    // Prikazi ime
    const nameEl = document.getElementById('dashboardUserName');
    if(nameEl) nameEl.textContent = user.name || user.email;
    
    // Admin dugme
    const adminBtn = document.getElementById('adminBtn'); // Dodajemo dugme dinamicki ako ga nema
    if (user.role === 'admin') {
        $('#nav-admin').removeClass('d-none'); // Prikazi link u meniju
        $('.admin-only').removeClass('d-none'); // Prikazi sve admin elemente
    }
}

// Global Logout function
window.logout = function() {
    localStorage.clear();
    $('#nav-login, #nav-register').removeClass('d-none');
    $('#nav-dashboard, #nav-profile, #nav-logout, #nav-admin').addClass('d-none');
    window.location.hash = '#login';
}
