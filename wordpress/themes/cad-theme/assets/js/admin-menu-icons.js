(function () {
    var icons = window.cadMenuIcons || [];
    var activeInput = null;
    var modal = null;
    var grid = null;

    function buildModal() {
        modal = document.createElement('div');
        modal.className = 'cad-icon-modal';
        modal.innerHTML =
            '<div class="cad-icon-modal__backdrop" data-cad-icon-close></div>' +
            '<div class="cad-icon-modal__panel" role="dialog" aria-modal="true" aria-label="Selector de iconos">' +
            '  <div class="cad-icon-modal__header">' +
            '    <span>Selector de iconos</span>' +
            '    <button type="button" class="button-link" data-cad-icon-close>Cerrar</button>' +
            '  </div>' +
            '  <div class="cad-icon-modal__search">' +
            '    <input type="text" placeholder="Buscar icono" class="cad-icon-modal__input" />' +
            '  </div>' +
            '  <div class="cad-icon-modal__grid"></div>' +
            '</div>';

        document.body.appendChild(modal);
        grid = modal.querySelector('.cad-icon-modal__grid');

        modal.addEventListener('click', function (event) {
            if (event.target && event.target.hasAttribute('data-cad-icon-close')) {
                closeModal();
            }
        });

        var search = modal.querySelector('.cad-icon-modal__input');
        if (search) {
            search.addEventListener('input', function () {
                renderIcons(search.value);
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    }

    function renderIcons(filter) {
        if (!grid) {
            return;
        }

        var query = (filter || '').toLowerCase();
        grid.innerHTML = '';

        icons
            .filter(function (name) {
                return !query || name.indexOf(query) !== -1;
            })
            .forEach(function (name) {
                var button = document.createElement('button');
                button.type = 'button';
                button.className = 'cad-icon-modal__item';
                button.setAttribute('data-icon-name', name);
                button.innerHTML =
                    '<span class="material-symbols-outlined" aria-hidden="true">' + name + '</span>' +
                    '<span class="cad-icon-modal__label">' + name + '</span>';
                button.addEventListener('click', function () {
                    if (activeInput) {
                        activeInput.value = name;
                        activeInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    closeModal();
                });
                grid.appendChild(button);
            });
    }

    function openModal(input) {
        activeInput = input;
        if (!modal) {
            buildModal();
        }
        renderIcons('');
        modal.classList.add('is-open');
        var search = modal.querySelector('.cad-icon-modal__input');
        if (search) {
            search.value = '';
            search.focus();
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.remove('is-open');
        }
        activeInput = null;
    }

    document.addEventListener('click', function (event) {
        var target = event.target;
        if (!target) {
            return;
        }

        if (target.classList.contains('cad-menu-icon-picker')) {
            event.preventDefault();
            var inputId = target.getAttribute('data-target');
            if (!inputId) {
                return;
            }
            var input = document.getElementById(inputId);
            if (!input) {
                return;
            }
            openModal(input);
        }
    });
})();
