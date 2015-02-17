<?php
function add_styles($buttons) {

    //Add style selector to the beginning of the toolbar
    array_unshift($buttons, 'formatselect');
    return $buttons;
}
add_filter('mce_buttons', 'add_styles');
