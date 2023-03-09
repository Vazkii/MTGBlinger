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
		<div id='header'>MTG <span class='r1'>B</span><span class='r2'>l</span><span class='r3'>i</span><span class='r4'>n</span><span class='r5'>g</span><span class='r6'>e</span><span class='r7'>r</span>
		</div>

		<div id='controls'>
			<div id='input'>
				<textarea name="Card Name" id="card-name" placeholder="Paste your decklist here" rows='20' cols=100></textarea>
				<button id="send-btn">Find Bling</button>
				<button id="clear-btn">Clear Results</button><br>
			</div>
			
			<div id='filters'>
				<div id='filters-header'>
					Show Filters
				</div>
				<div id='filters-contents' class='filters-hidden'>
					<?php 
						include 'get/filters.php'; 
						render_filters();
					?>
				</div>
			</div>

		</div>

	</div>

	<div id="results"></div>

	<div id="footer">
		by Vazkii (<a href='mailto:vazkii@violetmoon.org'>contact</a>)<br>
		<a href='https://github.com/Vazkii/MTGBlinger'>source code</a>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.2/mustache.min.js" integrity="sha512-xkCc8lq6qZqGvHzCQzWl69a/MF9RSB7ku5X3dJ9bsPfnoxyoXlhF87DN82Vqclv2Kc183pqPNRjtdGJymPO8DQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="blinger.js"></script>
</body>
</html>