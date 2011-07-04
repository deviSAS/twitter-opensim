<!DOCTYPE html>
<html>
<head>

<title>devi</title>
<meta charset="UTF-8" />
<meta name="author" content="devi S.A.S" />

<link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/devi.js"></script>

<!-- Plus One -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'es-419'}
</script>
<!-- Facebook like -->
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#appId=186101451444149&amp;xfbml=1"></script>

<?php wp_head(); ?>

<!-- Analytics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-12774956-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</head>

<body>
<div class="content">
    <header>
        <div class="top-feed">
            <div class="left"></div>
            <div class="text"><!-- feed text--></div>
            <div class="right"></div>
        </div>
        <div class="top-block">
          <div class="logo"></div>
          <div class="search">
            <input type="search" id="search_field" class="field"/>
            <input name="search" type="submit" value="&nbsp;" class="button"/>
          </div>
        </div>
    </header>
    <div class="top-hr"></div>
	<div class="site-block">
    	<menu>
        	<?php wp_list_categories(); ?>
        </menu>