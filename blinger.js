var lastResults = '';
var lastContainer = undefined;
var currentDecklist = [];

var doingDeckList = false;
var errored = false;

/**
$(document).keyup(function(event) {
	if(event.which == 13 && $('#card-name').is(":focus")) // enter
		$('#send-btn').click();
});
*/

$('#send-btn').click(function() {
	if(errored)
		lastContainer.remove();

	var decklist = $('#card-name').val().trim();
	var cards = decklist.split('\n');
	if(cards.length == 1) {
		doingDeckList = false;
		findNext(cards[0]);
	}
	else {
		doingDeckList = true;
		currentDecklist = cards;

		findDecklist();
	}
});

function findDecklist() {
	if(currentDecklist.length == 0) {
		doingDeckList = false;
		return;
	}

	var cardName = currentDecklist.pop();
	cardName = sanitize(cardName, /^(?:\d+ )(.+)/i);
	cardName = sanitize(cardName, /(.+) \*[FE]\*/i);
	cardName = sanitize(cardName, /(.+) \([a-zA-Z0-9]{3,5}\) [0-9sp]{1,4}/i);

	console.log("Sanitized cardname is " + cardName);
	findNext(cardName);
}

function sanitize(str, pattern) {
	if(pattern.test(str)) {
		console.log('Matching str ' + str);
		return str.replace(pattern, '$1')
	}
	return str;
}

function findNext(cardName) {
	var url = `get?card=${cardName}`;

	if(doingDeckList)
		url += '&allow_empty=true';

	var results = $('#results');
	lastContainer = $(`<div class="card-row-container"><div class="loading">Loading ${cardName}...</div></div>`);
	results.prepend(lastContainer);

	$.get(url, function(data) {
		lastResults = JSON.parse(data);
		lastResults['card_name'] = cardName.trim().toLowerCase();
		lastResults['material_icon'] = materialIcon;

		errored = false;

		updateResults();
	}).fail(function(data) {
		console.log(data);
		lastContainer.html(`<div class='error'>Error: ${data.status} - ${data.responseText}</div>`);
		errored = true;
	});
}

$('#clear-btn').click(function() {
	$('#results').html('');
})

function updateResults() {
	loadTemplate('cards', lastResults, function(data) {
		lastContainer.html(data);
	});
}

function loadTemplate(name, data, callback) {
	$.ajax({
		url: `templates/${name}.html`,
		success: function(template) {
			if(data.cards.length > 0) {
				var html = Mustache.to_html(template, data);
				callback(html);	
			} else if(doingDeckList)
				lastContainer.remove();

			if(doingDeckList)
				findDecklist();
		}
	});
}

function materialIcon() {
	return function(text, render) {
		var icon = render(text);
		switch(icon) {
			case 'nonfoil': return 'circle';
			case 'foil': return 'stars';
			case 'etched': return 'offline_bolt';
		}	
	}
}