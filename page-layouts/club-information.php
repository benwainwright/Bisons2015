<?php 
$options = get_option('club-info-settings-page');
$mission_statement = $options['mission-statement'];
$who_are_we = $options['who-are-we'];
$home_address = $options['home-address'];
$home_address = str_replace("\n", "<br />", $home_address);
?>
<header>
    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
</header>
<a href='http://online.bisonsrfc.co.uk/wp-content/uploads/2014/04/team.png'><img class='alignright' src='http://online.bisonsrfc.co.uk/wp-content/uploads/2014/04/team.png' /></a>
<?php if($who_are_we) : ?>
<h3>Who are we?</h3>
<p><?php echo $who_are_we; ?></p>    
<?php endif; ?>
<?php if($mission_statement) : ?>
<h3>Mission Statement</h3>
<p><?php echo $mission_statement; ?></p>    
<?php endif; ?>
<div>
    <div class="gmap-border leftmap"><div class="gmap-canvas" id="map-1"></div></div>
    <h3>Home Venue</h3>
    <p class="gmap-address map-1"><?php echo $home_address ?></p>
</div>

<h3 class="clear">Get in touch</h3>
<p>Please do get in touch with us if you have any questions at all. You can use the form below to send an email to one of us and we will get back to you as quickly as we can!</p>
    <form method='post' action='/club-information/' role='form'>

    <div>
        <label for="name">Name</label>
        <input type='text' name='name' id='name' />
    </div>
    <div class='form-group'>
        <label for="email">Email</label>
        <input type='text' name='email' id='email' />
    </div>
    
   <div class='form-group'>
       <label for="subject">Subject</label>
       <input type='text' name='subject' id='subject' />
   </div>
   <div class='form-group'>
       <label for="message">Message content</label>       
       <textarea name='message' rows='4'></textarea>
   </div>
    <div><input type='submit' value='Send email' /></div>
</form>

