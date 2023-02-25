<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MTG Blinger</title>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	
	<link rel="stylesheet" type="text/css" href="blinger.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
</head>
<body>
	<div id="top-part">
		<div id='header'>MTG Blinger</div>

		<div id='controls'>
			<textarea name="Card Name" id="card-name" placeholder="Paste your decklist here" rows='20' cols=100></textarea>
			<button id="send-btn">Find Bling</button>
			<button id="clear-btn">Clear Results</button><br>
			(note: currently only supports <i>exact</i> card names)
		</div>
	</div>

	<div id="results"></div>

	<div id="footer">
		by Vazkii (<a href='mailto:vazkii@violetmoon.org'>contact</a>)<br>
		<a href='https://github.com/Vazkii/MTGBlinger'>source code</a>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.2/mustache.min.js" integrity="sha512-xkCc8lq6qZqGvHzCQzWl69a/MF9RSB7ku5X3dJ9bsPfnoxyoXlhF87DN82Vqclv2Kc183pqPNRjtdGJymPO8DQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="blinger.js"></script>
</body>
</html>