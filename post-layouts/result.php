<?php
$parent = get_post_meta( get_the_id(), 'parent-fixture', true);
$fixdate = get_post_meta( $parent, 'fixture-date', true );
$fixdate = date('jS \o\f F Y', $fixdate); 
$opposing = get_post_meta( $parent, 'fixture-opposing-team', true );
$opplink = get_post_meta( $parent, 'fixture-opposing-team-website-url', true );
$ourscore = get_post_meta( get_the_id(), 'our-score', true );
$theirscore = get_post_meta( get_the_id(), 'their-score', true );
$homeaway = get_post_meta($parent, 'fixture-home-away', true);

?>
<div <?php post_class('post') ?>>
      <header>
          <h2><a href="<?php the_permalink() ?>">Match Result (<?php echo $opposing; ?>)</a></h2>
          <?php include( __DIR__ . '/../snippets/post_meta.php' ) ?>
      </header>
      <p>Match results are now in. Match reports and photos will follow shortly. If you believe this result to be incorrect, please let me know.</p>
        <table class='center resultstable'>
        <tbody>
            <tr>
                <th class="date-col" colspan='4'>
                    <?php echo  $fixdate; ?>
                    <?php if(get_edit_post_link( get_the_id() )) : ?>
                        <ul class='edit-links'>
                            <li><a class='post-edit-link' href='<?php echo get_edit_post_link( get_the_id() ); ?>'>Edit Result</a></li>
                        </ul>
                    <?php endif ?>
                </th>
            </tr>
            <tr>
                <?php if ($homeaway == "Home") : ?>
                <td class="hometeam-col"><span class="homeawaylabel">Home</span>Bristol Bisons RFC</td>
                <td class="scorecell<?php if ( $theirscore == 'TBC') echo ' tbcscore' ?>"><?php echo $ourscore; ?></td>
                <td class="scorecell<?php if ( $ourscore == 'TBC') echo ' tbcscore' ?>"><?php echo $theirscore; ?></td>
                <td class="oppteam-col"><span class="homeawaylabel">Away</span><?php echo team_link($opposing, $opplink); ?></td>
                <?php else : ?>
                <td class="hometeam-col"><span class="homeawaylabel">Home</span><?php echo team_link($opposing, $opplink); ?></td>
                <td class="scorecell<?php if ( $theirscore == 'TBC') echo ' tbcscore' ?>"><?php echo $theirscore; ?></td>
                <td class="scorecell<?php if ( $ourscore == 'TBC') echo ' tbcscore' ?>"><?php echo $ourscore; ?></td>
                <td class="oppteam-col"><span class="homeawaylabel">Away</span>Bristol Bisons RFC</td>
                <?php endif ?>
            
            </tr>
        </tbody>
        </table>
                            
<?php comments_template(); ?>
</div>