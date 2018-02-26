<?php
// OPTIONS - PLEASE CONFIGURE THESE BEFORE USE!

$yourEmail = "vp@arroc.org"; // the email address you wish to receive these mails through
$yourWebsite = "Rochester Vegan Community Center"; // the name of your website
$thanksPage = ''; // URL to 'thanks for sending mail' page; leave empty to keep message on the same page 
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "name,email,comments"; // names of the fields you'd like to be required as a minimum, separate each field with a comma


// DO NOT EDIT BELOW HERE
$error_msg = array();
$result = null;

$requiredFields = explode(",", $requiredFields);

function clean($data) {
	$data = trim(stripslashes(strip_tags($data)));
	return $data;
}
function isBot() {
	$bots = array("Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "PycURL", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "Teoma", "alexa", "froogle", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz");

	foreach ($bots as $bot)
		if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
			return true;

	if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == " ")
		return true;
	
	return false;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isBot() !== false)
		$error_msg[] = "No bots please! UA reported as: ".$_SERVER['HTTP_USER_AGENT'];
		
	// lets check a few things - not enough to trigger an error on their own, but worth assigning a spam score.. 
	// score quickly adds up therefore allowing genuine users with 'accidental' score through but cutting out real spam :)
	$points = (int)0;
	
	$badwords = array("adult", "beastial", "bestial", "blowjob", "clit", "cum", "cunilingus", "cunillingus", "cunnilingus", "cunt", "ejaculate", "fag", "felatio", "fellatio", "fuck", "fuk", "fuks", "gangbang", "gangbanged", "gangbangs", "hotsex", "hardcode", "jism", "jiz", "orgasim", "orgasims", "orgasm", "orgasms", "phonesex", "phuk", "phuq", "pussies", "pussy", "spunk", "xxx", "viagra", "phentermine", "tramadol", "adipex", "advai", "alprazolam", "ambien", "ambian", "amoxicillin", "antivert", "blackjack", "backgammon", "texas", "holdem", "poker", "carisoprodol", "ciara", "ciprofloxacin", "debt", "dating", "porn", "link=", "voyeur", "content-type", "bcc:", "cc:", "document.cookie", "onclick", "onload", "javascript");

	foreach ($badwords as $word)
		if (
			strpos(strtolower($_POST['comments']), $word) !== false || 
			strpos(strtolower($_POST['name']), $word) !== false
		)
			$points += 2;
	
	if (strpos($_POST['comments'], "http://") !== false || strpos($_POST['comments'], "www.") !== false)
		$points += 2;
	if (isset($_POST['nojs']))
		$points += 1;
	if (preg_match("/(<.*>)/i", $_POST['comments']))
		$points += 2;
	if (strlen($_POST['name']) < 3)
		$points += 1;
	if (strlen($_POST['comments']) < 15 || strlen($_POST['comments']) > 1500)
		$points += 2;
	if (preg_match("/[bcdfghjklmnpqrstvwxyz]{7,}/i", $_POST['comments']))
		$points += 1;
	// end score assignments

	if ( !empty( $requiredFields ) ) {
		foreach($requiredFields as $field) {
			trim($_POST[$field]);
			
			if (!isset($_POST[$field]) || empty($_POST[$field]) && array_pop($error_msg) != "Please fill in all the required fields and submit again.\r\n")
				$error_msg[] = "Please fill in all the required fields and submit again.";
		}
	}

	if (!empty($_POST['name']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['name'])))
		$error_msg[] = "The name field must not contain special characters.\r\n";
	if (!empty($_POST['email']) && !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($_POST['email'])))
		$error_msg[] = "That is not a valid e-mail address.\r\n";
	if (!empty($_POST['url']) && !preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i', $_POST['url']))
		$error_msg[] = "Invalid website url.\r\n";
	
	if ($error_msg == NULL && $points <= $maxPoints) {
		$subject = "Automatic Form Email";
		
		$message = "You received this e-mail message through your website: \n\n";
		foreach ($_POST as $key => $val) {
			if (is_array($val)) {
				foreach ($val as $subval) {
					$message .= ucwords($key) . ": " . clean($subval) . "\r\n";
				}
			} else {
				$message .= ucwords($key) . ": " . clean($val) . "\r\n";
			}
		}
		$message .= "\r\n";
		$message .= 'IP: '.$_SERVER['REMOTE_ADDR']."\r\n";
		$message .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\r\n";
		$message .= 'Points: '.$points;

		if (strstr($_SERVER['SERVER_SOFTWARE'], "Win")) {
			$headers   = "From: $yourEmail\r\n";
		} else {
			$headers   = "From: $yourWebsite <$yourEmail>\r\n";	
		}
		$headers  .= "Reply-To: {$_POST['email']}\r\n";

		if (mail($yourEmail,$subject,$message,$headers)) {
			if (!empty($thanksPage)) {
				header("Location: $thanksPage");
				exit;
			} else {
				$result = 'Your mail was successfully sent.';
				$disable = true;
			}
		} else {
			$error_msg[] = 'Your mail could not be sent this time. ['.$points.']';
		}
	} else {
		if (empty($error_msg))
			$error_msg[] = 'Your mail looks too much like spam, and could not be sent this time. ['.$points.']';
	}
}
function get_data($var) {
	if (isset($_POST[$var]))
		echo htmlspecialchars($_POST[$var]);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Contact | RVCC</title>
	
	<style type="text/css">
		p.error, p.success {
			font-weight: bold;
			padding: 10px;
			border: 1px solid;
		}
		p.error {
			background: #ffc0c0;
			color: #900;
		}
		p.success {
			background: #b3ff69;
			color: #4fa000;
		}
	</style>

<link rel="manifest" href="site.webmanifest">


<link rel="stylesheet" href="css/normalize.css">
        <!--Bootstrap CSS-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="css/sliderstyles.css">
    <!--Custom Styles-->
        <link rel="stylesheet" href="css/main.css">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!--Bootstrap JavaScript-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!--Font Awesome CDN-->
<script src="https://use.fontawesome.com/e16bfdf81b.js"></script>


        <script src="js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
        <script>
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>



</head>
<body>

<!--
	Free PHP Mail Form v2.4.5 - Secure single-page PHP mail form for your website
	Copyright (c) Jem Turner 2007-2017
	http://jemsmailform.com/

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	To read the GNU General Public License, see http://www.gnu.org/licenses/.
-->

<?php
if (!empty($error_msg)) {
	echo '<p class="error">ERROR: '. implode("<br />", $error_msg) . "</p>";
}
if ($result != NULL) {
	echo '<p class="success">'. $result . "</p>";
}
?>




       <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <div class="container-fluid">
    <div class="row">
     <div class="col-xs-0 col-sm-6 col-md-6" id="social_01">
 <!--         
    <img id="icon1" src="img/icon1.png">
    <img id="icon2" src="img/icon2.png">-->
</div>

<div class="col-xs-12 col-sm-12 col-md-12" id="header">

<img src="img/logo_01.png" id="logo" alt="RVCC Logo" title="RVCC Logo">
</div>

</div>
        
    </div>


<nav class="navbar">
    <div class="container-fluid navbar-container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle toggle-button" data-toggle="collapse" data-target="#navbar">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li class="navItem"><a class="navLink" href="index.html">HOME</a></li>
                <li class="navItem"><a class="navLink" href="schedule.html">SCHEDULE</a></li>
                <li class="navItem"><a class="navLink" target="blank" href="events.php">EVENTS</a></li>
                <li class="navItem"><a class="navLink navLink-current" href="contact.php">CONTACT</a></li>
            </ul>
        </div>
    </div>
</nav>
    <div id="accent1"></div>
    <div id="accent2"></div>





<div class="container-fluid" id="contact_page">
    <div class="row">

     	<div class="col-xs-12 col-sm-6 col-md-6">

			<h2 class="ind_title">LOCATION</h2>

				<div class="googlemap-responsive">
					<iframe class="map" src="https://www.google.com/maps/embed/v1/place?q=14%20Edmonds%20Street%20Rochester%2C%20NY%2014604&key=AIzaSyA5uubCZlsxkbnQy4o_igOKvtMh_pgwV94" allowfullscreen></iframe>
				</div>

		</div>

     	<div class="col-xs-12 col-sm-6 col-md-6">

			<h2 class="ind_title">CONTACT US</h2>

				<p class="form">Are you interested in teaching a class? Maybe you'd like to rent the space for an event or have an idea for a workshop. Let us know and we'll work with you to make it happen. We can't wait to hear from you.</p>

				<p class="form">P. (585) 454-9490</p>

				<form action="<?php echo basename(__FILE__); ?>" method="post">
					<noscript>
						<p><input class="contact" type="hidden" name="nojs" id="nojs" /></p>
					</noscript>

				<p class="form"><label for="name">Name: *</label> 
					<input id="contact" type="text" name="name" id="name" placeholder="Your name" value="<?php get_data("name"); ?>" /><br />
	
				<label for="email">E-mail: *</label> 
					<input id="contact" type="text" name="email" id="email" placeholder="Your email address" value="<?php get_data("email"); ?>" /><br />
	
				<label for="comments">Comments: *</label>
					<textarea id="contact" name="comments" id="comments" rows="5" cols="20" placeholder="Your message"><?php get_data("comments"); ?></textarea><br />
				</p>

				<p class="submit"><input id="contact" type="submit" name="submit" id="submit" value="Submit" <?php if (isset($disable) && $disable === true) echo ' disabled="disabled"'; ?> />
				</p>
				</form>

		</div>

	</div>



  	<div class="row" id="about">
     	<h2 class="ind_title">ABOUT US </h2>

     	     <div class="col-xs-12 col-sm-6 col-md-6">
     			<p class="half">The Rochester Vegan Community Center is a co-operative space which is the result of countless hours of work and many generous donations of space and building materials by numerous, tireless volunteers,  wonderful friends, lovely family members, and passionate vegans from Rochester and the surrounding western NY area.</p>

 				<p class="half">It is the brainchild of local animal rights activist, yogi, and business owner, Mary Barletta. Mary made the choice not to eat animals as a child and has made it her life’s mission to help them in every way she can. The center was created out of a necessity. Globally, veganism is a growing movement that shows no signs of stopping and the center aims to be a place where the community can spread it’s wings.</p>

 				<p class="half">We seek to inspire support and grow veganism by educating people about the "why" and "how" of adopting a vegan lifestyle, expanding and connecting our circle of individuals, united in their compassion for all living beings, and hosting exciting events that will enrich not only the vegan community but the entire community of Rochester.</p>
  			</div>
 
  			<div class="col-xs-12 col-sm-6 col-md-6">
    	 		<img class="piclinkfull" src="img/mary_rvcc.png" alt="Mary Barletta with a friend at Skylands" title="Mary Barletta with a friend at Skylands">
     		</div>
    
     </div>


</div>	







<div id="footer" class="container-fluid">

        <div class="row">

            <div class="col-xs-4 col-sm-4 col-md-4" id="planterbox">  

                    <img id="grass" src="img/grass.png" alt="grass" title="grass">

            </div>

        </div>


        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-4" id="mailform">

                <!-- Begin MailChimp Signup Form -->
                
                    <div id="mc_embed_signup">
                        <form class="news" action="https://rocvegancc.us17.list-manage.com/subscribe/post?u=1dc0451f3db16c866ba25312c&amp;id=4245e367e8" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

                            <div id="mc_embed_signup_scroll">
                                <label id="news" for="mce-EMAIL">Subscribe to our mailing list</label><br>
                                    <input id="news" type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
                                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_1dc0451f3db16c866ba25312c_4245e367e8" tabindex="-1" value="">
                                            </div>

                                            <div class="clear button"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe-form">
                                            </div>
                            </div>
                        </form>
                    </div>
            </div>
       


<!--End mc_embed_signup-->


      
            <div class="col-xs-6 col-sm-4 col-md-4 col-sm-offset-4 col-md-offset-4"> 

                <div class="row" id="social">

                    <div class="col-xs-4 col-sm-4 col-md-4 icons">  
                        <a class="social_link" href="https://www.facebook.com/rochestervegancommunitycenter/" target="blank"><i class="fa fa-facebook fa-2x" aria-hidden="true"></i></a>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4 icons">   
                        <a class="social_link" href="https://twitter.com/rocvegancc" target="blank"><i class="fa fa-twitter fa-2x" aria-hidden="true"></i></a>
                    </div> 

                    <div class="col-xs-4 col-sm-4 col-md-4 icons">  
                        <a class="social_link" href="" target="blank"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i></a>
                    </div>                 
        
                </div>
            </div>

            <div class="col-xs-6 col-sm-4 col-md-4" id="footershop">
                <h3 class="shop">SHOP</h3>
                <a href="https://www.etsy.com/shop/veganarmy269?ref=l2-shopheader-name"><img id="va_button" src="img/va_button.png" alt="Shop Vegan Army Activist Apparel" title="Shop Vegan Army Activist Apparel"></a>
            </div>
        </div>
        
    


        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

               <ul class="foot">
                   <li class="footeritem"><a class="copylink" href="contact.php">Contact</a></li>
                   <li class="footeritem"><a class="copylink" href="index.html">About Us</a></li>
                   <li class="footeritem"><a class="copylink" href="docs/privacy_policy.pdf">Privacy Policy</a></li>
               </ul>

                <p class="copyinfo">(585) 454-9490 | 14 Edmonds Street Rochester, NY 14607<br>
                @2018 Vegan Army LLC. | Web Design by <a class="mylink" href="https://www.facebook.com/leanne.morango" target="blank"> Leanne Morango</a></p>
            </div>

        </div>

</div>

    </body>
</html>













