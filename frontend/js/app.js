$(document).ready(function() {
    console.log("jQuery loaded:", typeof $);
    console.log("SPApp loaded:", typeof $.spapp);

    var app = $.spapp({
        defaultView: "home",
        templateDir: "views/"  
    });

    app.route({ view: "home", load: "home.html" });
    app.route({ view: "listings", load: "listings.html" });
    app.route({ view: "about", load: "about.html" });
    app.route({ view: "dashboard", load: "dashboard.html" });
    app.route({ view: "profile", load: "profile.html" });
    app.route({ view: "login", load: "login.html" });
    app.route({ view: "register", load: "register.html" });
    app.route({ view: "admin", load: "admin.html" });

    app.run();
    console.log("SPA initialized successfully!");
    
    if (localStorage.getItem('token')) {
        $('#nav-login, #nav-register').addClass('d-none');
        $('#nav-dashboard, #nav-profile, #nav-logout').removeClass('d-none');
        
        var user = JSON.parse(localStorage.getItem('user'));
        if (user && user.role === 'admin') {
            $('#nav-admin').removeClass('d-none');
        }
    }
});
