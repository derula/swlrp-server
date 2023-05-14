'use strict';

$(function () {
    $.fn.roundify = function () {
        return $(this).addClass('ui-widget-content ui-corner-all');
    };
    var defaultPortrait = '/assets/images/image_default.png';
    var wrap = function wrap(button) {
        $('main').wrapInner('<form method="POST">');
        $('.editable').each(function (i, e) {
            var prop = $(e).data('prop'),
                isText = $(e).is('.text');
            var field = $(isText ? '<textarea>' : '<input>').attr({
                id: 'property-' + prop.name,
                name: prop.name,
                maxlength: isText ? 50000 : 40
            }).roundify();
            var val = $(e).html();
            if (!isText) {
                field.attr({ type: prop.constraint || 'text' });
                if (prop.autocomplete) {
                    field.autocomplete({ source: '/suggestions/' + prop.name });
                }
                val = $('<div>').html(val).text();
            }
            field.val(val);
            $(e).html(field);
        });
        $('.editable.text').toggleClass('text _text').find('textarea').sceditor({
            style: '/assets/sceditor.min.css',
            emoticonsRoot: '/assets/images/',
            width: '100%',
            resizeEnabled: false,
            toolbar: 'bold,italic,underline,strike,subscript,superscript|' + 'left,center,right,justify|font,size,color,removeformat|' + 'bulletlist,orderedlist,table,code,quote,horizontalrule|' + 'image,email,link,unlink|emoticon,date,time,rtl'
        });
        var portrait = $('.portrait');
        portrait.wrap($('<a>', { href: '#', 'class': 'changePortrait' }).click(function (e) {
            var oldVal = portrait.attr('src');
            if (oldVal === defaultPortrait) {
                oldVal = '';
            }
            $('#changePortrait').dialog('open').find('input').val(oldVal);
        })).after($('<input>', { type: 'hidden', name: 'portrait', value: portrait.attr('src') }));
        button.button('option', 'label', 'Preview').after($('<button>').button({ label: 'Change password' }).click(Change)).after($('<input>', { type: 'submit' }).button({ label: 'Save' }));
    };
    var unwrap = function unwrap(button) {
        $('.portrait').siblings().remove().addBack().unwrap();
        $('form', 'main').children().unwrap();
        $('> textarea', '.editable._text').each(function (i, e) {
            var instance = $(e).sceditor('instance');
            instance.val($('<div>').html($.parseHTML(instance.val().substr(0, 50000))).html());
            instance.updateOriginal();
            instance.destroy();
        }).parent().toggleClass('text _text');
        $('.editable').each(function (i, e) {
            var prop = $(e).data('prop');
            var field = $('input, textarea', e);
            var val = field.val();
            if (!$(e).is('.text')) {
                val = $('<div>').text(val).html();
            }
            field.replaceWith(val);
        });
        button.button('option', 'label', 'Exit preview').nextAll().remove();
    };
    var Change = function Change(e) {
        e.preventDefault();
        $('#changePW').dialog('open');
    };
    var Save = function Save() {
        $('#changePW').find('form').submit();
    };
    var Apply = function Apply() {
        var url = $('#changePortrait').find('input').val().trim();
        var src = url || defaultPortrait;
        $('.portrait').attr({ src: src }).next().val(url);
        $('#changePortrait').dialog('close');
    };
    var Close = function Close() {
        $(this).dialog('close');
    };
    var OK = Close;
    $('#edit').not('.link').button({ label: 'Change profile' }).click(function (e) {
        e.preventDefault();
        ($('main').children().is('form') ? unwrap : wrap)($(e.target));
    });
    $('#changePortrait').dialog({ autoOpen: false, modal: true, buttons: { Apply: Apply, Close: Close } });
    $('#changePW').dialog({
        autoOpen: false, modal: true, buttons: { Save: Save, Close: Close },
        close: function close(e, ui) {
            $(e.target).find('input[type=password]').val('');
        }
    }).find('form').submit(function (e) {
        e.preventDefault();
        $.post('/changepw', $(e.target).serialize()).done(function () {
            $('#changePW').dialog('close');$('#pwChanged').dialog('open');
        }).fail(function () {
            $('#pwChangeFailed').dialog('open');
        });
    });
    $('#pwChanged, #pwChangeFailed, #openLink').dialog({ autoOpen: false, modal: true, buttons: { OK: OK } });
    $('.tabs').parent().tabs();
    $('button.link').button().click(function (e) {
        return document.location = $(e.target).data('href');
    });
    $('input').not('[type=submit]').roundify();
    $('input[type=submit]').button();
    $('input[type=password]').on('input', function (e) {
        var t = $(e.target),
            o = t.closest(':not(label, input)').find('[name=' + t.attr('name') + ']');
        if (o.length > 1) {
            var v = o.first().val() !== o.last().val() ? 'Passwords must match.' : '';
            o.last()[0].setCustomValidity(v);
        }
    });
    $('article').on('click', 'a', function (e) {
        e.preventDefault();
        var dialog = $('#openLink'),
            href = $(e.target).attr('href');
        dialog.find('a').attr('href', href);
        var input = dialog.find('input');
        dialog.dialog('open');
        input.val(href).select().focus();
    });
    var current = (new RegExp('[\?&]clientVer=([^&#]*)').exec(window.location.href) || [])[1];
    if (current) {
        $.getJSON('https://api.github.com/repos/samera999/SWLRP-Flash/releases?per_page=1').then(function (data) {
            var latest = data[0] ? data[0].tag_name : null;
            if (latest && latest != current) {
                (function () {
                    var elem = $('<aside>').addClass('toast').text('Your SWLRP add-on is out of date.').append('Your version: ' + current + ', available:' + latest).append('<br>').append('Please consider downloading and installing the latest version.');
                    $('body').prepend(elem);
                    elem.css('top');
                    elem.css({ top: 0 }).on('click focusout', function () {
                        elem.css({ top: '' });
                    });
                    setTimeout(function () {
                        elem.trigger('click');
                    }, 5000);
                })();
            }
        });
    }
});