(function () {
    var hasMediaLibrary = typeof wp !== 'undefined' && wp.media;
    var iconPickerModal = document.getElementById('cad-project-icon-modal');
    var iconPickerActiveField = null;
    var iconPickerActiveTrigger = null;

    function openMediaSelector(options) {
        if (!hasMediaLibrary) {
            return;
        }

        var frame = wp.media({
            title: options.title || 'Seleccionar archivo',
            button: { text: options.button || 'Usar este archivo' },
            library: options.type ? { type: options.type } : undefined,
            multiple: Boolean(options.multiple),
        });

        frame.on('select', function () {
            if (options.multiple) {
                var selection = frame.state().get('selection').toJSON();
                options.onSelect(selection || []);
                return;
            }

            var attachment = frame.state().get('selection').first();
            if (!attachment) {
                return;
            }
            options.onSelect(attachment.toJSON());
        });

        frame.open();
    }

    function getNextIndex(list) {
        var items = list.querySelectorAll('.cad-repeatable__item');
        var maxIndex = -1;
        items.forEach(function (item) {
            var current = parseInt(item.getAttribute('data-index'), 10);
            if (!isNaN(current) && current > maxIndex) {
                maxIndex = current;
            }
        });
        return maxIndex + 1;
    }

    function handleAdd(event) {
        var button = event.target.closest('[data-repeatable-add]');
        if (!button) {
            return;
        }

        var type = button.getAttribute('data-repeatable-add');
        var container = button.closest('.cad-repeatable');
        if (!container) {
            return;
        }

        var list = container.querySelector('.cad-repeatable__list');
        var template = container.querySelector('template[data-repeatable-template="' + type + '"]');
        if (!list || !template) {
            return;
        }

        var index = getNextIndex(list);
        var html = template.innerHTML.replace(/__INDEX__/g, String(index));
        list.insertAdjacentHTML('beforeend', html);
    }

    function handleRemove(event) {
        var button = event.target.closest('.cad-repeatable__remove');
        if (!button) {
            return;
        }

        var item = button.closest('.cad-repeatable__item');
        if (item) {
            item.remove();
        }
    }

    function handleDocumentMedia(event) {
        var button = event.target.closest('.cad-media-select');
        if (!button) {
            return;
        }

        event.preventDefault();
        var targetId = button.getAttribute('data-media-target');
        var targetInput = targetId ? document.getElementById(targetId) : null;
        if (!targetInput) {
            return;
        }

        openMediaSelector({
            title: 'Seleccionar documento',
            button: 'Usar este documento',
            type: button.getAttribute('data-media-type') || undefined,
            multiple: false,
            onSelect: function (attachment) {
                if (attachment && attachment.url) {
                    targetInput.value = attachment.url;
                    targetInput.dispatchEvent(new Event('change'));
                }
            },
        });
    }

    function handleGallerySelect(event) {
        var button = event.target.closest('.cad-project-gallery__select');
        if (!button) {
            return;
        }

        event.preventDefault();
        var gallery = button.closest('[data-project-gallery]');
        if (!gallery) {
            return;
        }

        var input = gallery.querySelector('[data-gallery-ids-input]');
        var preview = gallery.querySelector('[data-project-gallery-preview]');
        if (!input || !preview) {
            return;
        }

        openMediaSelector({
            title: 'Seleccionar imagenes',
            button: 'Usar imagenes',
            type: 'image',
            multiple: true,
            onSelect: function (attachments) {
                var ids = [];
                preview.innerHTML = '';

                attachments.forEach(function (attachment) {
                    if (!attachment || !attachment.id) {
                        return;
                    }
                    ids.push(String(attachment.id));

                    var url = attachment.url;
                    if (attachment.sizes && attachment.sizes.thumbnail) {
                        url = attachment.sizes.thumbnail.url;
                    }

                    var item = document.createElement('div');
                    item.className = 'cad-project-gallery__item';
                    item.innerHTML = '<img src="' + url + '" alt="">';
                    preview.appendChild(item);
                });

                input.value = ids.join(',');
            },
        });
    }

    function handleGalleryClear(event) {
        var button = event.target.closest('.cad-project-gallery__clear');
        if (!button) {
            return;
        }

        event.preventDefault();
        var gallery = button.closest('[data-project-gallery]');
        if (!gallery) {
            return;
        }

        var input = gallery.querySelector('[data-gallery-ids-input]');
        var preview = gallery.querySelector('[data-project-gallery-preview]');
        if (input) {
            input.value = '';
        }
        if (preview) {
            preview.innerHTML = '';
        }
    }

    function getIconPickerFieldParts(field) {
        if (!field) {
            return null;
        }

        return {
            input: field.querySelector('[data-icon-picker-input]'),
            previewIcon: field.querySelector('[data-icon-picker-preview-icon]'),
            previewText: field.querySelector('[data-icon-picker-preview-text]'),
        };
    }

    function markIconPickerSelection(selectedValue) {
        if (!iconPickerModal) {
            return;
        }

        var options = iconPickerModal.querySelectorAll('[data-icon-option]');
        options.forEach(function (option) {
            var value = option.getAttribute('data-icon-value') || '';
            var isSelected = value === selectedValue;
            option.classList.toggle('is-selected', isSelected);
            option.setAttribute('aria-selected', isSelected ? 'true' : 'false');
        });
    }

    function updateIconPickerPreview(field, value, label) {
        var parts = getIconPickerFieldParts(field);
        if (!parts || !parts.input) {
            return;
        }

        parts.input.value = value;
        if (parts.previewIcon) {
            parts.previewIcon.textContent = value;
        }
        if (parts.previewText) {
            parts.previewText.textContent = label;
        }
        parts.input.dispatchEvent(new Event('change'));
    }

    function openIconPicker(field, trigger) {
        if (!iconPickerModal) {
            return;
        }

        var parts = getIconPickerFieldParts(field);
        if (!parts || !parts.input) {
            return;
        }

        iconPickerActiveField = field;
        iconPickerActiveTrigger = trigger || null;
        if (iconPickerActiveTrigger) {
            iconPickerActiveTrigger.setAttribute('aria-expanded', 'true');
        }

        markIconPickerSelection(parts.input.value || '');
        iconPickerModal.hidden = false;
        document.body.classList.add('cad-project-icon-modal-open');

        var targetOption = iconPickerModal.querySelector('.cad-project-icon-modal__option.is-selected') || iconPickerModal.querySelector('[data-icon-option]');
        if (targetOption) {
            targetOption.focus();
        }
    }

    function closeIconPicker() {
        if (!iconPickerModal || iconPickerModal.hidden) {
            return;
        }

        iconPickerModal.hidden = true;
        document.body.classList.remove('cad-project-icon-modal-open');

        if (iconPickerActiveTrigger) {
            iconPickerActiveTrigger.setAttribute('aria-expanded', 'false');
            iconPickerActiveTrigger.focus();
        }

        iconPickerActiveField = null;
        iconPickerActiveTrigger = null;
    }

    function handleIconPicker(event) {
        var openTrigger = event.target.closest('[data-icon-picker-open]');
        if (openTrigger) {
            event.preventDefault();
            openIconPicker(openTrigger.closest('[data-icon-picker-field]'), openTrigger);
            return;
        }

        var closeTrigger = event.target.closest('[data-icon-picker-close]');
        if (closeTrigger) {
            event.preventDefault();
            closeIconPicker();
            return;
        }

        var iconOption = event.target.closest('[data-icon-option]');
        if (iconOption && iconPickerActiveField) {
            event.preventDefault();
            var value = iconOption.getAttribute('data-icon-value') || '';
            var label = iconOption.getAttribute('data-icon-label') || '';
            updateIconPickerPreview(iconPickerActiveField, value, label);
            closeIconPicker();
        }
    }

    function handleIconPickerKeydown(event) {
        if ('Escape' === event.key) {
            closeIconPicker();
        }
    }

    document.addEventListener('click', function (event) {
        handleIconPicker(event);
        handleAdd(event);
        handleRemove(event);
        handleDocumentMedia(event);
        handleGallerySelect(event);
        handleGalleryClear(event);
    });

    document.addEventListener('keydown', handleIconPickerKeydown);
})();
