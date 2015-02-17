<?php get_header(); ?>

<div id="wrapper">
	<div id="pagecol">
        <div class='page'>
   
            <p class="infoalert">Sorry... but the content you were looking for isn't here. Redirecting you to our homepage, please wait...</p>
            <script type="text/javascript">
                setTimeout( function() { window.location.replace ( '<?php echo $GLOBALS['blog_info']['url'] ?>' )}, 5000 );
            </script>
        </div>
    </div>
</div>
<?php get_footer(); ?>
 