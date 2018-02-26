<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>Animated Responsive Image Grid</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Animated Responsive Image Grid - Cycling through a set of images in a responsive grid." />
        <meta name="keywords" content="grid, images, thumbnails, responsive, css3, jquery, javascript, random, transition, 3d, perspective" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/minislider.css" />
		<link rel="stylesheet" href="css/sliderstyles.css">    
		        <!--Manifest-->
        <link rel="manifest" href="site.webmanifest">
    			<!--Normalizer-->
        <link rel="stylesheet" href="css/normalize.css">
		    <!--Bootstrap CSS-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--Custom Styles-->
        <link rel="stylesheet" href="css/main.css">
		
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!--Bootstrap JavaScript-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!--Font Awesome CDN-->
        <script src="https://use.fontawesome.com/e16bfdf81b.js"></script>
        
    <script type="text/javascript" src="js/modernizr.custom.26633.js"></script>
		<noscript>
			<link rel="stylesheet" type="text/css" href="css/fallback.css" />
		</noscript>
		<!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" href="css/fallback.css" />
		<![endif]-->



        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    </head>
    <body>

    	    <div class="container-fluid">
        <div class="row">
     
     <div class="col-xs-0 col-sm-6 col-md-6" id="social_01">
 <!--         
    <img id="icon1" src="img/icon1.png">
    <img id="icon2" src="img/icon2.png">-->
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12" id="header">

        <img src="img/logo_01.png" id="logo">
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
                <li class="navItem"><a class="navLink navLink-current" href="eventfinder.php">EVENTS</a></li>
                <li class="navItem"><a class="navLink" href="contact.php">CONTACT</a></li>
            </ul>
        </div>
    </div>
</nav>
    <div id="accent1"></div>
    <div id="accent2"></div>

        <h1>EVENTS &amp; WORKSHOPS</h1>




<div id="eventfield" class="container-fluid">
    <?php

        /* Get 2 upcoming events from Facebook */
        $json = @file_get_contents('https://graph.facebook.com/v2.11/rochestervegancommunitycenter/events?fields=start_time,name,description,cover,ticket_uri&access_token=1762412377395052|08e36ccae5ff74f80352d5d1ed43de11');

        /* Error handler for file_get_contents */
        if ($json === FALSE) {
            echo "Error! Couldn't load the events.";
        } else {

            /* JSON decode the response */
            $obj = json_decode($json);

            /* Check if there are any availeble posts */
            if ($json == '{"data":[]}') {
                echo "No events currently scheduled.";
            } else {



                echo "<div class='row'>";
                    /* Loop out all events */
                    $eventCount=0;
                    foreach (array_reverse($obj->data) as $post) {
                        list($date, $time) = explode("T", $post->start_time);
                        list($year, $month, $day) = explode("-", $date);
                        $today = getdate();
                        if ($today[year] <= $year && $today[mon] <= $month && $today[day] <= $day) { 
                            if (++$eventCount % 2 == 1) {
                                echo "<div class='col-xs-12 col-sm-6 col-md-6 eventcol_left'>";
                            } else {
                                echo "<div class='col-xs-12 col-sm-6 col-md-6 eventcol_right'>";
                            }
                            echo "<div class='eventbox'>";
                                echo "<div class='infopad'>";
                                    /* Name of the event */
                                    echo "<h2 class='eventtitle'>" . $post->name . "</h2>";

                                    echo "<div class='row'>
                                        <div class='col-xs-12 col-sm-12 col-md-8'>";

                                            /* Cover photo of the event */
                                            echo "<div class='picbox'>";
                                            echo "<img class='cover' src='" . $post->cover->source . "'>";
                                            echo "</div>";
                                        echo "</div>";


                                        echo "<div class='col-xs-12 col-sm-12 col-md-4'>";
                                            /* Event starts */
                                            list($date, $time) = explode("T", $post->start_time);
                                            list($year, $month, $day) = explode("-", $date);
                                            /* Convert time from military to normal time */
                                            $mil_time = explode("-", $time)[0];
                                            list($hour, $min, $sec) = explode(":", $mil_time);
                                            if ($hour > 12) {
                                                $hour -= 12;
                                                $proper_time = $hour . ":" . $min . " PM";
                                            } else {
                                                $proper_time = $hour . ":" . $min . " AM";
                                            }



                                            
                                            

                                            /* Display event data */
                                            /* Properly formatted date and time */
                                            echo "<h3 class='eventinfo'>DATE: " . $month . "/" . $day . "/" . $year . "<br>";
                                            echo "TIME: " . $proper_time . "</h3>";

                                            if (strlen($post->ticket_uri) > 0) {
                                                echo "<button class='tickets'><a class='tickets' target=blank href=\"" . $post->ticket_uri . "\">BUY TICKETS</a></button>";
                                                echo "<p class='ticket_info'>Powered by
                                                <img id='eb_logo' alt='Eventbrite Ticketing' src='img/eventbrite_logo.png'></p>";
                                            }
                                        echo "</div>";
                                    echo "</div>";

                                    echo "<div class='row'>
                                        <div class='col-xs-12 col-sm-12 col-md-12'>";

                                            /* Description of the event, truncated after 1000 chars *//* In event description, replace newlines with <br> tags */
                                            echo "<p class='event_descrip'>" . $description = str_replace("\n", "<br>", mb_strimwidth($post->description, 0, 200, "...")); 
                                            echo "</p>";

                                            /* Create a link to the event */
                                            echo "<a id='read_link' href='https://www.facebook.com/events/" . $post->id . "'>Read more</a>";

                                        echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                        }
                    }
                echo "</div>";
            }
        }
    ?>


    	<div class=" cointainer container-fluid minislider">
			
			<section class="main">

				<div id="ri-grid" class="ri-grid ri-grid-size-2">
					<img class="ri-loading-image" src="img/sliders/loading.gif"/>
					<ul>
						<li><a href="#"><img src="img/sliders/1.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/2.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/3.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/4.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/5.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/6.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/7.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/8.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/9.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/10.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/11.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/12.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/13.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/14.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/15.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/16.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/17.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/18.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/19.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/20.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/21.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/22.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/23.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/24.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/25.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/26.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/27.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/28.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/29.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/30.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/31.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/32.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/33.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/34.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/35.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/36.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/37.jpg"/></a></li>
						<li><a href="#"><img src="img/sliders/38.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/39.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/40.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/41.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/42.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/43.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/44.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/45.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/46.jpg"/></a></li>
                        <li><a href="#"><img src="img/sliders/47.jpg"/></a></li>
                        
						


					</ul>
				</div>
				
				
			</section>
			
        </div>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.gridrotator.js"></script>
		<script type="text/javascript">	
            $(function() {
            
                $( '#ri-grid' ).gridrotator( {
                    rows        : 3,
                    columns     : 15,
                    animType    : 'random',
                    animSpeed   : 600,
                    interval    : 800,
                    step        : 1,
                    w320        : {
                        rows    : 3,
                        columns : 4
                    },
                    w240        : {
                        rows    : 3,
                        columns : 4
                    }
                } );
            
            });        </script>
		</script>

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