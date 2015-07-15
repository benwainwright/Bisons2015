<?php
wp_enqueue_script( 'dynamicforms' );
wp_enqueue_script( 'formvalidation' );

$ppForm = new Wordpress_Form ( 'playerprofile', 'name', 'post', 'Submit', 'submit_player_profile' );


$ppForm->isEditorFor('currentUserMeta');


$ppForm->add_fieldset( 'aboutyou', 'About You' );
$ppForm->add_file_upload( 'aboutyou', 'photo', 'Photograph', 'image_id' );
$ppForm->add_text_input( 'aboutyou', 'ppName', 'First Name *', 'notempty' );
$ppForm->add_text_input( 'aboutyou', 'nickname', 'Club Nickname' );
$ppForm->add_text_input( 'aboutyou', 'age', 'Age *', 'notempty' );
$ppForm->add_text_input( 'aboutyou', 'living', 'What do you do for a living?' );

$ppForm->add_fieldset( 'youandthebisons', 'You and the Bisons' );
$ppForm->add_text_input( 'youandthebisons', 'jexp',
	'How much did you know about the game when you first started playing with the Bisons? *', 'notempty' );
$ppForm->add_text_input( 'youandthebisons', 'whydoyouplay', 'Why do you play for The Bisons?' );
$ppForm->add_text_input( 'youandthebisons', 'bestmem', 'Best Bisons memory or acheivement?' );
$ppForm->add_text_input( 'youandthebisons', 'position',
	'What is your usual position? (Interpret that however you want...)' );

$ppForm->add_fieldset( 'youandrugby', 'You and the Bisons',
	'We appreciate that some of you may be very new to the game. If you can\'t answer some of these, just leave them blank, with the exception of those fields with an asterisk (*) next to the label.' );
$ppForm->add_text_input( 'youandthebisons', 'exp', 'How long have you been playing rugby? *', 'notempty' );
$ppForm->add_text_input( 'youandthebisons', 'followed', 'Which club or team do you follow?' );
$ppForm->add_text_input( 'youandthebisons', 'proplayerasp',
	'Which professional player would you like to perform like, and why?' );
$ppForm->add_text_input( 'youandthebisons', 'proplayer',
	'Which professional player <strong>do</strong> you perform like and why?' );
$ppForm->add_text_input( 'youandthebisons', 'superst', 'Do you have any prematch superstitions/routines?' );

$ppForm->add_fieldset( 'theinterview', 'The Interview' );
$ppForm->add_text_input( 'theinterview', 'chatup', 'What is your best chat up line?' );
$ppForm->add_text_input( 'theinterview', 'breakfast', 'What do you normally eat for breakfast?' );
$ppForm->add_text_input( 'theinterview', 'possessions', 'What is your most treasured possession?' );
$ppForm->add_text_input( 'theinterview', 'notholiday', 'Where is the one place you would never go on holiday?' );

$ppForm->add_text_input( 'theinterview', 'movielife', 'In the movie of your life, who would you be played by?' );
$ppForm->add_text_input( 'theinterview', 'growingup', 'When you were growing up, what did you want to be?' );
$ppForm->add_text_input( 'theinterview', 'filmcry', 'Name the last film that made you cry?' );
$ppForm->add_text_input( 'theinterview', 'eventfromhistory',
	'If you could turn back time and witness one event from history, what would it be?' );
$ppForm->add_text_input( 'theinterview', 'sigdish', 'What is your signature dish?' );
$ppForm->add_text_input( 'theinterview', 'lastmeal', 'What would your last meal be?' );
$ppForm->add_text_input( 'theinterview', 'desertisland',
	'Stranded on a desert island, what would your three essential items be?' );
$ppForm->add_text_input( 'theinterview', 'lastfifty', 'What would you buy with your last fifty pounds?' );

$ppForm->add_fieldset( 'theteam', 'The Team', 'Out of the current team, who...' );
$ppForm->add_text_input( 'theteam', 'bestplayer', 'Is the best player?' );
$ppForm->add_text_input( 'theteam', 'fastestplayer', 'Is the fastest?' );
$ppForm->add_text_input( 'theteam', 'longestshower', 'Takes the longest to shower?' );
$ppForm->add_text_input( 'theteam', 'biggestmoaner', 'Is the biggest moaner?' );
$ppForm->add_text_input( 'theteam', 'dresssense', 'Has the worst dress sense?' );
$ppForm->add_text_input( 'theteam', 'lasttobar', 'Is the last to the bar?' );
$ppForm->add_text_input( 'theteam', 'worstdancer', 'Is the worst dancer?' );
$ppForm->add_text_input( 'theteam', 'badinfluence', 'Is the worst influence?' );
$ppForm->add_text_input( 'theteam', 'cheesegrindr', 'Has the cheesiest Grindr profile?' );





?>
<header>
	<h2>Player Profile</h2>
	<?php get_template_part( 'snippets/playerPage', 'menu' ) ?>

</header>
<?php get_template_part( 'snippets/playerPage', 'flashMessages' ) ?>
<?php if ( $ppForm->is_errors() ) : ?>
	<ul class='formerrors'>
		<?php foreach ( $ppForm->get_form_errors() as $error ) : ?>
			<li><?php echo $error ?></li><?php endforeach ?>
	</ul>
<?php endif ?>

<?php if ( $ppForm->submit_form() ) : ?>
	<p class='flashmessage'>Profile saved.</p>
<?php endif ?>

<p class="important">To be featured on the 'player profiles' section of the website, have a go at filling out the
	form below. Please note that the committee reserves the right not to publish content that you submit, and may make
	significant edits in order to maintain the good name of the club.</p>
<p>Please do not feel that you have to fill in all the fields on this form; the only fields that are mandatory are those
	with an asterisk (*) by them. There are a lot of different questions simply to ensure that everybody has something
	to say.</p>

<?php
$ppForm->form_output();
?>
