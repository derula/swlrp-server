$(() => {
    const defaultPortrait = '/assets/images/image_default.png';
    const wrap = (button) => {
        $('#main').wrapInner('<form method="POST">');
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
            style: '/assets/sceditor.min.css',
            emoticonsRoot: '/components/sceditor/',
            width: '100%',
            resizeEnabled: false,
            toolbar: 'bold,italic,underline,strike,subscript,superscript|' +
                'left,center,right,justify|font,size,color,removeformat|' +
                'bulletlist,orderedlist,table,code,quote,horizontalrule|' +
                'image,email,link,unlink|emoticon,date,time,rtl'
        });
        const portrait = $('.portrait');
        portrait.wrap(
            $('<a>', {href: '#'}).click((e) => {
                let oldVal = portrait.attr('src');
                if (oldVal === defaultPortrait) {
                    oldVal = '';
                }
                $('#changePortrait').dialog('open').find('input').val(oldVal);
            })
        ).after(
            $('<input>', {type: 'hidden', name: 'portrait', value: portrait.attr('src')})
        );
        button.button('option', 'label', 'Preview')
            .after($('<button>').button({label: 'Change password'}).click(Change))
            .after($('<input>', {type: 'submit'}).button({label: 'Save'}));
    };
    const unwrap = (button) => {
        $('.portrait').siblings().remove().addBack().unwrap();
        $('form', '#main').children().unwrap();
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
    const Change = (e) => {
        e.preventDefault();
        $('#changePW').dialog('open');
    };
    const Save = () => { $('#changePW').find('form').submit(); };
    const Apply = () => {
        const url = $('#changePortrait').find('input').val().trim();
        const src = url || defaultPortrait;
        $('.portrait').attr({src}).next().val(url);
        $('#changePortrait').dialog('close');
    };
    const Close = function() { $(this).dialog('close'); };
    const OK = Close;
    $('#edit').button({label: 'Change profile'}).click((e) => {
        e.preventDefault();
        ($('#main').children().is('form') ? unwrap : wrap)($(e.target));
    });
    $('#changePortrait').dialog({autoOpen: false, modal: true, buttons: {Apply, Close}});
    $('#changePW').dialog({
        autoOpen: false, modal: true, buttons: {Save, Close},
        close: (e, ui) => { $(e.target).find('input[type=password]').val('') }
    }).find('form').submit((e) => {
        e.preventDefault();
        $.post('/changepw', $(e.target).serialize())
            .done(() => {$('#changePW').dialog('close');$('#pwChanged').dialog('open')})
            .fail(() => {$('#pwChangeFailed').dialog('open')});
    });
    $('#pwChanged, #pwChangeFailed').dialog({autoOpen: false, modal: true, buttons: {OK}});
    $('.accordion').accordion({collapsible: true, heightStyle: 'content', active: false});
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
