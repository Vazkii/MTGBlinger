var lastResults = '';
var lastContainer = undefined;
var errored = false;

$(document).keyup(function(event) {
	if(event.which == 13 && $('#card-name').is(":focus")) // enter
		$('#send-btn').click();
});

$('#send-btn').click(function() {
	if(errored)
		lastContainer.remove();

	var cardName = $('#card-name').val();
	var url = `get?card=${cardName}`;

	var results = $('#results');
	lastContainer = $('<div class="card-row-container"><div class="loading">Loading...</div></div>');
	results.append(lastContainer);

	$.get(url, function(data) {
		lastResults = JSON.parse(data);
		lastResults['card_name'] = cardName;
		lastResults['material_icon'] = materialIcon;

		errored = false;

		updateResults();
	}).fail(function(data) {
		console.log(data);
		lastContainer.html(`<div class='error'>Error: ${data.status} - ${data.responseText}</div>`);
		errored = true;
	});
});

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
			var html = Mustache.to_html(template, data);
			callback(html);
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