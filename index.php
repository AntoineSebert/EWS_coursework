<?php
/*
	www.w3schools.com/howto/howto_css_fading_buttons.asp
	www.w3schools.com/HTML/html5_webworkers.asp

	secure.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration
	www.w3schools.com/sql/sql_ref_keywords.asp
	www.w3schools.com/xml/schema_intro.asp
	www.w3schools.com/xml/xsl_intro.asp
	www.w3schools.com/xml/xpath_intro.asp
	www.w3schools.com/xml/xquery_intro.asp

	fontawesome.com/icons?d=gallery

	DATE_ATOM, DATE_RSS, DATE_W3C
	htmlspecialchars()
*/

$directory = array(
	"model" => "data/",
	"vue" => "presentation/",
	"controller" => "application/"
);

function redirect(string $url, int $status_code = 303) {
	header('Location: ' . $url, true, $status_code);
	die();
}

require_once($directory["controller"] . 'database.php');
require_once($directory["controller"] . 'sign.php');

session_start(['cookie_lifetime' => 86400]);

if(strlen($_SERVER['REQUEST_URI']) == 1) {
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$result = sign();
		http_response_code($result);
		if($result != 400 && $result != 401) {
			redirect('/' . explode('@', $_POST["email"])[0]);
		}
	}
} else {
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$result = sign();
		http_response_code($result);
		if($result != 400 && $result != 401) {
			require_once($directory["controller"] . 'subscribe.php');
			http_response_code(manage_subscriptions());
		}
	} elseif(is_signed_in()) {
		require_once($directory["controller"] . 'load.php');
		$publications = get_publications();
	} else {
		http_response_code(401);
		redirect('/');
	}
}

?>

<!DOCTYPE html>
<html lang=en>

<head>
	<meta charset=UTF-8>
	<meta name=viewport content="width=device-width,initial-scale=1">
	<title>Enterprise Web Systems Coursework</title>
	<meta name=author content="Antoine/Anthony Sébert">
	<link type="text/plain" rel=author href="<?=$_SERVER['SERVER_NAME']?>/humans.txt" />
	<meta name=description content="3-Tier RSS Feed Tool">
	<meta name=keywords content="education, coursework, RSS, webdev, 3-tier">
	<link rel="shortcut icon" href=favicon.ico type="image/vnd.microsoft.icon">
	<link rel=stylesheet href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity=sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr crossorigin=anonymous>
	<link rel=stylesheet href="//fonts.googleapis.com/css?family=Comfortaa" type="text/css">
	<link rel=stylesheet href="//fonts.googleapis.com/css?family=Lobster" type="text/css">
	<link rel=stylesheet href="//fonts.googleapis.com/css?family=Srisakdi" type="text/css">
	<link rel=stylesheet href=presentation/style/index.css type="text/css">
	<script defer src="presentation/script/index.js"></script>
</head>

<body>
	<header>
		<div id=credit-badge>
			<a href="https://unsplash.com/@genessapana?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target=_blank rel="noopener noreferrer" title="Download free do whatever you want high-resolution photos from Genessa Panainte">
				<span style="display:inline-block;padding:2px 3px">
					<svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-2px;fill:white" viewBox="0 0 32 32">
						<title>unsplash-logo</title>
						<path d="M10 9V0h12v9H10zm12 5h10v18H0V14h10v9h12v-9z"></path>
					</svg>
				</span>
				<span style="display:inline-block;padding:2px 3px">Genessa Panainte</span>
			</a>
		</div>
		<div id=title-container>
			<h1>YouRSS</h1>
			<p>Seize the means of RSS feeds</p>
		</div>
	</header>

	<nav>
		<button onclick="document.getElementById('subscription_box').style.display='block'">
			<i class="fas fa-bars fa-2x"></i>
		</button>
		<input id=filter_field type=search name=filter placeholder=Filter..>
		<button class=right onclick="document.getElementById('loginbox').style.display='block'">
			<i class="far fa-user fa-2x"></i>
		</button>
		<div class=progress-container>
			<div class=progress-bar id=myBar></div>
		</div>
	</nav>

	<div id=loginbox class=modal>
		<form class="modal-content login-content animate" method=post>
			<h2>Register/Login</h2>
			<div class=container>
				<div class=input-container>
					<i class="fas fa-envelope icon"></i>
					<input class=input-field type=email placeholder=Email name=email maxlength=64 required>
				</div>
				<div class=input-container>
					<i class="fas fa-key icon"></i>
					<input id=password_field class=input-field type=password placeholder=Password name=password maxlength=32 required>
				</div>

				<button id=submit_button type=submit>Login/Register</button>
				<label>
					<input type=checkbox checked=checked name=remember> Remember me
				</label>
			</div>

			<div class=container style="background-color:#f1f1f1">
				<button type=button onclick="document.getElementById('loginbox').style.display='none'" class=cancelbtn>Cancel</button>
				<span class=forgotten_password>Forgot <a href=#>password?</a></span>
			</div>
		</form>
		<div id="message">
			<h3>Password must contain the following:</h3>
			<p id="letter" class=invalid><i class="fas fa-times"></i> A <b>lowercase</b> letter</p>
			<p id="capital" class=invalid><i class="fas fa-times"></i> A <b>capital (uppercase)</b> letter</p>
			<p id="number" class=invalid><i class="fas fa-times"></i> A <b>number</b></p>
			<p id="length" class=invalid><i class="fas fa-times"></i> Minimum <b>8 characters</b></p>
		</div>
	</div>

	<div id=subscription_box class=modal>
		<div class="modal-content animate subscription-content">
			<h2>Subscribe to an RSS feed</h2>
			<div class=container>
				<div class=input-container>
					<i class="fas fa-search icon"></i>
					<input class=input-field type=search placeholder=Subscribe... name=subscribe>
				</div>
			</div>
			<div class=container>
				<ul id=feed_list>
					<li>
						<button type=button><i class="fas fa-times"></i></button>
						<p>feed 1</p>
					</li>
					<li>
						<button type=button><i class="fas fa-times"></i></button>
						<p>feed 2</p>
					</li>
					<li>
						<button type=button><i class="fas fa-times"></i></button>
						<p>feed 3</p>
					</li>
				</ul>
			</div>
			<div class=container style="background-color:#f1f1f1">
				<button type=button onclick="document.getElementById('subscription_box').style.display='none'" class=cancelbtn>Cancel</button>
				<button type=submit style="float: right;">Subscribe</button>
			</div>
		</div>
	</div>

	<main>
		<div class=loader></div>
		<ol id=content_list_last_login class=feed_list>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Ainsi, toujours poussés vers de nouveaux rivages,<br>
						Dans la nuit éternelle emporté sans retour,<br>
						Ne pourrons-nous jamais sur l’océan des âges<br>
						Jeter l’ancre un seul jour ?
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Ô lac ! l’année à peine a fini sa carrière,<br>
						Et près des flots chéris qu’elle devait revoir,<br>
						Regarde ! je viens seul m’asseoir sur cette pierre<br>
						Où tu la vis s’asseoir !
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Tu mugissais ainsi sous ces roches profondes ;<br>
						Ainsi tu te brisais sur leurs flancs déchirés ;<br>
						Ainsi le vent jetait l’écume de tes ondes<br>
						Sur ses pieds adorés.
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Un soir, t’en souvient-il ? nous voguions en silence ;<br>
						On n’entendait au loin, sur l’onde et sous les cieux,<br>
						Que le bruit des rameurs qui frappaient en cadence<br>
						Tes flots harmonieux.
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Tout à coup des accents inconnus à la terre<br>
						Du rivage charmé frappèrent les échos :<br>
						Le flot plus attentif, et la voix qui m’est chère<br>
						Laissa tomber ces mots :
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						« Ô temps, suspends ton vol ! et vous, heures propices,<br>
						» Suspendez votre cours !<br>
						» Laissez-nous savourer les rapides délices<br>
						» Des plus beaux de nos jours !
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						» Assez de malheureux ici-bas vous implorent,<br>
						» Coulez, coulez pour eux ;<br>
						» Prenez avec leurs jours les soins qui les dévorent ;<br>
						» Oubliez les heureux.
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						» Mais je demande en vain quelques moments encore,<br>
						» Le temps m’échappe et fuit ;<br>
						» Je dis à cette nuit : Sois plus lente ; et l’aurore<br>
						» Va dissiper la nuit.
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						» Aimons donc, aimons donc ! de l’heure fugitive,<br>
						» Hâtons-nous, jouissons !<br>
						» L’homme n’a point de port, le temps n’a point de rive ;<br>
						» Il coule, et nous passons ! »
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Temps jaloux, se peut-il que ces moments d’ivresse,<br>
						Où l’amour à longs flots nous verse le bonheur,<br>
						S’envolent loin de nous de la même vitesse<br>
						Que les jours de malheur ?
					</p>
				</div>
			</li>
			<li>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Hé quoi ! n’en pourrons-nous fixer au moins la trace ?<br>
						Quoi ! passés pour jamais ? quoi ! tout entiers perdus ?<br>
						Ce temps qui les donna, ce temps qui les efface,<br>
						Ne nous les rendra plus ?
					</p>
				</div>
			</li>
			<li class=consulted>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Éternité, néant, passé, sombres abîmes,<br>
						Que faites-vous des jours que vous engloutissez ?<br>
						Parlez : nous rendrez-vous ces extases sublimes<br>
						Que vous nous ravissez ?
					</p>
				</div>
			</li>
			<li class=consulted>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Ô lacs ! rochers muets ! grottes ! forêt obscure !<br>
						Vous que le temps épargne ou qu’il peut rajeunir,<br>
						Gardez de cette nuit, gardez, belle nature,<br>
						Au moins le souvenir !
					</p>
				</div>
			</li>
			<li class=consulted>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Qu’il soit dans ton repos, qu’il soit dans tes orages,<br>
						Beau lac, et dans l’aspect de tes riants coteaux,<br>
						Et dans ces noirs sapins, et dans ces rocs sauvages<br>
						Qui pendent sur tes eaux !
					</p>
				</div>
			</li>
			<li class=consulted>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Qu’il soit dans le zéphyr qui frémit et qui passe,<br>
						Dans les bruits de tes bords par tes bords répétés,<br>
						Dans l’astre au front d’argent qui blanchit ta surface<br>
						De ses molles clartés !
					</p>
				</div>
			</li>
			<li class=consulted>
				<button class=collapsible>Open Collapsible</button>
				<div class=entry_content>
					<p>
						Que le vent qui gémit, le roseau qui soupire,<br>
						Que les parfums légers de ton air embaumé,<br>
						Que tout ce qu’on entend, l’on voit ou l’on respire,<br>
						Tout dise : Ils ont aimé !
					</p>
				</div>
			</li>
		</ol>
		<ol id=content_list_past class=feed_list>
		</ol>
	</main>

	<footer>
		<button id=top_button title="Go to top"><i class="fas fa-arrow-up"></i></button>
		<div class=pagination>
			<a href=#>&laquo;</a>
			<a href=#>1</a>
			<a class=active href=#>2</a>
			<a href=#>3</a>
			<a href=#>4</a>
			<a href=#>5</a>
			<a href=#>6</a>
			<a href=#>&raquo;</a>
		</div>
		<p>made with <i class="fas fa-mug-hot"></i> and <i class="far fa-heart"></i> by <a href="https://github.com/AntoineSebert">Antoine/Anthony Sébert</a> </p>
	</footer>
</body>

</html>
