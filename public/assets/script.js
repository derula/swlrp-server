$(() => {
    const maxTextLen = 20000;
    const wrap = (button) => {
        $('body').wrapInner('<form method="POST">');
        $('.editable').each((i, e) => {
            const prop = $(e).data('prop'), isText = $(e).is('.text');
            const field = $(isText ? '<textarea>' : '<input>').attr({
                id: 'property-' + prop.name,
                class: 'ui-widget-content ui-corner-all',
                name: prop.name,
                maxlength: isText ? 20000 : 40,
            });
            if (!isText) {
                field.attr({type: prop.constraint || 'text'});
                if (prop.autocomplete) {
                    field.autocomplete({source: '/suggestions/' + prop.name});
                }
            }
            field.val($(e).html());
            $(e).html(field);
        });
        $('textarea', '.editable.text').each((i, e) => {
            tinymce.EditorManager.execCommand('mceAddEditor', false, $(e).attr('id'));
        });
        button.button('option', 'label', 'Preview')
            .after($('<button>').button({label: 'Change password'}).click(changePW))
            .after($('<input type="submit">').button({label: 'Save'}));
    };
    const unwrap = (button) => {
        $('form').children().unwrap();
        $('textarea', '.editable.text').each((i, e) => {
            const id = $(e).attr('id');
            tinymce.EditorManager.execCommand('mceFocus', false, id);
            tinymce.EditorManager.execCommand('mceRemoveEditor', true, id);
        });
        $('.editable').each((i, e) => {
            const prop = $(e).data('prop');
            const field = $('input, textarea', e);
            field.replaceWith(field.val());
        });
        button.button('option', 'label', 'Change profile').nextAll().remove();
    };
    const changePW = (e) => {
        $('#changePW').dialog('open');
        e.preventDefault();
    };
    const savePW = (e) => {
        $('#changePW').find('form').submit();
        e.preventDefault();
    };
    const getMceLength = () => {
        return $('body', tinymce.get(tinymce.activeEditor.id).contentDocument).html().length;
    };
    $('#edit').button({label: 'Change profile'}).click((e) => {
        ($('body').children().is('form') ? unwrap : wrap)($(e.target));
        e.preventDefault();
    });
    $('#changePW').dialog({
        autoOpen: false, modal: true, buttons: {'Save': savePW, 'Close': function() { $(this).dialog('close') }},
        close: (e, ui) => { $(e.target).find('input[type=password]').val('') }
    }).find('form').submit((e) => {
        $.post('/changepw', $(e.target).serialize())
            .done(() => {$('#changePW').dialog('close');$('#pwChanged').dialog('open')})
            .fail(() => {$('#pwChangeFailed').dialog('open')});
        e.preventDefault();
    });
    $('#pwChanged, #pwChangeFailed, #maxLength').dialog({autoOpen: false, modal: true, buttons: {'OK': function() { $(this).dialog('close') }}});
    $('.accordion').accordion();
    $('.tabs').tabs();
    $('input[type=submit]').button();
    $('input[type=checkbox]').checkboxradio();
    $('input[type=password]').on('input', (e) => {
        const t = $(e.target), o = t.closest(':not(label, input)').find('[name=' + t.attr('name') + ']');
        if (o.length > 1) {
            const v = o.first().val() !== o.last().val() ? 'Passwords must match.' : '';
            o.last()[0].setCustomValidity(v);
        }
    });
    tinymce.init({
        selector:'none',
        menubar: false,
        plugins: 'paste',
        setup: (ed) => {
            const allowedKeys = [8, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 91, 92, 93, 144, 145];
            ed.showWarning = () => {
                $('#maxLength').dialog('open').dialog('option', 'close', () => {
                    ed.focus();
                });
                return false;
            };
            ed.on('keydown', (e) => {
                if (e.altKey || e.ctrlKey || e.metaKey || e.shiftKey || allowedKeys.indexOf(e.keyCode) != -1) {
                    return true;
                }
                if (getMceLength() + 1 > maxTextLen) {
                    ed.on('keyup', ed.showWarning);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                return true;
            });
        },
        paste_preprocess: (plugin, args) => {
            if (getMceLength() + args.content.length > maxTextLen) {
                tinymce.activeEditor.showWarning();
                args.content = '';
            }
        }
    });
});
