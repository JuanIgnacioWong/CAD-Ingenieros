(function () {
    'use strict';

    var BATCH_SIZE = 8;
    var MIN_PAIR_WIDTH = 150;
    var COLUMN_GAP = 10;
    var galleries = document.querySelectorAll('[data-cad-masonry-gallery]');

    if (!galleries.length) {
        return;
    }

    function prefersReducedMotion() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function readManifest(gallery) {
        var source = gallery.querySelector('[data-cad-gallery-manifest]');

        if (!source) {
            return [];
        }

        try {
            var parsed = JSON.parse(source.textContent || '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    }

    function galleryRatio(item) {
        var width = Number(item && item.width) || 1;
        var height = Number(item && item.height) || 1;
        return Math.max(0.05, width / height);
    }

    function formatRange(first, last, total) {
        var firstText = String(first + 1).padStart(2, '0');
        var lastText = String(last + 1).padStart(2, '0');
        return first === last ? firstText + ' / ' + String(total) : firstText + '–' + lastText + ' / ' + String(total);
    }

    function createImage(item, index) {
        var image = document.createElement('img');
        image.src = item.src || item.full || '';
        if (item.srcset) {
            image.srcset = item.srcset;
        }
        if (item.sizes) {
            image.sizes = item.sizes;
        }
        image.alt = item.alt || '';
        image.width = Number(item.width) || 1;
        image.height = Number(item.height) || 1;
        image.loading = index < 4 ? 'eager' : 'lazy';
        image.decoding = 'async';
        image.addEventListener('error', function () {
            image.closest('[data-cad-gallery-item]').setAttribute('data-image-error', 'true');
        });
        return image;
    }

    function createItem(item, index) {
        var figure = document.createElement('figure');
        var button = document.createElement('button');
        var caption = document.createElement('figcaption');
        var ratio = galleryRatio(item);

        figure.className = 'cad-masonry-gallery__item';
        figure.setAttribute('data-cad-gallery-item', '');
        figure.setAttribute('data-gallery-index', String(index));
        figure.style.setProperty('--cad-gallery-ratio', String(ratio));

        button.type = 'button';
        button.className = 'cad-masonry-gallery__trigger';
        button.setAttribute('data-gallery-open', '');
        button.setAttribute('data-gallery-index', String(index));
        button.setAttribute('aria-haspopup', 'dialog');
        button.setAttribute('aria-label', 'Ampliar imagen: ' + (item.alt || 'sin titulo'));
        button.setAttribute('data-full-src', item.full || item.src || '');
        button.appendChild(createImage(item, index));
        figure.appendChild(button);

        if (item.caption) {
            caption.className = 'cad-masonry-gallery__caption';
            caption.textContent = item.caption;
            figure.appendChild(caption);
        }

        return figure;
    }

    function createLightbox(gallery, manifest) {
        var overlay = document.createElement('div');
        var content = document.createElement('div');
        var close = document.createElement('button');
        var previous = document.createElement('button');
        var next = document.createElement('button');
        var image = document.createElement('img');
        var caption = document.createElement('p');
        var status = document.createElement('span');
        var activeIndex = 0;
        var returnFocus = null;
        var pointerStartX = null;

        overlay.className = 'cad-masonry-gallery__lightbox';
        overlay.hidden = true;
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', 'Galeria de imagenes');

        content.className = 'cad-masonry-gallery__lightbox-content';
        close.type = 'button';
        close.className = 'cad-masonry-gallery__lightbox-close';
        close.setAttribute('aria-label', 'Cerrar imagen');
        close.textContent = '×';
        previous.type = 'button';
        previous.className = 'cad-masonry-gallery__lightbox-nav';
        previous.setAttribute('aria-label', 'Imagen anterior');
        previous.textContent = '←';
        next.type = 'button';
        next.className = 'cad-masonry-gallery__lightbox-nav';
        next.setAttribute('aria-label', 'Imagen siguiente');
        next.textContent = '→';
        image.className = 'cad-masonry-gallery__lightbox-image';
        image.alt = '';
        caption.className = 'cad-masonry-gallery__lightbox-caption';
        status.className = 'cad-masonry-gallery__lightbox-status';
        status.setAttribute('aria-live', 'polite');

        content.appendChild(close);
        content.appendChild(previous);
        content.appendChild(image);
        content.appendChild(next);
        content.appendChild(caption);
        content.appendChild(status);
        overlay.appendChild(content);
        document.body.appendChild(overlay);

        function preload(index) {
            var item = manifest[(index + manifest.length) % manifest.length];
            if (!item || !item.full) {
                return;
            }
            var preloadImage = new Image();
            preloadImage.src = item.full;
        }

        function update(index) {
            activeIndex = (index + manifest.length) % manifest.length;
            var item = manifest[activeIndex] || {};

            image.src = item.full || item.src || '';
            image.alt = item.alt || '';
            image.width = Number(item.width) || 1;
            image.height = Number(item.height) || 1;
            caption.textContent = item.caption || '';
            status.textContent = formatRange(activeIndex, activeIndex, manifest.length);
            previous.hidden = manifest.length < 2;
            next.hidden = manifest.length < 2;
            preload(activeIndex - 1);
            preload(activeIndex + 1);
        }

        function closeLightbox() {
            if (overlay.hidden) {
                return;
            }
            overlay.hidden = true;
            document.body.classList.remove('cad-masonry-gallery-lightbox-open');
            image.removeAttribute('src');
            if (returnFocus) {
                returnFocus.focus();
            }
            returnFocus = null;
        }

        function moveFocus(event) {
            if ('Tab' !== event.key) {
                return;
            }
            var focusable = [close, previous, next].filter(function (element) {
                return !element.hidden;
            });
            var current = focusable.indexOf(document.activeElement);
            var target = event.shiftKey ? current - 1 : current + 1;
            if (target < 0) {
                target = focusable.length - 1;
            }
            if (target >= focusable.length) {
                target = 0;
            }
            event.preventDefault();
            focusable[target].focus();
        }

        function open(index, trigger) {
            returnFocus = trigger;
            update(index);
            overlay.hidden = false;
            document.body.classList.add('cad-masonry-gallery-lightbox-open');
            close.focus();
        }

        gallery.querySelector('[data-gallery-track]').addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-gallery-open]');
            if (!trigger) {
                return;
            }
            open(Number(trigger.getAttribute('data-gallery-index')) || 0, trigger);
        });

        close.addEventListener('click', closeLightbox);
        previous.addEventListener('click', function () { update(activeIndex - 1); });
        next.addEventListener('click', function () { update(activeIndex + 1); });
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                closeLightbox();
            }
        });
        overlay.addEventListener('keydown', function (event) {
            if ('Escape' === event.key) {
                event.preventDefault();
                closeLightbox();
            } else if ('ArrowLeft' === event.key) {
                event.preventDefault();
                update(activeIndex - 1);
            } else if ('ArrowRight' === event.key) {
                event.preventDefault();
                update(activeIndex + 1);
            } else {
                moveFocus(event);
            }
        });
        image.addEventListener('pointerdown', function (event) {
            pointerStartX = event.clientX;
        });
        image.addEventListener('pointerup', function (event) {
            if (null === pointerStartX || Math.abs(event.clientX - pointerStartX) < 40) {
                pointerStartX = null;
                return;
            }
            update(activeIndex + (event.clientX < pointerStartX ? 1 : -1));
            pointerStartX = null;
        });

        return {
            close: closeLightbox
        };
    }

    function initGallery(gallery) {
        var manifest = readManifest(gallery);
        var viewport = gallery.querySelector('[data-gallery-viewport]');
        var track = gallery.querySelector('[data-gallery-track]');
        var sentinel = gallery.querySelector('[data-gallery-sentinel]');
        var previous = gallery.querySelector('[data-gallery-prev]');
        var next = gallery.querySelector('[data-gallery-next]');
        var status = gallery.querySelector('[data-gallery-status]');
        var progress = gallery.querySelector('[data-gallery-progress]');
        var loading = gallery.querySelector('[data-gallery-loading]');
        var loadedCount = Math.min(BATCH_SIZE, manifest.length);
        var nodes = [];
        var columns = [];
        var frame = null;
        var loadingMore = false;

        if (!manifest.length || !viewport || !track || !sentinel) {
            return;
        }

        track.hidden = false;

        function galleryHeight() {
            var configured = parseFloat(window.getComputedStyle(gallery).getPropertyValue('--cad-masonry-height'));
            return configured || viewport.clientHeight || 280;
        }

        function pairWidth(first, second, height) {
            return (height - COLUMN_GAP) / ((1 / galleryRatio(first)) + (1 / galleryRatio(second)));
        }

        function canPair(first, second, height) {
            return galleryRatio(first) >= 1.08 && galleryRatio(second) >= 1.08 && pairWidth(first, second, height) >= MIN_PAIR_WIDTH;
        }

        function getFirstVisibleIndex() {
            var viewportRect = viewport.getBoundingClientRect();
            var first = nodes.find(function (node) {
                var rect = node.getBoundingClientRect();
                return rect.right > viewportRect.left + 2 && rect.left < viewportRect.right;
            });
            return first ? Number(first.getAttribute('data-gallery-index')) || 0 : 0;
        }

        function compose(restoreIndex) {
            var height = galleryHeight();
            var index = 0;
            var fragment = document.createDocumentFragment();

            columns = [];
            while (index < nodes.length) {
                var firstNode = nodes[index];
                var firstItem = manifest[index];
                var secondItem = manifest[index + 1];
                var isPair = secondItem && canPair(firstItem, secondItem, height);
                var width = isPair ? pairWidth(firstItem, secondItem, height) : height * galleryRatio(firstItem);
                var renderedWidth = isPair ? Math.max(MIN_PAIR_WIDTH, width) : width;
                var column = document.createElement('div');

                column.className = 'cad-masonry-gallery__column';
                column.style.width = String(renderedWidth) + 'px';
                column.style.setProperty('--cad-column-width', String(renderedWidth) + 'px');
                column.appendChild(firstNode);
                if (isPair) {
                    column.appendChild(nodes[index + 1]);
                    index += 2;
                } else {
                    index += 1;
                }
                columns.push(column);
                fragment.appendChild(column);
            }
            fragment.appendChild(sentinel);
            track.replaceChildren(fragment);

            if (typeof restoreIndex === 'number') {
                var anchor = track.querySelector('[data-gallery-index="' + String(restoreIndex) + '"]');
                if (anchor && anchor.parentElement) {
                    viewport.scrollLeft = Math.max(0, anchor.parentElement.offsetLeft - 4);
                }
            }
        }

        function buildNodes() {
            for (var index = nodes.length; index < loadedCount; index += 1) {
                nodes.push(createItem(manifest[index], index));
            }
        }

        function updateStatus() {
            var viewportRect = viewport.getBoundingClientRect();
            var visible = nodes.filter(function (node) {
                var rect = node.getBoundingClientRect();
                return rect.right > viewportRect.left + 2 && rect.left < viewportRect.right - 2;
            });
            var first = visible.length ? Number(visible[0].getAttribute('data-gallery-index')) || 0 : getFirstVisibleIndex();
            var last = visible.length ? Number(visible[visible.length - 1].getAttribute('data-gallery-index')) || first : first;
            var maxScroll = Math.max(0, viewport.scrollWidth - viewport.clientWidth);

            if (status) {
                status.textContent = formatRange(first, last, manifest.length);
            }
            if (progress) {
                var progressValue = Math.max(1, Math.min(manifest.length, last + 1));
                progress.style.width = String((progressValue / manifest.length) * 100) + '%';
                progress.parentElement.setAttribute('aria-valuenow', String(progressValue));
            }
            if (previous) {
                previous.disabled = viewport.scrollLeft <= 1;
            }
            if (next) {
                next.disabled = loadedCount >= manifest.length && viewport.scrollLeft >= maxScroll - 1;
            }
        }

        function checkLoadMore() {
            if (loadingMore || loadedCount >= manifest.length) {
                updateStatus();
                return;
            }

            var remaining = viewport.scrollWidth - viewport.scrollLeft - viewport.clientWidth;
            if (remaining > Math.max(viewport.clientWidth * 1.5, 320) && viewport.scrollWidth > viewport.clientWidth + 4) {
                updateStatus();
                return;
            }

            loadingMore = true;
            if (loading) {
                loading.hidden = false;
            }
            var anchor = getFirstVisibleIndex();
            loadedCount = Math.min(manifest.length, loadedCount + BATCH_SIZE);
            buildNodes();
            compose(anchor);
            loadingMore = false;
            if (loading) {
                loading.hidden = true;
            }
            updateStatus();
        }

        function scheduleUpdate() {
            if (frame !== null) {
                return;
            }
            frame = window.requestAnimationFrame(function () {
                frame = null;
                checkLoadMore();
                updateStatus();
            });
        }

        function move(direction) {
            var current = viewport.scrollLeft;
            var target = null;

            if (direction > 0) {
                target = columns.find(function (column) {
                    return column.offsetLeft > current + 2;
                });
                if (!target && loadedCount < manifest.length) {
                    checkLoadMore();
                    window.setTimeout(function () { move(direction); }, 0);
                    return;
                }
            } else {
                for (var index = columns.length - 1; index >= 0; index -= 1) {
                    if (columns[index].offsetLeft < current - 2) {
                        target = columns[index];
                        break;
                    }
                }
            }

            var left = target ? target.offsetLeft : (direction > 0 ? viewport.scrollWidth - viewport.clientWidth : 0);
            viewport.scrollTo({ left: Math.max(0, left), behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
        }

        function ensureViewportFilled() {
            if (loadedCount >= manifest.length) {
                return;
            }
            while (loadedCount < manifest.length && viewport.scrollWidth <= viewport.clientWidth + 4) {
                loadedCount = Math.min(manifest.length, loadedCount + BATCH_SIZE);
                buildNodes();
                compose(0);
            }
            updateStatus();
        }

        buildNodes();
        compose(0);
        ensureViewportFilled();
        createLightbox(gallery, manifest);

        viewport.addEventListener('scroll', scheduleUpdate, { passive: true });
        viewport.addEventListener('keydown', function (event) {
            if ('ArrowLeft' === event.key) {
                event.preventDefault();
                move(-1);
            } else if ('ArrowRight' === event.key) {
                event.preventDefault();
                move(1);
            }
        });
        if (previous) {
            previous.addEventListener('click', function () { move(-1); });
        }
        if (next) {
            next.addEventListener('click', function () { move(1); });
        }

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                if (entries.some(function (entry) { return entry.isIntersecting; })) {
                    checkLoadMore();
                }
            }, { root: viewport, rootMargin: '0px 900px 0px 0px' });
            observer.observe(sentinel);
        }

        if ('ResizeObserver' in window) {
            var resizeObserver = new ResizeObserver(function () {
                var anchor = getFirstVisibleIndex();
                compose(anchor);
                ensureViewportFilled();
            });
            resizeObserver.observe(viewport);
        } else {
            window.addEventListener('resize', function () {
                var anchor = getFirstVisibleIndex();
                compose(anchor);
                ensureViewportFilled();
            });
        }

        updateStatus();
    }

    Array.prototype.forEach.call(galleries, initGallery);
}());
