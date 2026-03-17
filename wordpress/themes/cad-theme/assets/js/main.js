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
