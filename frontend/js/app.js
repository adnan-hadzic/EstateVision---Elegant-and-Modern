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

  app.run();
  console.log("SPA initialized successfully!");
});
