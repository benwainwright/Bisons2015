<?php get_header(); ?>

<div id="wrapper">
	<div id="pagecol">
        <div class='page'>
   			<h2>Whoops!</h2>
            <p class="infoalert">Whatever you tried to find isn't here anymore, unfortunately! If you wait a moment, you will be redirected to our home page...</p>
            <script type="text/javascript">
               setTimeout( function() { window.location.replace ( '<?php echo $GLOBALS['blog_info']['url'] ?>' )}, 5000 );
            </script>
        </div>
    </div>
</div>
<?php get_footer(); ?>
 