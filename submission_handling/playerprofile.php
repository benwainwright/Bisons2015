<?php 
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}


update_post_meta($post, 'name', esc_attr( $_POST['name'] ) );
update_post_meta($post, 'nickname', esc_attr( $_POST['nickname'] ) );
update_post_meta($post, 'age', esc_attr( $_POST['age'] ) );
update_post_meta($post, 'position', esc_attr( $_POST['position'] ) );
update_post_meta($post, 'followed', esc_attr( $_POST['followed'] ) );
update_post_meta($post, 'exp', esc_attr( $_POST['exp'] ) );
update_post_meta($post, 'jexp', esc_attr( $_POST['jexp'] ) );
update_post_meta($post, 'whydoyouplay', esc_attr( $_POST['whydoyouplay'] ) );
update_post_meta($post, 'proplayer', esc_attr( $_POST['proplayer'] ) );
update_post_meta($post, 'proplayerasp', esc_attr( $_POST['proplayerasp'] ) );
update_post_meta($post, 'bestmem', esc_attr( $_POST['bestmem'] ) );
update_post_meta($post, 'sigdish', esc_attr( $_POST['sigdish'] ) );
update_post_meta($post, 'desertisland', esc_attr( $_POST['desertisland'] ) );
update_post_meta($post, 'bestplayer', esc_attr( $_POST['bestplayer'] ) );
update_post_meta($post, 'fastestplayer', esc_attr( $_POST['fastestplayer'] ) );
update_post_meta($post, 'longestshower', esc_attr( $_POST['longestshower'] ) );
update_post_meta($post, 'dresssense', esc_attr( $_POST['dresssense'] ) );
update_post_meta($post, 'biggestmoaner', esc_attr( $_POST['biggestmoaner'] ) );
update_post_meta($post, 'lasttobar', esc_attr( $_POST['lasttobar'] ) );
update_post_meta($post, 'worstdancer', esc_attr( $_POST['worstdancer'] ) );
update_post_meta($post, 'badinfluence', esc_attr( $_POST['badinfluence'] ) );
update_post_meta($post, 'cheesegrindr', esc_attr( $_POST['cheesegrindr'] ) );
update_post_meta($post, 'lastmeal', esc_attr( $_POST['lastmeal'] ) );
update_post_meta($post, 'superst', esc_attr( $_POST['superst'] ) );
update_post_meta($post, 'notholiday', esc_attr( $_POST['notholiday'] ) );
update_post_meta($post, 'eventfromhistory', esc_attr( $_POST['eventfromhistory'] ) );
update_post_meta($post, 'chatup', esc_attr( $_POST['chatup'] ) );
update_post_meta($post, 'possessions', esc_attr( $_POST['possessions'] ) );
update_post_meta($post, 'breakfast', esc_attr( $_POST['breakfast'] ) );
update_post_meta($post, 'movielife', esc_attr( $_POST['movielife'] ) );
update_post_meta($post, 'filmcry', esc_attr( $_POST['filmcry'] ) );
update_post_meta($post, 'living', esc_attr( $_POST['living'] ) );
update_post_meta($post, 'growingup', esc_attr( $_POST['growingup'] ) );
update_post_meta($post, 'cartoon', esc_attr( $_POST['cartoon'] ) );
update_post_meta($post, 'lastfifty', esc_attr( $_POST['lastfifty'] ) );
update_post_meta($post, 'image_id', $_POST['upload_image_id']);

if ($_POST['upload_image_id']) {
    update_post_meta($post, 'image_id', $_POST['upload_image_id']);
} else {
    delete_post_meta($post, 'image_id');
}
