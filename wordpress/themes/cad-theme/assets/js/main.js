(function () {
    var body = document.body;
    var toggleButton = document.querySelector('[data-menu-toggle]');
    var overlay = document.querySelector('[data-menu-overlay]');
    var panel = document.querySelector('[data-menu-panel]');

    function closeMenu() {
        body.classList.remove('menu-open');
        if (toggleButton) {
            toggleButton.setAttribute('aria-expanded', 'false');
        }
    }

    function openMenu() {
        body.classList.add('menu-open');
        if (toggleButton) {
            toggleButton.setAttribute('aria-expanded', 'true');
        }
    }

    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            if (body.classList.contains('menu-open')) {
                closeMenu();
                return;
            }

            openMenu();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeMenu);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    if (panel) {
        var panelLinks = panel.querySelectorAll('a');
        panelLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (link.hasAttribute('data-submenu-toggle')) {
                    return;
                }
                closeMenu();
            });
        });
    }

    var video = document.querySelector('[data-hero-video]');
    var videoButton = document.querySelector('[data-video-toggle]');
    var labelPlay = videoButton ? videoButton.getAttribute('data-label-play') : null;
    var labelPause = videoButton ? videoButton.getAttribute('data-label-pause') : null;
    var isEmbed = video && video.tagName === 'IFRAME';
    var embedSrc = isEmbed ? video.getAttribute('data-video-src') : null;

    function setVideoButtonLabel(playing) {
        if (!videoButton) {
            return;
        }

        var pauseText = labelPause || 'Pausar video';
        var playText = labelPlay || 'Activar video';
        videoButton.textContent = playing ? pauseText : playText;
    }

    if (video && videoButton) {
        var playing = true;

        videoButton.addEventListener('click', function () {
            if (isEmbed) {
                if (playing) {
                    video.setAttribute('src', '');
                    body.classList.add('is-video-paused');
                } else {
                    video.setAttribute('src', embedSrc || '');
                    body.classList.remove('is-video-paused');
                }
            } else {
                if (playing) {
                    video.pause();
                    body.classList.add('is-video-paused');
                } else {
                    video.play();
                    body.classList.remove('is-video-paused');
                }
            }

            playing = !playing;
            setVideoButtonLabel(playing);
        });

        setVideoButtonLabel(true);
    }

    var sidebarMenu = document.querySelector('.cad-menu');
    if (!sidebarMenu) {
        sidebarMenu = null;
    }

    if (sidebarMenu) {
        var parentItems = sidebarMenu.querySelectorAll('.menu-item-has-children');
        parentItems.forEach(function (item) {
            var link = item.querySelector(':scope > a');
            if (!link) {
                return;
            }

            link.setAttribute('data-submenu-toggle', 'true');
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-expanded', item.classList.contains('is-open') ? 'true' : 'false');

            link.addEventListener('click', function (event) {
                event.preventDefault();
                var isOpen = item.classList.toggle('is-open');
                link.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });
    }

    function initFeaturedProjects() {
        var carousels = document.querySelectorAll('[data-featured-projects]');
        if (!carousels.length) {
            return;
        }

        var reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

        carousels.forEach(function (carousel) {
            var viewport = carousel.querySelector('[data-featured-projects-viewport]');
            var track = carousel.querySelector('[data-featured-projects-track]');
            var controls = carousel.querySelector('[data-featured-projects-controls]');
            var prev = carousel.querySelector('[data-featured-projects-prev]');
            var next = carousel.querySelector('[data-featured-projects-next]');
            var status = carousel.querySelector('[data-featured-projects-status]');
            var cards = track ? Array.prototype.slice.call(track.querySelectorAll('.cad-featured-project')) : [];
            var resizeTimer = null;

            if (!viewport || !track || !controls || !prev || !next || !status || !cards.length) {
                return;
            }

            function getMaxScroll() {
                return Math.max(0, track.scrollWidth - track.clientWidth - 1);
            }

            function hasOverflow() {
                return getMaxScroll() > 1;
            }

            function getCardScrollLeft(card) {
                return Math.max(0, card.offsetLeft - track.offsetLeft);
            }

            function getActiveIndex() {
                var scrollLeft = track.scrollLeft;
                var activeIndex = 0;
                var activeDistance = Infinity;

                cards.forEach(function (card, index) {
                    var distance = Math.abs(getCardScrollLeft(card) - scrollLeft);
                    if (distance < activeDistance) {
                        activeDistance = distance;
                        activeIndex = index;
                    }
                });

                return activeIndex;
            }

            function scrollToIndex(index) {
                var targetIndex = Math.min(Math.max(index, 0), cards.length - 1);
                var target = cards[targetIndex];
                if (!target) {
                    return;
                }

                track.scrollTo({
                    left: getCardScrollLeft(target),
                    behavior: reducedMotionQuery.matches ? 'auto' : 'smooth'
                });
                requestUpdate();
            }

            function updateState() {
                var overflow = hasOverflow();
                controls.hidden = !overflow;

                if (!overflow) {
                    prev.disabled = true;
                    next.disabled = true;
                    status.textContent = '';
                    return;
                }

                var activeIndex = getActiveIndex();
                prev.disabled = track.scrollLeft <= 1;
                next.disabled = track.scrollLeft >= getMaxScroll();
                status.textContent = String(activeIndex + 1) + ' / ' + String(cards.length);
            }

            function requestUpdate() {
                window.clearTimeout(resizeTimer);
                resizeTimer = window.setTimeout(updateState, 80);
            }

            prev.addEventListener('click', function () {
                scrollToIndex(getActiveIndex() - 1);
            });

            next.addEventListener('click', function () {
                scrollToIndex(getActiveIndex() + 1);
            });

            viewport.addEventListener('keydown', function (event) {
                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    scrollToIndex(getActiveIndex() - 1);
                } else if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    scrollToIndex(getActiveIndex() + 1);
                } else if (event.key === 'Home') {
                    event.preventDefault();
                    scrollToIndex(0);
                } else if (event.key === 'End') {
                    event.preventDefault();
                    scrollToIndex(cards.length - 1);
                }
            });

            track.addEventListener('scroll', requestUpdate, { passive: true });
            window.addEventListener('resize', requestUpdate);

            if ('ResizeObserver' in window) {
                var observer = new ResizeObserver(requestUpdate);
                observer.observe(track);
                observer.observe(viewport);
            }

            updateState();
        });
    }

    initFeaturedProjects();

    function initClientsCarousel() {
        var carousels = document.querySelectorAll('[data-clients-carousel]');
        if (!carousels.length) {
            return;
        }

        carousels.forEach(function (carousel) {
            var track = carousel.querySelector('[data-clients-track]');
            var prev = carousel.querySelector('[data-clients-prev]');
            var next = carousel.querySelector('[data-clients-next]');
            if (!track) {
                return;
            }

            function getScrollStep() {
                var card = track.querySelector('.cad-client-card');
                if (!card) {
                    return track.clientWidth;
                }
                var styles = window.getComputedStyle(track);
                var gapValue = styles.columnGap || styles.gap || '0';
                var gap = parseFloat(gapValue) || 0;
                return card.getBoundingClientRect().width + gap;
            }

            function updateButtons() {
                var maxScroll = track.scrollWidth - track.clientWidth - 1;
                if (prev) {
                    prev.disabled = track.scrollLeft <= 0;
                }
                if (next) {
                    next.disabled = track.scrollLeft >= maxScroll;
                }
            }

            function scrollByStep(direction) {
                var amount = getScrollStep();
                track.scrollBy({ left: direction * amount, behavior: 'smooth' });
            }

            if (prev) {
                prev.addEventListener('click', function () {
                    scrollByStep(-1);
                });
            }

            if (next) {
                next.addEventListener('click', function () {
                    scrollByStep(1);
                });
            }

            track.addEventListener('scroll', updateButtons, { passive: true });
            window.addEventListener('resize', updateButtons);
            updateButtons();
        });
    }

    initClientsCarousel();

    function initBusinessCarousel() {
        var carousels = document.querySelectorAll('[data-business-carousel]');
        if (!carousels.length) {
            return;
        }

        var mobileQuery = window.matchMedia('(max-width: 782px)');
        var reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        var finePointerQuery = window.matchMedia('(pointer: fine)');

        carousels.forEach(function (carousel) {
            var track = carousel.querySelector('[data-business-track]');
            var pagination = carousel.querySelector('[data-business-pagination]');
            var slides = track ? Array.prototype.slice.call(track.querySelectorAll('.cad-business-card')) : [];
            var dots = [];

            if (!track || !slides.length) {
                return;
            }

            slides.forEach(function (card) {
                initializeBusinessCardDesign(card, reducedMotionQuery, finePointerQuery);
            });

            function getScrollStep() {
                var card = slides[0];
                if (!card) {
                    return track.clientWidth;
                }

                var styles = window.getComputedStyle(track);
                var gapValue = styles.columnGap || styles.gap || '0';
                var gap = parseFloat(gapValue) || 0;
                return card.getBoundingClientRect().width + gap;
            }

            function getActiveIndex() {
                var step = getScrollStep();
                if (!step) {
                    return 0;
                }

                return Math.min(Math.max(Math.round(track.scrollLeft / step), 0), slides.length - 1);
            }

            function setActiveSlide(index) {
                slides.forEach(function (slide, slideIndex) {
                    slide.classList.toggle('is-active', !mobileQuery.matches || slideIndex === index);
                });

                dots.forEach(function (dot, dotIndex) {
                    var isActive = dotIndex === index;
                    dot.classList.toggle('is-active', isActive);
                    dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                });
            }

            function updateState() {
                setActiveSlide(getActiveIndex());
            }

            function scrollToIndex(index) {
                track.scrollTo({ left: getScrollStep() * index, behavior: 'smooth' });
            }

            if (pagination && slides.length > 1) {
                slides.forEach(function (_, index) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'cad-business-carousel__dot';
                    dot.setAttribute('aria-label', 'Ver tarjeta ' + String(index + 1));
                    dot.setAttribute('aria-current', 'false');
                    dot.addEventListener('click', function () {
                        scrollToIndex(index);
                    });
                    pagination.appendChild(dot);
                    dots.push(dot);
                });
            }

            track.addEventListener('scroll', updateState, { passive: true });
            window.addEventListener('resize', updateState);
            if (typeof mobileQuery.addEventListener === 'function') {
                mobileQuery.addEventListener('change', updateState);
            } else if (typeof mobileQuery.addListener === 'function') {
                mobileQuery.addListener(updateState);
            }

            setActiveSlide(0);
            updateState();
        });
    }

    function initializeBusinessCardDesign(card, reducedMotionQuery, finePointerQuery) {
        if (!card) {
            return;
        }

        if (card.dataset.businessDesignInitialized === 'true') {
            return;
        }

        card.dataset.businessDesignInitialized = 'true';
        card.classList.add('is-design-ready');

        var media = card.querySelector('[data-business-card-media]');
        if (!media) {
            return;
        }

        var styles = window.getComputedStyle(card);
        var baseScale = parseFloat(styles.getPropertyValue('--cad-card-media-scale')) || 1.02;

        if (reducedMotionQuery.matches || !finePointerQuery.matches) {
            media.style.transform = reducedMotionQuery.matches ? 'none' : 'scale(' + baseScale + ')';
            return;
        }

        var frameId = null;
        var pointerX = 0;
        var pointerY = 0;

        function renderParallax() {
            frameId = null;
            media.style.transform = 'scale(' + baseScale + ') translate(' + pointerX + 'px, ' + pointerY + 'px)';
        }

        function handlePointerMove(event) {
            var rect = card.getBoundingClientRect();

            if (!rect.width || !rect.height) {
                return;
            }

            var normalizedX = (event.clientX - rect.left) / rect.width - 0.5;
            var normalizedY = (event.clientY - rect.top) / rect.height - 0.5;

            pointerX = normalizedX * -10;
            pointerY = normalizedY * -8;

            if (frameId !== null) {
                return;
            }

            frameId = window.requestAnimationFrame(renderParallax);
        }

        function handlePointerLeave() {
            pointerX = 0;
            pointerY = 0;

            if (frameId !== null) {
                window.cancelAnimationFrame(frameId);
                frameId = null;
            }

            media.style.transform = 'scale(' + baseScale + ')';
        }

        card.addEventListener('pointermove', handlePointerMove, { passive: true });
        card.addEventListener('pointerleave', handlePointerLeave);
    }

    initBusinessCarousel();

    function initProjectGallery() {
        var galleries = document.querySelectorAll('[data-project-gallery-grid]');
        if (!galleries.length) {
            return;
        }

        galleries.forEach(function (grid) {
            var items = Array.prototype.slice.call(grid.querySelectorAll('[data-gallery-item]'));
            if (!items.length) {
                return;
            }

            var perPage = parseInt(grid.getAttribute('data-gallery-per-page'), 10);
            if (!perPage || perPage < 1) {
                perPage = 6;
            }

            var totalPages = Math.max(1, Math.ceil(items.length / perPage));
            var block = grid.closest('.cad-project-block--gallery');
            var prev = block ? block.querySelector('[data-gallery-prev]') : null;
            var next = block ? block.querySelector('[data-gallery-next]') : null;
            var status = block ? block.querySelector('[data-gallery-status]') : null;
            var currentPage = 0;

            function renderPage(page) {
                currentPage = Math.min(Math.max(page, 0), totalPages - 1);

                items.forEach(function (item, index) {
                    var itemPage = Math.floor(index / perPage);
                    if (itemPage === currentPage) {
                        item.removeAttribute('hidden');
                        return;
                    }
                    item.setAttribute('hidden', 'hidden');
                });

                if (status) {
                    status.textContent = String(currentPage + 1) + ' / ' + String(totalPages);
                }
                if (prev) {
                    prev.disabled = currentPage <= 0;
                }
                if (next) {
                    next.disabled = currentPage >= totalPages - 1;
                }
            }

            if (prev) {
                prev.addEventListener('click', function () {
                    renderPage(currentPage - 1);
                });
            }

            if (next) {
                next.addEventListener('click', function () {
                    renderPage(currentPage + 1);
                });
            }

            renderPage(0);
        });
    }

    initProjectGallery();

    function initBusinessAreaGalleryLightbox() {
        var galleries = document.querySelectorAll('[data-business-gallery]');
        if (!galleries.length || typeof document.createElement('dialog').showModal !== 'function') {
            return;
        }

        galleries.forEach(function (gallery, galleryIndex) {
            var triggers = Array.prototype.slice.call(gallery.querySelectorAll('[data-business-gallery-trigger]'));
            if (!triggers.length) {
                return;
            }

            var dialog = document.createElement('dialog');
            var titleId = 'cad-business-gallery-lightbox-title-' + String(galleryIndex);
            dialog.className = 'cad-business-area__lightbox';
            dialog.setAttribute('aria-labelledby', titleId);
            dialog.innerHTML =
                '<div class="cad-business-area__lightbox-content">' +
                    '<button type="button" class="cad-business-area__lightbox-close" aria-label="Cerrar imagen">&times;</button>' +
                    '<button type="button" class="cad-business-area__lightbox-nav" data-lightbox-prev aria-label="Imagen anterior">&larr;</button>' +
                    '<div class="cad-business-area__lightbox-image-wrap">' +
                        '<h2 id="' + titleId + '" class="screen-reader-text">Imagen ampliada</h2>' +
                        '<img class="cad-business-area__lightbox-image" alt="">' +
                        '<p class="cad-business-area__lightbox-caption"></p>' +
                        '<span class="cad-business-area__lightbox-status" aria-live="polite"></span>' +
                    '</div>' +
                    '<button type="button" class="cad-business-area__lightbox-nav" data-lightbox-next aria-label="Imagen siguiente">&rarr;</button>' +
                '</div>';
            document.body.appendChild(dialog);

            var image = dialog.querySelector('.cad-business-area__lightbox-image');
            var caption = dialog.querySelector('.cad-business-area__lightbox-caption');
            var status = dialog.querySelector('.cad-business-area__lightbox-status');
            var closeButton = dialog.querySelector('.cad-business-area__lightbox-close');
            var previousButton = dialog.querySelector('[data-lightbox-prev]');
            var nextButton = dialog.querySelector('[data-lightbox-next]');
            var currentIndex = 0;
            var activeTrigger = null;

            if (triggers.length < 2) {
                previousButton.hidden = true;
                nextButton.hidden = true;
            }

            function updateImage(index) {
                currentIndex = (index + triggers.length) % triggers.length;
                var trigger = triggers[currentIndex];
                var source = trigger.getAttribute('data-full-src');
                var thumbnail = trigger.querySelector('img');

                image.src = source || '';
                image.alt = thumbnail ? thumbnail.getAttribute('alt') || '' : '';
                caption.textContent = trigger.getAttribute('data-caption') || '';
                status.textContent = String(currentIndex + 1) + ' / ' + String(triggers.length);
            }

            function closeDialog() {
                if (dialog.open) {
                    dialog.close();
                    return;
                }
                document.body.classList.remove('cad-business-area-lightbox-open');
            }

            triggers.forEach(function (trigger, index) {
                trigger.addEventListener('click', function () {
                    activeTrigger = trigger;
                    updateImage(index);
                    dialog.showModal();
                    document.body.classList.add('cad-business-area-lightbox-open');
                    closeButton.focus();
                });
            });

            closeButton.addEventListener('click', closeDialog);
            previousButton.addEventListener('click', function () {
                updateImage(currentIndex - 1);
            });
            nextButton.addEventListener('click', function () {
                updateImage(currentIndex + 1);
            });

            dialog.addEventListener('click', function (event) {
                if (event.target === dialog) {
                    closeDialog();
                }
            });

            dialog.addEventListener('cancel', function (event) {
                event.preventDefault();
                closeDialog();
            });

            dialog.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' || event.key === 'Esc') {
                    event.preventDefault();
                    closeDialog();
                } else if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    updateImage(currentIndex - 1);
                } else if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    updateImage(currentIndex + 1);
                }
            });

            document.addEventListener('keydown', function (event) {
                if ((event.key === 'Escape' || event.key === 'Esc') && dialog.open) {
                    event.preventDefault();
                    closeDialog();
                }
            });

            dialog.addEventListener('close', function () {
                document.body.classList.remove('cad-business-area-lightbox-open');
                image.removeAttribute('src');
                if (activeTrigger) {
                    activeTrigger.focus();
                }
            });
        });
    }

    initBusinessAreaGalleryLightbox();

    function initIndicatorCounters() {
        var section = document.querySelector('#indicadores');
        if (!section) {
            return;
        }

        var values = Array.prototype.slice.call(section.querySelectorAll('.cad-indicator-card__value[data-count]'));
        if (!values.length) {
            return;
        }

        function formatCount(value, separator) {
            var count = Math.max(0, Math.round(value));
            if (!separator) {
                return String(count);
            }

            return String(count).replace(/\B(?=(\d{3})+(?!\d))/g, separator);
        }

        function buildValueText(element, value) {
            var prefix = element.getAttribute('data-prefix') || '';
            var suffix = element.getAttribute('data-suffix') || '';
            var separator = element.getAttribute('data-separator') || '';
            var numeric = formatCount(value, separator);
            var suffixSpacer = suffix && !/^[\s\+\-%]/.test(suffix) ? ' ' : '';
            var prefixSpacer = prefix && /[A-Za-z0-9ÁÉÍÓÚÜÑáéíóúüñ]$/.test(prefix) ? ' ' : '';

            if (!prefix) {
                return numeric + suffixSpacer + suffix;
            }

            return prefix + prefixSpacer + numeric + suffixSpacer + suffix;
        }

        function setFinalValues() {
            values.forEach(function (element) {
                var target = parseInt(element.getAttribute('data-count'), 10);
                if (!Number.isFinite(target)) {
                    return;
                }

                element.textContent = buildValueText(element, target);
            });
        }

        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            setFinalValues();
            return;
        }

        function animateCounter(element) {
            var target = parseInt(element.getAttribute('data-count'), 10);
            if (!Number.isFinite(target)) {
                return;
            }

            var duration = 1500;
            var startTime = null;

            function step(timestamp) {
                if (null === startTime) {
                    startTime = timestamp;
                }

                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                var current = Math.round(target * eased);
                element.textContent = buildValueText(element, current);

                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            }

            element.textContent = buildValueText(element, 0);
            window.requestAnimationFrame(step);
        }

        if (!('IntersectionObserver' in window)) {
            setFinalValues();
            return;
        }

        var hasAnimated = false;
        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || hasAnimated) {
                        return;
                    }

                    hasAnimated = true;
                    values.forEach(animateCounter);
                    observer.disconnect();
                });
            },
            {
                threshold: 0.35,
                rootMargin: '0px 0px -10% 0px',
            }
        );

        observer.observe(section);
    }

    initIndicatorCounters();

    var sectionNav = document.querySelector('[data-section-nav]');
    if (!sectionNav) {
        return;
    }

    var navLinks = Array.prototype.slice.call(sectionNav.querySelectorAll('a'));
    var sections = navLinks
        .map(function (link) {
            var id = link.getAttribute('href');
            if (!id || id.charAt(0) !== '#') {
                return null;
            }
            return document.querySelector(id);
        })
        .filter(Boolean);

    if (!sections.length || !('IntersectionObserver' in window)) {
        return;
    }

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var activeId = '#' + entry.target.id;
                navLinks.forEach(function (link) {
                    if (link.getAttribute('href') === activeId) {
                        link.classList.add('is-active');
                        return;
                    }

                    link.classList.remove('is-active');
                });
            });
        },
        {
            rootMargin: '-30% 0px -55% 0px',
            threshold: 0.1,
        }
    );

    sections.forEach(function (section) {
        observer.observe(section);
    });
})();
