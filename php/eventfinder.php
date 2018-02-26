<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Rochester Vegan Community Center</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <!--Facebook SDK-->

    <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1762412377395052',
      xfbml      : true,
      version    : 'v2.11'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>



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

    <h1>UPCOMING EVENTS</h1>

    <p class="full">Please join us for our upcoming events. We want to create a space where people interested in veganism can gather and have some fun.</p>


<div id="eventfield" class="container-fluid">
<?php
/* Get 2 upcoming events from Facebook */
$json = @file_get_contents('https://graph.facebook.com/v2.11/rochestervegancommunitycenter/events?fields=start_time,name,description,cover&limit=2&access_token=1762412377395052|08e36ccae5ff74f80352d5d1ed43de11');
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
/* Loop out all events */
foreach ($obj->data as $post) {
/* Name of the event */
echo "<h2>" . $post->name . "</h2>";
/* Event starts */
echo "<p class='eventinfo'>" . $post->start_time . "</p>";
/* Description of the event, truncated after 1000 chars */
echo mb_strimwidth( "<p class='event_descrip'>" . $post->description, 0, 1000, "...") . "</p>";
/* Cover photo of the event */
echo "<img class='cover' alt='Event Cover Image' title='Event Cover Image'src='" . $post->cover->source . "'>";
/* Create a link to the event */
echo "<a id='read_link' href='https://www.facebook.com/events/" . $post->id . "'>Read more</a>";
}
}
}
?>
</div>






<div id="footer" class="container-fluid">

        <div class="row">

            <div class="col-xs-4 col-sm-4 col-md-4" id="planterbox">  

                    <img id="grass" src="img/grass.png">

            </div>

        </div>


        <div class="row">
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
                <a href="https://www.etsy.com/shop/veganarmy269?ref=l2-shopheader-name"><img id="va_button" src="img/va_button.png"></a>
            </div>
        </div>
    


        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

               <ul class="foot">
                   <li class="footeritem"><a class="copylink" href="contact.php">Contact</a></li>
                   <li class="footeritem"><a class="copylink" href="">About Us</a></li>
                   <li class="footeritem"><a class="copylink" href="docs/privacy_policy.pdf">Privacy Policy</a></li>
               </ul>

                <p class="copyinfo">(585) 454-9490 | 14 Edmonds Street Rochester, NY 14607<br>
                @2018 Vegan Army LLC. | Web Design by <a class="mylink" href="https://www.facebook.com/leanne.morango" target="blank"> Leanne Morango</a></p>
            </div>

        </div>

</div>

</body>
</html>

