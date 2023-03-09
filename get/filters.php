<?php
	define('TYPE_FILTERS', array(
		'inventions' => 'Kaladesh Inventions',
		'invocations' => 'Amonkhet Invocations',
		'expeditions' => 'Zendikar Expeditions',
		'mythic_edition' => 'Mythic Edition',
		'mystical_archive' => 'Mystical Archive',
		'secret-lair' => 'Secret Lair',
		'box-topper' => 'Box Topper',
		'sketch-artifact' => 'Sketch Artifacts',
		'phyrexian' => 'Phyrexian Language',
		'extended-art' => 'Extended Art Frame',
		'showcase' => 'Showcase',
		'etched-foil' => 'Etched Foil',
		'retro-foil' => '7th Edition Foil',
		'borderless' => 'Borderless',
		'misc-promo' => 'Misc. Promos'
	));

	define('PROMO_FILTERS', array(
		'love-your-lgs' => 'Love Your LGS',
		'store-championships' => 'Store Championships',
		'pro-tour' => 'Pro Tour',
		'30a-play' => '30th Anniversary Play Promos',
		'champs-and-states' => 'Champs and States',
		'bundle' => 'WIP',
		'buyabox' => 'WIP',
		'convention' => 'WIP',
		'instore' => 'WIP',
		'jpwalker' => 'WIP',
		'judgegift' => 'WIP',
		'mediainsert' => 'WIP',
		'prerelease' => 'WIP',
		'promopack' => 'WIP',
		'release' => 'WIP',
		'setpromo' => 'WIP',
		'textless' => 'WIP',
		'wizardsplaynetwork' => 'WIP',
		'ampersand' => 'WIP',
		'fnm' => 'WIP',
		'gameday' => 'WIP'
	));

	define('NEGATIVE_FILTERS', array(
		'secret-lair' => 'Hide Secret Lair',
		'universes-beyond' => 'Hide Universes Beyond',
		'not-english' => 'Hide Non-English Cards'
	));

	function render_filters() {
		render_filter_set('Bling To Show', TYPE_FILTERS);
		render_filter_set('Promos To Show', PROMO_FILTERS);
		render_filter_set('Extra Options', NEGATIVE_FILTERS, true);
	}

	function render_filter_set($header, $set, $negative=false) {
		$class = $negative ? 'filter filter-negative' : 'filer';
		$checked = $negative ? '' : 'checked';

		echo("<div class='filter-container'><div class='filter-container-header'>$header</div>");
		foreach($set as $key => $value) {

			echo("<input type='checkbox' class='$class' data-filter-key='$key' $checked /> $value<br>");
		}
		echo('</div>');
	}
?>