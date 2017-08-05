$(() => {
    const wrap = (button) => {
        $('body').wrapInner('<form method="POST">');
        $('.editable').each((i, e) => {
            const prop = $(e).data('prop'), isText = $(e).is('._text');
            const field = $(isText ? '<textarea>' : '<input>').attr({
                id: 'property-' + prop.name,
                class: 'ui-widget-content ui-corner-all',
                name: prop.name,
                maxlength: isText ? 50000 : 40,
            });
            let val = $(e).html();
            if (!isText) {
                field.attr({type: prop.constraint || 'text'});
                if (prop.autocomplete) {
                    field.autocomplete({source: '/suggestions/' + prop.name});
                }
                val = $('<div>').html(val).text();
            }
            field.val(val);
            $(e).html(field);
        });
        $('textarea', '.editable._text').sceditor({
            style: '/components/sceditor/minified/jquery.sceditor.default.min.css',
            emoticonsRoot: '/components/sceditor/',
            width: '100%',
            resizeEnabled: false,
            toolbar: 'bold,italic,underline,strike,subscript,superscript|' +
                'left,center,right,justify|font,size,color,removeformat|' +
                'bulletlist,orderedlist,table,code,quote,horizontalrule|' +
                'image,email,link,unlink|emoticon,date,time,rtl'
        });
        button.button('option', 'label', 'Preview')
            .after($('<button>').button({label: 'Change password'}).click(changePW))
            .after($('<input type="submit">').button({label: 'Save'}));
    };
    const unwrap = (button) => {
        $('form').children().unwrap();
        $('> textarea', '.editable._text').each((i, e) => {
            const instance = $(e).sceditor('instance');
            instance.val($('<div>').html($.parseHTML(instance.val().substr(0, 50000))).html());
            instance.updateOriginal();
            instance.destroy();
        });
        $('.editable').each((i, e) => {
            const prop = $(e).data('prop');
            const field = $('input, textarea', e);
            let val = field.val();
            if (!$(e).is('._text')) {
                val = $('<div>').text(val).html();
            }
            field.replaceWith(val);
        });
        button.button('option', 'label', 'Change profile').nextAll().remove();
    };
    const changePW = (e) => {
        e.preventDefault();
        $('#changePW').dialog('open');
    };
    const savePW = (e) => {
        e.preventDefault();
        $('#changePW').find('form').submit();
    };
    $('#edit').button({label: 'Change profile'}).click((e) => {
        e.preventDefault();
        ($('body').children().is('form') ? unwrap : wrap)($(e.target));
    });
    $('#changePW').dialog({
        autoOpen: false, modal: true, buttons: {'Save': savePW, 'Close': function() { $(this).dialog('close') }},
        close: (e, ui) => { $(e.target).find('input[type=password]').val('') }
    }).find('form').submit((e) => {
        e.preventDefault();
        $.post('/changepw', $(e.target).serialize())
            .done(() => {$('#changePW').dialog('close');$('#pwChanged').dialog('open')})
            .fail(() => {$('#pwChangeFailed').dialog('open')});
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
});
