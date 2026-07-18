/* ==========================================================================
   portfolio.js — Comportamiento de la vista portfolio
   Filtros de proyectos · panel de detalle que baja desde arriba
   (Los trazos y líneas que se dibujan al hacer scroll los maneja app.js
   con la clase .draw-in-view.)
   ========================================================================== */

(() => {
    "use strict";

    /* ---- Filtros de proyectos ------------------------------------------ */

    const setupFilters = () => {
        const filterButtons = document.querySelectorAll(".filter-button");
        const projectCards = document.querySelectorAll(".project-card");

        if (!filterButtons.length || !projectCards.length) {
            return;
        }

        filterButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const selectedCategory = button.dataset.filter;

                filterButtons.forEach((item) => {
                    const isActive = item === button;

                    item.classList.toggle("active", isActive);
                    item.setAttribute("aria-pressed", String(isActive));
                });

                projectCards.forEach((card) => {
                    const shouldDisplay =
                        selectedCategory === "all" ||
                        selectedCategory === card.dataset.category;

                    card.classList.toggle("hidden", !shouldDisplay);
                });
            });
        });
    };

    /* ---- Formulario estático de contacto ------------------------------- */

    const setupStaticContactForm = () => {
        const form =
            document.getElementById("portfolio-contact-form") ||
            document.querySelector("[data-static-contact-form]");

        if (!form || !form.matches("[data-static-contact-form]")) {
            return;
        }

        const email = form.dataset.contactEmail || "";
        const message = form.querySelector("[data-static-contact-message]");

        if (!email) {
            return;
        }

        form.addEventListener("submit", (event) => {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            const data = new FormData(form);
            const getField = (...names) => {
                for (const name of names) {
                    const value = String(data.get(name) || "").trim();

                    if (value) {
                        return value;
                    }
                }

                return "";
            };
            const name = getField("nombre", "name");
            const sender = getField("correo", "email");
            const projectType = getField("tipo_proyecto", "subject");
            const bodyMessage = getField("mensaje", "message");
            const subject = projectType
                ? `Consulta de proyecto: ${projectType}`
                : "Nueva consulta desde el portafolio";
            const body = [
                `Nombre: ${name}`,
                `Correo: ${sender}`,
                `Tipo de proyecto: ${projectType}`,
                "",
                "Mensaje:",
                bodyMessage,
            ].join("\n");

            const mailtoUrl = `mailto:${email}?subject=${encodeURIComponent(
                subject
            )}&body=${encodeURIComponent(body)}`;

            window.location.href = mailtoUrl;

            if (message) {
                message.classList.remove("error");
                message.textContent =
                    "Se abrira tu aplicacion de correo con el mensaje preparado.";
            }
        });
    };

    /* ---- Detalle del proyecto ------------------------------------------ */
    /* Cada card abre el panel con su numeral; dentro, sólo se activan el
       texto y la captura que llevan ese mismo numeral. */

    const setupProjectDetail = () => {
        const overlay = document.getElementById("projectDetail");

        if (!overlay) {
            return;
        }

        const closeButton = document.getElementById("projectDetailClose");
        const triggers = document.querySelectorAll("[data-project-open]");
        const copies = overlay.querySelectorAll("[data-project-detail]");
        const galleries = overlay.querySelectorAll("[data-project-visual]");
        const stage = overlay.querySelector(".gp-detail-right");

        const AUTOPLAY_MS = 3000;
        const reducedMotion = window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;

        let lastTrigger = null;
        let autoplayTimer = null;

        // Aplica una miniatura: cambia imagen y texto de su propia galería
        const activateThumb = (thumb) => {
            const gallery = thumb.closest(".gp-detail-gallery");

            if (!gallery) {
                return;
            }

            const stage = gallery.querySelector(".gp-stage-img");
            const capTitle = gallery.querySelector(".gp-cap-title");
            const capText = gallery.querySelector(".gp-cap-text");

            if (stage && stage.getAttribute("src") !== thumb.dataset.src) {
                stage.setAttribute("src", thumb.dataset.src);
                stage.setAttribute("alt", thumb.dataset.title);
                // Re-dispara la animación de aparición del stage
                stage.style.animation = "none";
                void stage.offsetWidth;
                stage.style.animation = "";
            }

            if (capTitle) {
                capTitle.textContent = thumb.dataset.title;
            }

            if (capText) {
                capText.textContent = thumb.dataset.caption;
            }

            gallery.querySelectorAll(".gp-thumb").forEach((item) => {
                const isActive = item === thumb;

                item.classList.toggle("is-active", isActive);
                item.setAttribute("aria-selected", String(isActive));
            });
        };

        // Click en cualquier miniatura. Al elegir a mano, se reinicia el
        // conteo para no saltar de imagen enseguida.
        galleries.forEach((gallery) => {
            gallery.querySelectorAll(".gp-thumb").forEach((thumb) => {
                thumb.addEventListener("click", () => {
                    activateThumb(thumb);
                    restartAutoplay();
                });
            });
        });

        /* ---- Autoplay: avanza la galería activa cada 3s ---------------- */

        const advance = () => {
            const gallery = overlay.querySelector(
                ".gp-detail-gallery.is-active"
            );

            if (!gallery) {
                return;
            }

            const thumbs = gallery.querySelectorAll(".gp-thumb");

            // Con una sola imagen no hay nada que rotar
            if (thumbs.length < 2) {
                return;
            }

            const current = gallery.querySelector(".gp-thumb.is-active");
            const index = Array.prototype.indexOf.call(thumbs, current);
            const next = thumbs[(index + 1) % thumbs.length];

            activateThumb(next);
        };

        const stopAutoplay = () => {
            if (autoplayTimer !== null) {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const startAutoplay = () => {
            // Sin movimiento reducido, y sólo con el panel abierto
            if (reducedMotion || !overlay.classList.contains("is-open")) {
                return;
            }

            stopAutoplay();
            autoplayTimer = window.setInterval(advance, AUTOPLAY_MS);
        };

        const restartAutoplay = () => {
            stopAutoplay();
            startAutoplay();
        };

        // Pausa mientras el mouse está sobre la galería (para poder leer)
        if (stage) {
            stage.addEventListener("mouseenter", stopAutoplay);
            stage.addEventListener("mouseleave", startAutoplay);
        }

        const showProject = (key) => {
            let found = false;

            copies.forEach((copy) => {
                const isActive = copy.dataset.projectDetail === key;

                copy.classList.toggle("is-active", isActive);

                if (isActive) {
                    found = true;
                }
            });

            galleries.forEach((gallery) => {
                const isActive = gallery.dataset.projectVisual === key;

                gallery.classList.toggle("is-active", isActive);

                // Cada vez que se abre un proyecto, su galería vuelve a la 1ª
                if (isActive) {
                    const firstThumb = gallery.querySelector(".gp-thumb");

                    if (firstThumb) {
                        activateThumb(firstThumb);
                    }
                }
            });

            return found;
        };

        const openDetail = (key, trigger) => {
            if (!showProject(key)) {
                return;
            }

            lastTrigger = trigger;
            document.body.classList.add("detail-lock");
            overlay.classList.add("is-open");
            overlay.setAttribute("aria-hidden", "false");

            // Espera a que el panel sea visible para poder enfocar
            window.setTimeout(() => closeButton?.focus(), 120);

            // Arranca el pase automático tras la entrada del panel
            window.setTimeout(startAutoplay, 800);
        };

        const closeDetail = () => {
            stopAutoplay();
            overlay.classList.remove("is-open");
            overlay.setAttribute("aria-hidden", "true");
            document.body.classList.remove("detail-lock");
            lastTrigger?.focus();
        };

        const isOpen = () => overlay.classList.contains("is-open");

        triggers.forEach((trigger) => {
            trigger.addEventListener("click", () => {
                openDetail(trigger.dataset.projectOpen, trigger);
            });
        });

        closeButton?.addEventListener("click", closeDetail);

        // Los enlaces del pie llevan a otra sección: cierran antes de navegar
        overlay.querySelectorAll("[data-detail-close]").forEach((link) => {
            link.addEventListener("click", closeDetail);
        });

        document.addEventListener("keydown", (event) => {
            if (!isOpen()) {
                return;
            }

            if (event.key === "Escape") {
                closeDetail();
                return;
            }

            // Es un diálogo modal: el foco no debe irse a la página de atrás
            if (event.key !== "Tab") {
                return;
            }

            const focusables = overlay.querySelectorAll(
                'button, a[href], [tabindex]:not([tabindex="-1"])'
            );

            if (!focusables.length) {
                return;
            }

            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });
    };

    /* ---- Trazo del retrato: se dibuja cuando la tarjeta termina de entrar */

    const setupPortraitDraw = () => {
        const wrapper = document.querySelector(".hero-card-wrapper");
        const draw = document.querySelector(".portrait-draw");

        if (!draw) {
            return;
        }

        const start = () => draw.classList.add("in");

        // El fin de la entrada de la tarjeta (su translate) dispara el trazo;
        // así el usuario lo ve dibujarse recién cuando la tarjeta ya apareció.
        // Nota: NO usar { once: true } — el wrapper también transiciona opacity
        // (más corta), que dispararía transitionend antes y consumiría el once
        // sin ser el evento que buscamos. Filtramos y quitamos a mano.
        if (wrapper) {
            const onEnd = (event) => {
                if (event.propertyName === "translate") {
                    start();
                    wrapper.removeEventListener("transitionend", onEnd);
                }
            };

            wrapper.addEventListener("transitionend", onEnd);
        }

        // Red de seguridad calculada: si transitionend no llega, dibuja en el
        // momento en que la tarjeta ya debería estar visible. Con intro son
        // ~2.8s hasta que arranca el reveal; sin intro, arranca de inmediato.
        // A eso se suma la duración del reveal de la tarjeta (~1.3s).
        const introSeen =
            document.documentElement.classList.contains("intro-seen");
        const fallbackMs = (introSeen ? 0 : 2800) + 1300;

        window.setTimeout(start, fallbackMs);
    };

    document.addEventListener("DOMContentLoaded", () => {
        setupFilters();
        setupStaticContactForm();
        setupProjectDetail();
        setupPortraitDraw();
    });
})();
