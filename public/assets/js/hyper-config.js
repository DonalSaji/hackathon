(function () {
    const html = document.documentElement;
    const storedConfig = sessionStorage.getItem("__HYPER_CONFIG__");

    const defaultConfig = {
        theme: "light",
        nav: "vertical",
        layout: { mode: "fluid", position: "fixed" },
        topbar: { color: "light" },
        menu: { color: "dark" },
        sidenav: { size: "default", user: false }
    };

    let config = Object.assign({}, defaultConfig);

    // Pull config from data attributes
    config.theme = html.getAttribute("data-bs-theme") || defaultConfig.theme;
    config.nav = html.getAttribute("data-layout") === "topnav" ? "horizontal" : "vertical";
    config.layout.mode = html.getAttribute("data-layout-mode") || defaultConfig.layout.mode;
    config.layout.position = html.getAttribute("data-layout-position") || defaultConfig.layout.position;
    config.topbar.color = html.getAttribute("data-topbar-color") || defaultConfig.topbar.color;
    config.menu.color = html.getAttribute("data-menu-color") || defaultConfig.menu.color;
    config.sidenav.size = html.getAttribute("data-sidenav-size") || defaultConfig.sidenav.size;
    config.sidenav.user = html.getAttribute("data-sidenav-user") !== null || defaultConfig.sidenav.user;

    // Apply __HYPER_CONFIG__ from sessionStorage if present
    if (storedConfig) {
        config = JSON.parse(storedConfig);
    }

    window.defaultConfig = JSON.parse(JSON.stringify(config));
    window.config = config;

    // Apply sidebar size from sessionStorage if set
    const savedSidebarSize = sessionStorage.getItem("sidebarSize");
    if (savedSidebarSize) {
        html.setAttribute("data-sidenav-size", savedSidebarSize);
        if (window.config?.sidenav) {
            window.config.sidenav.size = savedSidebarSize;
        }
    }

    // Set data attributes on HTML based on config
    html.setAttribute("data-bs-theme", config.theme);
    html.setAttribute("data-layout-mode", config.layout.mode);
    html.setAttribute("data-menu-color", config.menu.color);
    html.setAttribute("data-topbar-color", config.topbar.color);
    html.setAttribute("data-layout-position", config.layout.position);

    if (config.nav === "vertical") {
        let size = config.sidenav.size;

        if (window.innerWidth <= 767) {
            size = "full";
        } else if (
            window.innerWidth > 767 &&
            window.innerWidth <= 1140 &&
            config.sidenav.size !== "full" &&
            config.sidenav.size !== "fullscreen"
        ) {
            size = "condensed";
        }

        html.setAttribute("data-sidenav-size", size);

        if (config.sidenav.user && config.sidenav.user.toString() === "true") {
            html.setAttribute("data-sidenav-user", true);
        } else {
            html.removeAttribute("data-sidenav-user");
        }
    }
})();
