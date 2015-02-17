<!DOCTYPE HTML>
<html>
	<head>
       
		<title><?php wp_title('-',true,'right'); ?> <?php echo $GLOBALS['blog_info']['name']; ?></title>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 	 <meta name="viewport" content="width=device-width, minumum-scale=1.0, maximum-scale=1.0">
 
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>+            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <!-- ****** The below are all courtesy of favicon.com ****** -->
        <link rel="shortcut icon" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon.ico">
        <link rel="icon" sizes="16x16 32x32 64x64" href="/favicon.ico">
        <link rel="icon" type="image/png" sizes="196x196" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-196.png">
        <link rel="icon" type="image/png" sizes="160x160" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-160.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-96.png">
        <link rel="icon" type="image/png" sizes="64x64" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-64.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-16.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-152.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-144.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-120.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-114.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-76.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-72.png">
        <link rel="apple-touch-icon" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-57.png">
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-TileImage" content="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/favicon-144.png">
        <meta name="msapplication-config" content="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicons/browserconfig.xml">

        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $GLOBALS['blog_info']['template_url']; ?>/images/favicon.ico" />

		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		<link rel="pingback" href="echo $GLOBALS['blog_info']['pingback_url']; ?>" />
		<?php wp_head(); ?>

	</head>
	<body>
		
	
	<header id="mainheader">
	    <div id="col">

          <a class="logo" href="<?php echo $GLOBALS['blog_info']['url'] ?>" title="Return to the homepage">
            <img style="border-style:none;" src="<?php echo get_template_directory_uri(); ?>/images/pinkbisonsvg.svg" alt="Bisons Logo" />
                      <div class="expand"></div>
          </a>
            <img id='showmenu' src='<?php echo get_template_directory_uri(); ?>/images/menu.svg' alt='showmenu'>



         
          <div class="title">
		<h1><?php echo $GLOBALS['blog_info']['name']; ?></h1>
		<p><?php echo $GLOBALS['blog_info']['description']; ?></p>
		</div>
                        <div class="expand"></div>

                    </div>

	</header>
        <div id="menu">

        <?php get_template_part('menu'); ?>

 
        </div>
  