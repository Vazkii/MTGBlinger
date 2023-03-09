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
		'jpwalker' => 'WAR Japanese Planeswalkers',
		'misc-promo' => 'Misc. Promos'
	));

	define('PROMO_FILTERS', array(
		'love-your-lgs' => 'Love Your LGS',
		'store-championships' => 'Store Championships',
		'pro-tour' => 'Pro Tour',
		'30a-play' => '30th Anniversary Play Promos',
		'champs-and-states' => 'Champs and States',
		'bundle' => 'Bundle',
		'buyabox' => 'Buy a Box',
		'convention' => 'Convention',
		'instore' => 'In-Store Play',
		'judgegift' => 'Judge Gift',
		'mediainsert' => 'Media Insert',
		'prerelease' => 'Prerelease',
		'promopack' => 'Promo Pack',
		'release' => 'Release',
		'setpromo' => 'Set Promo',
		'textless' => 'Textless',
		'wizardsplaynetwork' => 'WPN',
		'ampersand' => 'D&D Ampersand',
		'fnm' => 'FNM',
		'gameday' => 'Game Day'
	));

	define('NEGATIVE_FILTERS', array(
		'secret-lair' => 'Hide Secret Lair',
		'universes-beyond' => 'Hide Universes Beyond',
		'not-english' => 'Hide Non-English Cards'
	));

	function render_filters() {
		render_filter_set('Bling To Show', TYPE_FILTERS);
		render_filter_set('Promos To Show', PROMO_FILTERS);

		render_filter_set('Extra Options', NEGATIVE_FILTERS, true, "<br>(price filter coming soon)");
	}

	function render_filter_set($header, $set, $negative=false, $additional='') {
		$class = $negative ? 'filter filter-negative' : 'filer';
		$checked = $negative ? '' : 'checked';

		asort($set);

		echo("<div class='filter-container'><div class='filter-container-header'>$header</div>");
		
		foreach($set as $key => $value) {
			$line = "<div class='filter-line'>".
						"<input type='checkbox' class='$class' data-filter-key='$key' $checked />".
						"<div class='filter-name'>$value</div>".
					"</div>";
			echo($line);
		}

		echo($additional);
		echo('</div>');
	}
?>