$(() => {
    $("body").addClass($.datepicker.formatDate('DD', new Date()))
    $('.accordion').accordion({
        collapsible: true, heightStyle: 'content', active: false,
        activate: (e, ui) => {
            if (ui.newPanel.is('.slick')) {
                ui.newPanel.find('ul').slick({adaptiveHeight:true})
            }
        }
    });
    const changelog = repo => 'https://api.github.com/repos/' + repo + '/releases?per_page=5'
    const map = c => data => data.map(c)
    const title = component => entry => {
        return {
            ...entry,
            title: component + ' ' + entry.tag_name,
            date: new Date(entry.published_at)
        }
    }
    Promise.all([
        $.getJSON(changelog('derula/swlrp-server')).then(map(title('Server'))),
        $.getJSON(changelog('samera999/SWLRP-Flash')).then(map(title('Client')))
        .then(data => {$('#version').text(data[0] ? data[0].tag_name : 'unknown'); return data})
    ])
    .then(data => [...data[0], ...data[1]])
    .then(data => data.filter(entry => !entry.prerelease))
    .then(map(({title, body, date}) => { return {title, body, date} }))
    .then(data => data.sort((a, b) => b.date - a.date))
    .then(map(entry =>
        $('<dt>')
            .text(entry.title)
            .append($('<time>').text(entry.date.toLocaleString()).attr('datetime', entry.date.toISOString()))
            .add($('<dd>').html((new markdownit()).render(entry.body)))
    ))
    .then(html => $('#changelog').html(html))
    .catch(() => $('#changelog').text('Failed retrieving changelog.'))
})
