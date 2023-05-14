'use strict';

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) arr2[i] = arr[i]; return arr2; } else { return Array.from(arr); } }

$(function () {
    $("body").addClass($.datepicker.formatDate('DD', new Date()));
    $('.accordion').accordion({
        collapsible: true, heightStyle: 'content', active: false,
        activate: function activate(e, ui) {
            if (ui.newPanel.is('.slick')) {
                ui.newPanel.find('ul').slick({ adaptiveHeight: true });
            }
        }
    });
    var changelog = function changelog(repo) {
        return 'https://api.github.com/repos/' + repo + '/releases?per_page=5';
    };
    var map = function map(c) {
        return function (data) {
            return data.map(c);
        };
    };
    var title = function title(component) {
        return function (entry) {
            return _extends({}, entry, {
                title: component + ' ' + entry.tag_name,
                date: new Date(entry.published_at)
            });
        };
    };
    Promise.all([$.getJSON(changelog('derula/swlrp-server')).then(map(title('Server'))), $.getJSON(changelog('samera999/SWLRP-Flash')).then(map(title('Client'))).then(function (data) {
        $('#version').text(data[0] ? data[0].tag_name : 'unknown');return data;
    })]).then(function (data) {
        return [].concat(_toConsumableArray(data[0]), _toConsumableArray(data[1]));
    }).then(function (data) {
        return data.filter(function (entry) {
            return !entry.prerelease;
        });
    }).then(map(function (_ref) {
        var title = _ref.title;
        var body = _ref.body;
        var date = _ref.date;
        return { title: title, body: body, date: date };
    })).then(function (data) {
        return data.sort(function (a, b) {
            return b.date - a.date;
        });
    }).then(map(function (entry) {
        return $('<dt>').text(entry.title).append($('<time>').text(entry.date.toLocaleString()).attr('datetime', entry.date.toISOString())).add($('<dd>').html(new markdownit().render(entry.body)));
    })).then(function (html) {
        return $('#changelog').html(html);
    })['catch'](function () {
        return $('#changelog').text('Failed retrieving changelog.');
    });
});