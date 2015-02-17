<?php header('content-type:text/css'); ?>

<?php
$icons_for_custom_types = array(
    'post' => 'post',
    'event' => 'event',
    'result' => 'result' ,
    'report' => 'report',
    'fixture' => 'fixture',
    'player-page' => 'post',
    'committee-page' => 'post'
);

$icons_for_default_pages = array(
    'post' => 'post',
    'page' => 'post'

);

$bigicon = isset($icons_for_custom_types[ $_GET['post-type'] ]) ?
    $icons_for_custom_types[ $_GET['post-type'] ] :
    $_GET['post-type'];

$bigicon = isset($icons_for_default_pages[ $_GET['post-type'] ]) ?
    $icons_for_custom_types[ $_GET['post-type'] ] :
    $bigicon;


?>

<?php if( file_exists( "images/$bigicon-32.png" ) ) : ?>
    #icon-edit { background: transparent url('images/<?php echo $bigicon; ?>-32.png') no-repeat 5px 2px; }
<?php endif; ?>

<?php foreach($icons_for_default_pages as $key => $icon) { ?>

<?php if( file_exists( "images/$icon-16.png" ) ) : ?>
#adminmenu #menu-<?php echo $key; ?>s .wp-menu-image {
    background-color:transparent;
    background-image:url('images/<?php echo $icon; ?>-16.png');
    background-repeat:no-repeat;
    background-position:6px 6px;
    }
<?php endif; ?>
<?php } ?>

<?php foreach($icons_for_custom_types as $key => $icon) { ?>
<?php if( file_exists( "images/$icon-16.png" ) ) : ?>
#adminmenu #menu-posts-<?php echo $key; ?> .wp-menu-image {
    background-color:transparent;
    background-image:url('images/<?php echo $icon; ?>-16.png');
    background-repeat:no-repeat;
    background-position:6px 6px;
}

<?php endif; ?>
<?php } ?>


.fixture-reports-results-list li {
list-style:none;
}

.description {
margin-left: 5px;
}

.email_log li 
{
    padding-bottom:10px; 
}
.spacer {
clear:both;
}

p.submit {
overflow:auto;
float:none;
margin:1em;
}

#custom-form tr {
border-bottom:1px solid #dfdfdf;
border-top: 1px solid white;
}

#custom-form tr:first-child {
border-bottom:1px solid #dfdfdf;
border-top: 0;
}

#custom-form tr:last-child {
border-top:white;
border-bottom: 0;
}



#custom-form .submit input {
float:right;
margin-left:10px;
}

#custom-form textarea { width:100%; height:200px;}
#custom-form .small { width:auto; ;height:100px;}
#custom-form img { max-width:100%;}

.wp-list-table ul, .wp-list-table li  {
    margin:0;
}


#custom-form .submit {
margin:0.5em !important;
}
#custom-form .description {
    display:block;
    margin-top:0.5em;
}

#custom-form .submit .description {
    text-align:right;
}

.bottom-buttons {
padding-top:1em;
border-top:1px solid #dfdfdf;
}


.embed-map {
width:100%;
display:none;
}

.map-row td {
    margin:10px;
    display:block;
} 
.map-row td{
    height:300px;
}

#sidebar .numfield {
width:20px;
margin:0px;
border-radius:3px;
-webkit-border-radius:3px;
border-width:1px;
border-style:solid;
text-align:center;
}
.large-text {
    height:150px;
}

#TB_window { width: 80% !important; max-width:800px !important; min-width:300px !important; }
iframe#TB_iframeContent { width: 100% !important; }
div#media-items { width: 100% !important; }
#media-upload #filter { width: 95% !important; }

.playermanagement td ul { margin:0; }
.playermanagement td li { margin-bottom:3px; }
.playermanagement .noform { font-weight:bold; }

.playermanagement button {
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
background: white;
border: 1px solid grey;
}

#custom-form p.formerror {
margin: 0.5em 0;
color: #D45F57;
padding: 0.3em;
background-color: #FAE5E3;
border: 1px solid #D45F57;
}
