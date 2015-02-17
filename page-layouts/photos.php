<?php 
$flikr = new Flikr ( $GLOBALS['api_settings'] );
$userid = $flikr->peopleFindByUsername ( 'BristolBisonsRFC' )->user->nsid;
$userInfo = $flikr->peopleGetInfo( $userid );
$photosurl = $userInfo->person->photosurl->_content;
if ( isset ( $_GET['gallery'] ) ) : 
    
$photos = $flikr->photosetsGetPhotos ( $_GET['gallery'], 'url_q,url_z,url_m' )->photoset->photo;
$photoinfo = $flikr->photosetsGetInfo( $_GET['gallery'] )->photoset;


$title = $photoinfo->title->_content;
$description = $photoinfo->description->_content;
$created = date ( 'jS \o\f F Y' , $photoinfo->date_create );
$updated = date ( 'jS \o\f F Y' , $photoinfo->date_update );

?>
<header>
    <h2><a href="<?php the_permalink() ?>"><?php echo $title ?></a></h2>
    <p>Album created on the <?php echo $created ?><?php if ( $created != $updated ) { ?> and last updated on the <?php echo $updated; } ?></p>
</header>
<p>Click photos below to view. To download the photos at their original resolutions, have a look at <a href='<?php echo $photosurl.'sets/'.$_GET['gallery'] ?>' title='<?php echo $userid ?> on Flickr'>our Flickr page</a>.</p>

<table class="photogallery">
    <tbody>
        
<?php 

$cols = 5;
$i = 0;
foreach ( $photos as $photo ) :
    

    if ($i == 0 ) echo "<tr>";
    echo "<td><a class=\"image-link mobilethumb\" href='$photo->url_z'><img src='$photo->url_m' /></a></td>";
    if ($i == $cols - 1) : echo "</tr>"; $i = 0;
    else : $i++; 
    endif;
endforeach;

if ( $i != 0 ) echo "</tr>"; ?>
    </tbody>
</table>
<?php else : ?>
<header>
    <h2><a href="<?php the_permalink() ?>">Photo Albums</a></h2>
    <p>Courtesy of <a href='http://www.flickr.com/'>Flickr</a></p>
</header>
<table class="photosets">
    <tbody>
        
<?php
$photosets = $flikr->photosetsGetList ( $userid, false, false, 'url_m, url_q' )->photosets->photoset;

foreach ( $photosets as $set ) :

    $id = $set->id;
    $title = $set->title->_content;
    $description = $set->description->_content;
    $created = date ( 'jS \o\f F Y' , $set->date_create );
    $modified = date (  'jS \o\f F Y', $set->date_update );
    $primary_src = $set->primary_photo_extras->url_q;
    $bigger_src = $set->primary_photo_extras->url_m; ?>
<tr>
    <td class="thumbs"><a class="desktopthumb" href='?gallery=<?php echo $id ?>'><img src='<?php echo $primary_src ?>' /></a></td>
    <td><h3><a href='?gallery=<?php echo $id ?>'><?php echo $title ?></a></h3>
        <ul class="metalist">
            <?php if ($description) : ?><li><strong>Description</strong>: <?php echo $description ?></li><?php endif ?>
            <li><strong>Date Created</strong>: <?php echo $created ?></li>
        </li>
    </td>
</tr>    

    
<?php endforeach; ?>

</tbody>
</table>
<?php endif ?>
