<?php
	$TYPE_FILTERS = array(
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
		'retro-foil' => '7th Edition Style Foil',
		'no-reminder' => '10th Edition Foil',
		'borderless' => 'Borderless',
		'jpwalker' => 'WAR Japanese Planeswalkers',
		'misc-promo' => 'Misc. Promos',
		'from-the-vault' => 'From The Vault',
		'spellbook' => 'Signature Spellbook',
		'commander-collection' => 'Commander Collection',
		'premium_deck' => 'Premium Deck Series'
	);

	$PROMO_FILTERS = array(
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
	);

	$NEGATIVE_FILTERS = array(
		'secret-lair' => 'Hide Secret Lair Cards',
		'universes-beyond' => 'Hide Universes Beyond',
		'has-flavor-name' => 'Hide Cards with Flavor Name',
		'not-english' => 'Hide Non-English Cards',
		'controversial-artist' => 'Hide Controversial Artists',
		'only-foil' => 'Hide Foil-Only Cards',
		'only-nonfoil' => 'Hide Nonfoil-Only Cards'
	);

	function render_filters() {
		global $TYPE_FILTERS, $PROMO_FILTERS, $NEGATIVE_FILTERS;

		render_filter_set('Bling To Show', $TYPE_FILTERS);
		render_filter_set('Promos To Show', $PROMO_FILTERS);

		render_filter_set('Extra Options', $NEGATIVE_FILTERS, true, 
			'<div id="filter-line">'.
				'<input type="checkbox" class="filter filter-negative" data-filter-key="price-filter" />'.
				'<div class="filter-name">Hide Cards above <input type="number" id="price-filter" value="20" min="1"></input> USD</div>'.
			'</div>');
	}

	function render_filter_set($header, $set, $negative=false, $additional='') {
		$class = $negative ? 'filter-negative' : '';
		$checked = $negative ? '' : 'checked';

		asort($set);

		echo("<div class='filter-container'><div class='filter-container-header'>$header</div>");
		
		foreach($set as $key => $value) {
			$line = "<div class='filter-line'>".
						"<input type='checkbox' class='filter $class' data-filter-key='$key' $checked />".
						"<div class='filter-name'>$value</div>".
					"</div>";
			echo($line);
		}

		echo($additional);
		echo('</div>');
	}
?>