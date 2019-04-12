<?php
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

if(strlen($_SERVER['REQUEST_URI']) == 1) {
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$result = sign();
		http_response_code($result);
		if($result != 400 && $result != 401)
			redirect('/' . explode('@', $_POST["email"])[0]);
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
	<link type="text/plain" rel=author href="<?=htmlspecialchars($_SERVER['SERVER_NAME'])?>/humans.txt" />
	<meta name=description content="3-Tier RSS Feed Tool">
	<meta name=keywords content="education, coursework, RSS, webdev, 3-tier">
	<link rel="shortcut icon" href=favicon.ico type="image/vnd.microsoft.icon">
	<link rel=stylesheet href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity=sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr crossorigin=anonymous>
	<link rel=stylesheet href="//fonts.googleapis.com/css?family=Comfortaa" type="text/css">
	<link rel=stylesheet href="//fonts.googleapis.com/css?family=Lobster" type="text/css">
	<link rel=stylesheet href="//fonts.googleapis.com/css?family=Srisakdi" type="text/css">
	<link rel=stylesheet href=presentation/style/index.css type="text/css">
	<script defer src="presentation/script/index.js"></script>
	<?php
	if(is_signed_in())
		echo "<meta data-details='".htmlspecialchars($_SERVER['REQUEST_URI'])."'/>";
	?>
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
			<?php
			$icon_class = is_signed_in() ? "fas" : "far";
			?>
			<i class="<?=$icon_class?> fa-user fa-2x"></i>
		</button>
		<div class=progress-container>
			<div class=progress-bar id=myBar></div>
		</div>
	</nav>

	<div id=loginbox class=modal>
		<?php
		if(is_signed_in()) {
			echo "<p>Connected as " . htmlspecialchars(get_username()) . "<p>";
		} else {
			echo '
				<form class="modal-content login-content animate" method=post action=http://ewsc/index.php>
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
						<button type=button onclick="document.getElementById(\'loginbox\').style.display=\'none\'" class=cancelbtn>Cancel</button>
						<span class=forgotten_password>Forgot <a href=#>password?</a></span>
					</div>
				</form>
				<div id=message>
					<h3>Password must contain the following:</h3>
					<p id=letter class=invalid><i class="fas fa-times"></i> A <b>lowercase</b> letter</p>
					<p id=capital class=invalid><i class="fas fa-times"></i> An <b>uppercase</b> letter</p>
					<p id=number class=invalid><i class="fas fa-times"></i> A <b>number</b></p>
					<p id=length class=invalid><i class="fas fa-times"></i> Minimum <b>8 characters</b></p>
				</div>
			';
		}
		?>
	</div>

	<div id=subscription_box class=modal>
		<div class="modal-content animate subscription-content">
			<h2>Subscribe to an RSS feed</h2>
			<div class=container>
				<div class=input-container>
					<i class="fas fa-plus icon"></i>
					<input class=input-field type=search placeholder="Enter RSS feed link" name=subscribe>
				</div>
			</div>
			<?php
			if(is_signed_in()) {
				$subscriptions = get_user_subscriptions($_SESSION["email"]); // does not work :(
				echo "<div class=container>";
				if(0 < sizeof($subscriptions)) {
					echo "<ul id=feed_list>";
					foreach($subscriptions as $feed) {
						echo "<button type=button><i class='fas fa-times'></i></button>";
						echo "<p>" . $feed . "</p>";
					}
					echo "</ul>";
				} else {
					echo "<p>You haven't subscribed to any RSS feed yet.</p>";
				}
				echo "</div>";
			} else {
				echo "<p>You must create an account to manage subscriptions.</p>";
			}
			?>
			<div class=container style="background-color:#f1f1f1">
				<button type=button onclick="document.getElementById('subscription_box').style.display='none'" class=cancelbtn>Cancel</button>
				<button id=submit_subscription type=submit style="float: right;">Subscribe</button>
			</div>
		</div>
	</div>

	<main>
		<div class=loader></div>
		<?php
		if(is_signed_in()) {
			$feed = get_user_feed($_SESSION["email"]);
			function display_feed(array $feed) {
				foreach($feed as $publication) {
					echo "<li><button class=collapsible>". htmlentities($publication["title"]) . "</button><div class=entry_content><p>";
					echo htmlentities($publication["content"]);
					echo "</p></div></li>";
				}
			}

			if(0 < sizeof($feed["new"])) {
				echo "<ol id=content_list_last_login class=feed_list>";
				display_feed($feed["new"]);
				echo "</ol>";
			} else {
				echo "<p>There is nothing to show here at the moment.</p>";
			}
			if(0 < sizeof($feed["old"])) {
				echo "<ol id=content_list_past class=feed_list>";
				display_feed($feed["old"]);
				echo "</ol>";
			} else {
				echo "<p>There is nothing to show here at the moment.</p>";
			}
		} else {
			echo "<p>Create an account and manage your own RSS feed list now !</p>";
		}
		?>
	</main>

	<footer>
		<button id=top_button title="Go to top"><i class="fas fa-arrow-up"></i></button>
		<p>made with <i class="fas fa-mug-hot"></i> and <i class="far fa-heart"></i> by <a href="https://github.com/AntoineSebert">Antoine/Anthony Sébert</a> </p>
	</footer>
</body>

</html>
