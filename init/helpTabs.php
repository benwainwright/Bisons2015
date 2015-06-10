<?php

/********************* Add Help Tab *******************/
function addHelpTabs () {
	$screen = get_current_screen();

	$content = '<h2>' . __( 'Fixtures' ) . '</h2>' .
	           '<p>' . __(  'The \'Fixtures\' page can be used to add or edit any fixtures listed on the' .
	                        '\'fixtures\' page of the website. To add a fixture, click on the \'Add New\' ' .
	                        'button at the top of the page and fill in all the relavent details.' .
	                        ' If we have not played against the team before, you will first need ' .
	                        'to create a \'team\' entry by hovering over \'fixtures\' in the Wordpress ' .
	                        'sidebar and clicking on \'teams\'.') . '</p>';



	$screen->add_help_tab( array(
		'id'	=> 'adding_fixtures',
		'title'	=> __('Fixtures'),
		'content'	=> $content,
	) );

	$content = '<h2>' . __( 'Results' ) . '</h2>' .
	           '<p>' . __(  'Fixture results can be recorded in one of two ways:') . '</p>' .
				'<ol>' .
				'<li>' .__( 'Go to the \'Fixtures\' page in the dashboard, hover over the relavent ' .
				            'cell in the \'Score\' column, and click \'Add Score\'').'</li>' .
				'</ol>' ;

	$screen->add_help_tab( array(
		'id'	=> 'adding_results',
		'title'	=> __('Results'),
		'content'	=> $content,
	) );

}
