/* ==========================================================================
   app.js — Comportamiento global (layout base)
   Menú móvil · header al hacer scroll · reveal · nav activa · intro · año
   ========================================================================== */

(() => {
    "use strict";

    const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    /* ---- Intro gótica que se dibuja (svg-draw-in) ----------------------- */

    const INTRO_KEY = "gothicIntroSeen";
    const INTRO_DURATION = prefersReducedMotion ? 600 : 2800;

    /* Mientras corre la intro, las entradas esperan: si no, la cascada del
       header y del hero se gasta detrás del overlay.

       La espera es un delay de CSS (--intro-offset) y NO se hace creando los
       observers más tarde: así el IntersectionObserver se crea siempre en
       DOMContentLoaded, que es el único momento en que estamos seguros de que
       observa bien. Al terminar la intro se quita el offset, para que lo que
       entre después por scroll no arrastre el retraso. */

    const runIntro = () => {
        const intro = document.getElementById("intro");
        const root = document.documentElement;

        if (!intro) {
            return;
        }

        let alreadySeen = false;

        try {
            alreadySeen = sessionStorage.getItem(INTRO_KEY) === "1";
        } catch (error) {
            alreadySeen = false;
        }

        if (alreadySeen) {
            intro.remove();
            return;
        }

        root.style.setProperty("--intro-offset", `${INTRO_DURATION}ms`);
        document.body.classList.add("intro-lock");

        window.setTimeout(() => {
            intro.classList.add("done");
            document.body.classList.remove("intro-lock");

            // Las transiciones ya lanzadas conservan el delay que tenían al
            // arrancar; esto sólo afecta a lo que se revele de aquí en adelante.
            root.style.removeProperty("--intro-offset");

            try {
                sessionStorage.setItem(INTRO_KEY, "1");
            } catch (error) {
                /* sessionStorage bloqueado: la intro simplemente se repetirá */
            }

            window.setTimeout(() => intro.remove(), 700);
        }, INTRO_DURATION);
    };

    /* ---- Menú móvil ---------------------------------------------------- */

    const setupMenu = () => {
        const menuButton = document.getElementById("menuButton");
        const navList = document.getElementById("navList");

        if (!menuButton || !navList) {
            return;
        }

        const closeMenu = () => {
            navList.classList.remove("active");
            menuButton.classList.remove("active");
            document.body.classList.remove("menu-open");
            menuButton.setAttribute("aria-expanded", "false");
            menuButton.setAttribute("aria-label", "Abrir menú");
        };

        menuButton.addEventListener("click", () => {
            const isActive = navList.classList.toggle("active");

            menuButton.classList.toggle("active", isActive);
            document.body.classList.toggle("menu-open", isActive);
            menuButton.setAttribute("aria-expanded", String(isActive));
            menuButton.setAttribute(
                "aria-label",
                isActive ? "Cerrar menú" : "Abrir menú"
            );
        });

        document.querySelectorAll(".nav-link").forEach((link) => {
            link.addEventListener("click", closeMenu);
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && navList.classList.contains("active")) {
                closeMenu();
                menuButton.focus();
            }
        });
    };

    /* ---- Header al hacer scroll ---------------------------------------- */

    const setupHeader = () => {
        const header = document.getElementById("header");

        if (!header) {
            return;
        }

        const updateHeader = () => {
            header.classList.toggle("scrolled", window.scrollY > 40);
        };

        window.addEventListener("scroll", updateHeader, { passive: true });
        updateHeader();
    };

    /* ---- Reveal al entrar en pantalla ---------------------------------- */

    const setupReveal = () => {
        const revealElements = document.querySelectorAll(".reveal");

        if (!revealElements.length) {
            return;
        }

        if (!("IntersectionObserver" in window)) {
            revealElements.forEach((el) => el.classList.add("visible"));
            return;
        }

        const revealObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12 }
        );

        revealElements.forEach((element) => revealObserver.observe(element));
    };

    /* ---- Trazos y líneas que se dibujan al entrar en pantalla ---------- */
    /* Cualquier elemento con .draw-in-view recibe .in al aparecer: lo usan
       los ornamentos SVG, el rosetón, los arcos y la lista de habilidades. */

    const setupDrawInView = () => {
        const drawElements = document.querySelectorAll(".draw-in-view");

        if (!drawElements.length) {
            return;
        }

        if (!("IntersectionObserver" in window)) {
            drawElements.forEach((element) => element.classList.add("in"));
            return;
        }

        const drawObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("in");
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        drawElements.forEach((element) => drawObserver.observe(element));
    };

    /* ---- Enlace activo según la sección visible ------------------------ */

    const setupNavigationSpy = () => {
        const sections = document.querySelectorAll("section[id]");
        const navLinks = document.querySelectorAll(".nav-link");

        if (!sections.length || !navLinks.length) {
            return;
        }

        if (!("IntersectionObserver" in window)) {
            return;
        }

        const navigationObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    navLinks.forEach((link) => {
                        const href = link.getAttribute("href") || "";
                        const hash = href.includes("#")
                            ? `#${href.split("#").pop()}`
                            : href;

                        link.classList.toggle(
                            "active",
                            hash === `#${entry.target.id}`
                        );
                    });
                });
            },
            { rootMargin: "-35% 0px -55% 0px" }
        );

        sections.forEach((section) => navigationObserver.observe(section));
    };

    /* ---- Año del footer ------------------------------------------------ */

    const setupYear = () => {
        const yearElement = document.getElementById("currentYear");

        if (yearElement) {
            yearElement.textContent = String(new Date().getFullYear());
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        // runIntro va primero: fija --intro-offset antes de que setupReveal
        // marque como visibles los elementos que ya están en pantalla.
        runIntro();

        setupMenu();
        setupHeader();
        setupReveal();
        setupDrawInView();
        setupNavigationSpy();
        setupYear();
    });
})();
