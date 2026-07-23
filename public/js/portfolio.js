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

    /* ---- Detalle del proyecto — case study scrolleable ----------------- */
    /* Cada card abre el case study de su obra: hero con la demo, y al bajar la
       ficha, el recorrido pantalla por pantalla, el stack y el cierre. */

    const setupProjectDetail = () => {
        const overlay = document.getElementById("projectDetail");

        if (!overlay) {
            return;
        }

        const scroll = document.getElementById("projectDetailScroll");
        const closeButton = document.getElementById("projectDetailClose");
        const triggers = document.querySelectorAll("[data-project-open]");
        const cases = overlay.querySelectorAll("[data-project-case]");
        const progressValue = overlay.querySelector("[data-detail-progress]");

        const reducedMotion = window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;

        let lastTrigger = null;
        let activeMedia = null;
        const userPausedMedia = new WeakSet();

        const isOpen = () => overlay.classList.contains("is-open");

        /* ---- Players de video inline ---------------------------------- */
        /* Se precarga al acercarse y solo reproduce la escena en foco. */

        const getVideo = (media) => media?.querySelector("video.gp-media-el");

        const loadMedia = (media) => {
            const video = getVideo(media);

            if (!video || video.getAttribute("src")) {
                return video;
            }

            video.setAttribute("src", media.dataset.src);
            video.load();

            return video;
        };

        const pauseMedia = (media, reset = false) => {
            const video = getVideo(media);

            if (!video) {
                return;
            }

            try {
                video.pause();

                if (reset) {
                    video.removeAttribute("src");
                    video.load();
                }
            } catch (error) {
                /* El reproductor todavía no tenía una fuente activa. */
            }

            video.controls = false;
            media.classList.remove("is-playing");

            if (activeMedia === media) {
                activeMedia = null;
            }
        };

        const playMedia = (media) => {
            const video = getVideo(media);

            if (!video) {
                return;
            }

            overlay
                .querySelectorAll(".gp-media.is-video.is-playing")
                .forEach((item) => {
                    if (item !== media) {
                        pauseMedia(item);
                    }
                });

            loadMedia(media);
            video.controls = false;
            media.classList.add("is-playing");
            activeMedia = media;

            const played = video.play();

            if (played && typeof played.catch === "function") {
                played.catch(() => {
                    video.controls = false;
                    media.classList.remove("is-playing");

                    if (activeMedia === media) {
                        activeMedia = null;
                    }
                });
            }
        };

        const resetMedia = (media) => {
            userPausedMedia.delete(media);
            pauseMedia(media, true);
        };

        const pauseAllVideos = () => {
            overlay
                .querySelectorAll(".gp-media.is-video")
                .forEach((media) => resetMedia(media));
        };

        overlay.querySelectorAll(".gp-media[data-video]").forEach((media) => {
            const play = media.querySelector(".gp-media-play");
            const video = getVideo(media);

            if (play) {
                play.addEventListener("click", () => {
                    if (video && !video.paused) {
                        userPausedMedia.add(media);
                        pauseMedia(media);
                    } else {
                        userPausedMedia.delete(media);
                        playMedia(media);
                    }
                });
            }

            video?.addEventListener("play", () => {
                activeMedia = media;
                media.classList.add("is-playing");
                play?.setAttribute(
                    "aria-label",
                    `Pausar video: ${media.dataset.videoTitle || "demo"}`
                );
            });

            video?.addEventListener("pause", () => {
                media.classList.remove("is-playing");
                play?.setAttribute(
                    "aria-label",
                    `Reproducir video: ${media.dataset.videoTitle || "demo"}`
                );

                if (activeMedia === media) {
                    activeMedia = null;
                }
            });

            video?.addEventListener("ended", () => {
                media.classList.remove("is-playing");
                video.controls = false;
                play?.setAttribute(
                    "aria-label",
                    `Reproducir video: ${media.dataset.videoTitle || "demo"}`
                );

                if (activeMedia === media) {
                    activeMedia = null;
                }
            });
        });

        /* ---- Autoplay por visibilidad -------------------------------- */

        const videoVisibility = new Map();
        let videoObserver = null;

        const syncVisibleVideo = () => {
            if (reducedMotion || !isOpen()) {
                return;
            }

            const activeCase = overlay.querySelector(".gp-case.is-active");

            if (!activeCase) {
                return;
            }

            const candidate = [...videoVisibility.entries()]
                .filter(
                    ([media, ratio]) =>
                        ratio >= 0.52 &&
                        activeCase.contains(media) &&
                        !userPausedMedia.has(media)
                )
                .sort((a, b) => b[1] - a[1])[0]?.[0];

            if (candidate) {
                if (candidate !== activeMedia) {
                    playMedia(candidate);
                }

                return;
            }

            if (
                activeMedia &&
                (videoVisibility.get(activeMedia) || 0) < 0.18
            ) {
                pauseMedia(activeMedia);
            }
        };

        if ("IntersectionObserver" in window) {
            videoObserver = new IntersectionObserver(
                (entries) => {
                    const activeCase = overlay.querySelector(
                        ".gp-case.is-active"
                    );

                    entries.forEach((entry) => {
                        const media = entry.target;

                        videoVisibility.set(media, entry.intersectionRatio);

                        if (
                            entry.isIntersecting &&
                            isOpen() &&
                            activeCase?.contains(media)
                        ) {
                            loadMedia(media);
                        }

                        if (entry.intersectionRatio < 0.18) {
                            userPausedMedia.delete(media);
                        }
                    });

                    syncVisibleVideo();
                },
                {
                    root: scroll,
                    rootMargin: "8% 0px 8% 0px",
                    threshold: [0, 0.15, 0.35, 0.52, 0.7, 0.85],
                }
            );

            overlay
                .querySelectorAll(".gp-media[data-video]")
                .forEach((media) => videoObserver.observe(media));
        }

        /* ---- Reveal al scrollear dentro del panel --------------------- */

        let revealObserver = null;

        if ("IntersectionObserver" in window) {
            revealObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("in");
                            revealObserver.unobserve(entry.target);
                        }
                    });
                },
                { root: scroll, threshold: 0.12, rootMargin: "0px 0px -8% 0px" }
            );
        }

        // (Re)prepara los reveals del case activo cada vez que se abre
        const primeReveals = (caseEl) => {
            caseEl.querySelectorAll(".gp-reveal").forEach((el) => {
                if (revealObserver) {
                    el.classList.remove("in");
                    revealObserver.observe(el);
                } else {
                    el.classList.add("in");
                }
            });
        };

        /* ---- Efectos ligados al scroll del panel (PagREF) ------------- */
        /* Fold: el video del hero se expande (--fold 0→1). Spiral: los
           numerales del recorrido giran. Todo con el scroll de este panel. */

        const clamp = (v, min, max) => Math.min(max, Math.max(min, v));

        let fxTicking = false;

        const updateFx = () => {
            fxTicking = false;

            const activeCase = overlay.querySelector(".gp-case.is-active");

            if (!activeCase || !scroll) {
                return;
            }

            const maxScroll = Math.max(
                1,
                scroll.scrollHeight - scroll.clientHeight
            );
            const caseProgress = clamp(scroll.scrollTop / maxScroll, 0, 1);
            const viewportRect = scroll.getBoundingClientRect();
            const viewportCenter =
                viewportRect.top + viewportRect.height * 0.52;

            overlay.style.setProperty(
                "--case-progress",
                caseProgress.toFixed(4)
            );

            if (progressValue) {
                progressValue.textContent = String(
                    Math.round(caseProgress * 100)
                ).padStart(2, "0");
            }

            let focusedStep = null;
            let closestDistance = Number.POSITIVE_INFINITY;
            const steps = activeCase.querySelectorAll("[data-case-step]");

            steps.forEach((step) => {
                const rect = step.getBoundingClientRect();
                const isVisible =
                    rect.bottom > viewportRect.top + viewportRect.height * 0.16 &&
                    rect.top < viewportRect.bottom - viewportRect.height * 0.16;
                const distance = Math.abs(
                    rect.top + rect.height / 2 - viewportCenter
                );

                if (isVisible && distance < closestDistance) {
                    closestDistance = distance;
                    focusedStep = step;
                }
            });

            steps.forEach((step) => {
                step.classList.toggle("is-focus", step === focusedStep);
            });

            if (reducedMotion) {
                return;
            }

            // Fold del hero: progreso del scroll dentro del tramo alto del hero
            const hero = activeCase.querySelector("[data-fold-hero]");

            if (hero) {
                const rel =
                    hero.getBoundingClientRect().top -
                    viewportRect.top;
                const travel = hero.offsetHeight - scroll.clientHeight;
                const progress = travel > 0 ? clamp(-rel / travel, 0, 1) : 0;
                const fold = clamp(progress / 0.6, 0, 1);
                hero.style.setProperty("--fold", fold.toFixed(3));
            }

            activeCase
                .querySelectorAll(".gp-step-index-orbit[data-spin]")
                .forEach((orbit) => {
                    const rect = orbit.getBoundingClientRect();
                    const progress = clamp(
                        (viewportRect.bottom - rect.top) /
                            (viewportRect.height + rect.height),
                        0,
                        1
                    );
                    const direction = Number(orbit.dataset.spin) || 1;
                    const angle = (progress - 0.5) * 38 * direction;

                    orbit.style.setProperty("--spin", angle.toFixed(2));
                });
        };

        const onPanelScroll = () => {
            if (!fxTicking) {
                window.requestAnimationFrame(updateFx);
                fxTicking = true;
            }
        };

        if (scroll) {
            scroll.addEventListener("scroll", onPanelScroll, { passive: true });
        }

        window.addEventListener("resize", onPanelScroll, { passive: true });

        /* ---- Mostrar / abrir / cerrar --------------------------------- */

        const showProject = (key) => {
            // Al cambiar de obra, corta cualquier video de la anterior
            pauseAllVideos();
            videoVisibility.clear();

            let found = false;

            cases.forEach((caseEl) => {
                const isActive = caseEl.dataset.projectCase === key;

                caseEl.classList.toggle("is-active", isActive);

                if (isActive) {
                    found = true;
                } else {
                    caseEl
                        .querySelectorAll("[data-case-step]")
                        .forEach((step) => step.classList.remove("is-focus"));
                }
            });

            return found;
        };

        const openDetail = (key, trigger) => {
            if (!showProject(key)) {
                return;
            }

            lastTrigger = trigger;
            document.documentElement.classList.add("detail-lock");
            document.body.classList.add("detail-lock");
            overlay.classList.add("is-open");
            overlay.setAttribute("aria-hidden", "false");

            if (scroll) {
                scroll.scrollTop = 0;
            }

            const activeCase = overlay.querySelector(".gp-case.is-active");

            if (activeCase) {
                primeReveals(activeCase);
            }

            // Estado inicial de los efectos (fold 0, numerales sin girar)
            updateFx();
            window.requestAnimationFrame(updateFx);

            // Espera a que el panel sea visible para poder enfocar
            window.setTimeout(() => closeButton?.focus(), 120);

            // La demo destacada arranca sola (muted) tras la entrada del panel,
            // salvo que se pida movimiento reducido.
            window.setTimeout(() => {
                if (reducedMotion || !activeCase) {
                    return;
                }

                const hero = activeCase.querySelector(".gp-media[data-featured]");

                if (hero) {
                    playMedia(hero);
                }
            }, 850);
        };

        const closeDetail = () => {
            pauseAllVideos();
            videoVisibility.clear();
            overlay.classList.remove("is-open");
            overlay.setAttribute("aria-hidden", "true");
            overlay.style.setProperty("--case-progress", "0");

            if (progressValue) {
                progressValue.textContent = "00";
            }

            document.documentElement.classList.remove("detail-lock");
            document.body.classList.remove("detail-lock");
            lastTrigger?.focus();
        };

        triggers.forEach((trigger) => {
            trigger.addEventListener("click", () => {
                openDetail(trigger.dataset.projectOpen, trigger);
            });
        });

        closeButton?.addEventListener("click", closeDetail);

        // Los enlaces del cierre llevan a otra sección: cierran antes de navegar
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

            // Es un diálogo modal: el foco se queda entre la barra y el case
            if (event.key !== "Tab") {
                return;
            }

            const focusables = overlay.querySelectorAll(
                '.gp-detail-top button, .gp-case.is-active a[href], .gp-case.is-active button, .gp-case.is-active [tabindex]:not([tabindex="-1"])'
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
