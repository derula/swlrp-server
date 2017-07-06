$(() => {
    const wrap = (button) => {
        $('body').wrapInner('<form method="POST">');
        $('.editable').each((i, e) => {
            const prop = $(e).data('prop'), isText = $(e).is('.text');
            const field = $(isText ? '<textarea>' : '<input>').attr({
                class: 'ui-widget-content ui-corner-all',
                name: prop.name,
                maxlength: isText ? 20000 : 40,
            });
            if (!isText) {
                field.attr({type: prop.constraint || 'text'});
                if (prop['autocomplete']) {
                    field.autocomplete({source: '/suggestions/' + prop['name']});
                }
            }
            field.val(prop.value);
            $(e).html(field)
        });
        button.button('option', 'label', 'Preview')
            .after($('<button>').button({label: 'Change password'}).click(changePW))
            .after($('<input type="submit">').button({label: 'Save'}));
    };
    const unwrap = (button) => {
        $('form').children().unwrap();
        $('.editable').each((i, e) => {
            const field = $('input, textarea', e), value = field.val();
            $(e).data('prop', $.extend($(e).data('prop'), {value: value}));
            field.replaceWith(value);
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
    $('#pwChanged, #pwChangeFailed').dialog({autoOpen: false, modal: true, buttons: {'OK': function() { $(this).dialog('close') }}});
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
});
