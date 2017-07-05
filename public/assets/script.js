$(() => {
    const wrap = (button) => {
        $('body').wrapInner('<form method="POST">');
        $('.editable').each((i, e) => {
            const prop = $(e).data('prop'), isText = $(e).is('.text');
            const field = $(isText ? '<textarea>' : '<input>').attr({
                name: prop.name,
                maxlength: isText ? 20000 : 40,
            });
            if (!isText) {
                field.attr({type: prop.contraint || 'text'});
                if (prop['autocomplete']) {
                    field.autocomplete({source: '/autocomplete/' + prop['name']});
                }
            }
            (isText ? field.html.bind(field) : (v) => { field.attr({value: v}) })(prop.value);
            $(e).html(field)
        });
        button.button('option', 'label', 'Preview');
    };
    const unwrap = (button) => {
        $('form').children().unwrap();
        $('.editable').each((i, e) => {
            const field = $('input, textarea', e), value = field.prop('value') || field.html();
            $(e).data('prop', $.extend($(e).data('prop'), {value: value}));
            field.replaceWith(value);
        });
        button.button('option', 'label', 'Change profile');
    };
    $('#edit').button({label: 'Change profile'}).click((e) => {
        ($('body').children().is('form') ? unwrap : wrap)($(e.target));
        e.preventDefault();
    });
    $('.accordion').accordion();
});
