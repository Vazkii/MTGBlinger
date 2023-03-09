<?php	
	$QUERY_FLAGS = 'game:paper unique:prints -t:basic -is:funny -is:token';
	$TAGGED_SETS = array(
		'mps' => 'inventions',
		'mp2' => 'invocations',
		'exp' => 'expeditions',
		'zne' => 'expeditions',
		'med' => 'mythic_edition',
		'sta' => 'mystical_archive',
		'plg20' => 'love-your-lgs',
		'plg21' => 'love-your-lgs',
		'plg22' => 'love-your-lgs',
		'plg23' => 'love-your-lgs',
		'sch' => 'store-championships',
		'ppro' => 'pro-tour',
		'p30a' => '30a-play',
		'pcmp' => 'champs-and-states',
		'sld' => 'secret-lair',
		'slc' => 'secret-lair',
		'slp' => 'secret-lair',
		'slu' => 'secret-lair'
	);
	$TRACKED_PROMO_TYPES = array(
		'bundle', 'buyabox', 'convention', 'instore', 'jpwalker', 
		'judgegift', 'mediainsert', 'prerelease', 'promopack', 'release', 
		'setpromo', 'textless', 'wizardsplaynetwork', 'ampersand', 'fnm', 
		'gameday', 'boxtopper'
	);
	$IGNORED_PROMO_TYPES = array(
		'brawldeck'
	);

	function debug($str) {
		global $testing;
		if($testing)
			echo($str);
	}

	function search_card($cardname, $set_testing=false) {
		global $testing, $QUERY_FLAGS;
		$testing = $set_testing;
		$query_return = array();

		$cardname = str_replace('\'', '', $cardname);
		$query = "!'$cardname' " . $QUERY_FLAGS;
		$sf_url = "https://api.scryfall.com/cards/search?q=$query&pretty=" . $testing;

		debug("Searching for <b>$cardname</b><br>");
		debug("<b>$sf_url</b><br>");

		$sf_contents = @file_get_contents($sf_url);
		if(!$sf_contents)
			return null;

		$obj = json_decode($sf_contents);

		$count = $obj->total_cards;
		$data = $obj->data;

		debug("Found $count total cards!<br><br>");

		foreach($data as $key => $obj) {
			debug("<hr>Card ID: {$obj->id}<br><br>");
			if($obj->set_type === 'memorabilia')
				continue;

			$tags = get_card_tags($obj);
			if(sizeof($tags)) {
				$tags_str = implode(",", $tags);
				$image_uris = get_image_uris($obj);
				$small_img = $image_uris->small;
				debug("<img src='$small_img'></img><b>Tags: </b>$tags_str<br>");

				$skip_nonfoil = sizeof($tags) == 1 && $tags[0] === 'retro-foil';

				$effective_price = 0;

				$finishes_obj = array();
				foreach ($obj->finishes as $key => $finish) {
					if($skip_nonfoil && $finish !== 'foil')
						continue;

					$finish_obj = array();
					$finish_obj['type'] = $finish;
					$finish_obj['buy'] = array();

					$usd = parse_price($obj, 'usd', 'tcgplayer', $finish, $finish_obj);
					parse_price($obj, 'eur', 'cardmarket', $finish, $finish_obj);
					array_push($finishes_obj, $finish_obj);

					if($usd != 0 && ($effective_price == 0 || $usd < $effective_price))
						$effective_price = $usd;
				}

				$filtered_obj = array(
					'url' => $obj->scryfall_uri,
					'image' => $image_uris->normal,
					'versions' => $finishes_obj,
					'tags' => $tags,
					'effective_price' => $effective_price
				);
				array_push($query_return, $filtered_obj);
			}
		}

		usort($query_return, 'compare_prices');
		return array('cards' => $query_return);
	}

	function compare_prices($card1, $card2) {
		$price1 = $card1['effective_price'] * 100;
		$price2 = $card2['effective_price'] * 100;

		$price_diff = $price2 - $price1;
		if($price_diff != 0)
			return $price_diff;

		return strcmp($card1['url'], $card2['url']);
	}

	function get_image_uris($card) {
		$layout = $card->layout;
		if($layout === 'transform' || $layout === 'modal_dfc' || $layout === 'meld' || $layout === 'reversible_card')
			return $card->card_faces[0]->image_uris;
		else 
			return $card->image_uris;
	}

	function parse_price($obj, $curr, $store, $finish, &$out) {
		$prices = $obj->prices;
		$key = ($finish === 'nonfoil' ? '' : "_$finish");

		$price_val = 0;
		$listing = array(
			'currency' => $curr,
			'store' => $store,
			'url' => $obj->purchase_uris->{$store},
			'price' => 'N/A'
		);

		$search_key = "$curr$key";
		if(property_exists($prices, $search_key) && $prices->{$search_key}) {
			$price_str = $prices->{$search_key};
			$price_val = floatval($price_str);
			$listing['price'] = $price_str;
		}

		array_push($out['buy'], $listing);
		return $price_val;
	}

	function get_card_tags($card) {
		global $TAGGED_SETS, $TRACKED_PROMO_TYPES, $IGNORED_PROMO_TYPES;
		$tags = array();

		$set = $card->set;
		$lang = $card->lang;
		$cn = $card->collector_number;

		if(array_key_exists($set, $TAGGED_SETS))
			array_push($tags, $TAGGED_SETS[$set]);

		if($set === 'brr' && $cn > 63)
			array_push($tags, 'sketch-artifact');

		if($lang !== 'en') {
			array_push($tags, 'not-english');
			if($lang === 'ph')
				array_push($tags, 'phyrexian');
		}

		if((property_exists($card, 'security_stamp') && $card->security_stamp === 'triangle') || property_exists($card, 'flavor_name'))
			array_push($tags, 'universes-beyond');

		if(property_exists($card, 'frame_effects')) {
			if(in_array('extendedart', $card->frame_effects))
				array_push($tags, 'extended-art');

			else if(in_array('showcase', $card->frame_effects))
				array_push($tags, 'showcase');
		}

		if(in_array('etched', $card->finishes))
			array_push($tags, 'etched-foil');

		if($card->frame === '1997' && in_array('foil', $card->finishes))
			array_push($tags, 'retro-foil');

		if($card->border_color === 'borderless')
			array_push($tags, 'borderless');

		if(property_exists($card, 'promo_types')) {
			$types = $card->promo_types;

			$found_acceptable = false;

			foreach($types as $key => $type) {
				if(in_array($type, $TRACKED_PROMO_TYPES))
					array_push($tags, $type);

				else if(!in_array($type, $IGNORED_PROMO_TYPES))
					$found_acceptable = true;
			}

			if($found_acceptable && !sizeof($tags))
				array_push($tags, "misc-promo");
		}

		if(sizeof($tags) == 1 && $tags[0] === 'not-english')
			array_pop($tags);

		return $tags;
	}

?>