<?php
	define('MAX_CACHE_AGE', 24 * 3600);

	include 'find_card.php';
	main();

	function main() {
		set_error_handler('throw_everything');

		if(!array_key_exists('card', $_GET)) {
			error(400, 'No card name provided, use ?card');
			return;
		}

		$card = $_GET['card'];
		if(!strlen($card)) {
			error(400, 'Empty card name');
			return;
		}

		try {
			$result = cache_or_request(strtolower($card));	
		} catch(Exception $e) {
			error(500, "Internal Error: {$e->getMessage()} when loading $card");
			return;
		}
		
		if(!$result) {
			error(404, "No card data found on Scryfall for $card");
			return;
		}

		if(!sizeof(json_decode($result)->cards)) {
			error(406, "$card exists on Scryfall, but no versions count as bling");
			return;
		}

		echo($result);
	}

	function throw_everything($severity, $message, $filename, $lineno) {
		if(!error_reporting())
			return;

		throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}

	function cache_or_request($card) {
		$hash = hash('md5', $card);

		$cache_file = "../cache/$hash.json";
		if(file_exists($cache_file) && (time() - filemtime($cache_file)) < MAX_CACHE_AGE)
			return file_get_contents($cache_file);

		$result = search_card($card);
		if(!$result)
			return null;

		$ret = json_encode($result);
		file_put_contents($cache_file, $ret);
		return $ret;
	}

	function error($error, $msg) {
		echo($msg);
		http_response_code($error);
	}

?>