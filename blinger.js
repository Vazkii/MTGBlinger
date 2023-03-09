var lastResults = '';
var lastContainer = undefined;
var currentDecklist = [];

var positiveFilters = [];
var negativeFilters = [];

var doingDeckList = false;
var errored = false;

$(function() {
	$('.filter').each(function() {
		updateCheckbox($(this));
	});
});

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
		lastResults['if_wpn_star'] = ifWPNStar;

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

$('#filters-header').click(function() {
	var contents = $('#filters-contents');
	var visible = contents.hasClass('filters-hidden');

	if(visible) {
		contents.removeClass('filters-hidden');
		$(this).text('Hide Filters');
	} else {
		contents.addClass('filters-hidden');
		$(this).text('Show Filters');
	}
});

$('.filter').click(function() {
	if(updateCheckbox($(this)))
		updateFiltersOnCards($(document));
});

function updateCheckbox(obj) {
	var checked = obj.is(':checked');
	var key = obj.attr('data-filter-key');
	var negate = obj.hasClass('filter-negative');

	var list = negate ? negativeFilters : positiveFilters;

	var contained = list.includes(key);
	if(checked && !contained) {
		list.push(key);
		return true;
	}

	if(contained) {
		list.splice(list.indexOf(key), 1);
		return true;
	}

	return false;
}

function updateFiltersOnCards(container) {
	container.find('.card').each(function() {
		var card = $(this);

		var matchedPositive = false;
		var matchedNegative = false;

		for(k in negativeFilters) {
			var neg = negativeFilters[k];
			var test = `tag-${neg}`;
			if(card.hasClass(test)) {
				matchedNegative = true;
				break;
			}
		}

		if(!matchedNegative)
			for(k in positiveFilters) {
				var pos = positiveFilters[k];
				var test = `tag-${pos}`;
				if(card.hasClass(test)) {
					matchedPositive = true;
					break;
				}
			}

		if(matchedPositive)
			card.show();
		else card.hide();
	});
}

function updateResults() {
	loadTemplate('cards', lastResults, function(data) {
		lastContainer.html(data);
		updateFiltersOnCards(lastContainer);
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
	};
}

function ifWPNStar() {
	return function(text, render) {
		if(this.tags.length == 1 && this.tags.includes('retro-foil'))
			return text; 
		return '';
	};
}