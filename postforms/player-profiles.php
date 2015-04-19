<?php
$image_id = get_post_meta( $post->ID, 'image_id', true);
$image_id = is_object( $image_id ) ? "" : $image_id;


$image_url = wp_get_attachment_thumb_url( $image_id ) ;
?>

<div id='custom-form'>
    <table class="form-table">
        <tbody>

            <tr>
                <th><label for="name">Name</label></th>
                <td>
                    <input class='regular-text' type='text' name='name' value='<?php echo get_post_meta( $post->ID, 'name', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="nickname">Club Nickname</label></th>
                <td>
                    <input class='regular-text'  type='text' name='nickname' value='<?php echo get_post_meta( $post->ID, 'nickname', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="age">Age</label></th>
                <td>
                    <input class='small-text' type='text' name='age' value='<?php echo get_post_meta( $post->ID, 'age', true); ?>' />
                </td>
            </tr>
            
              <tr>
                <th><label for="living">What do you do for a living? </label></th>
                <td>
                    <textarea class='tiny' name='living'><?php echo get_post_meta( $post->ID, 'living', true); ?></textarea>
                </td>
            </tr>            
            <tr>
                <th><label for="position">Usual Position</label></th>
                <td>
                    <textarea class='tiny' name='position'><?php echo get_post_meta( $post->ID, 'position', true); ?></textarea>
                    <span class="description">On the <strong>pitch</strong>, that is...</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="followed">What club or team do you follow?</label></th>
                <td>
                    <textarea class='tiny' name='followed'><?php echo get_post_meta( $post->ID, 'followed', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="exp">How long have you been playing?</label></th>
                <td>
                    <textarea class='tiny' name='exp'><?php echo get_post_meta( $post->ID, 'exp', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="jexp">How much did you know about rugby when you joined?</label></th>
                <td>
                    <textarea class='tiny' name='jexp'><?php echo get_post_meta( $post->ID, 'jexp', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="whydoyouplay">Why do you play for the Bisons?</label></th>
                <td>
                    <textarea class='tiny' name='whydoyouplay'><?php echo get_post_meta( $post->ID, 'whydoyouplay', true); ?></textarea>
                </td>
            </tr> 
            <tr>
                <th><label for="proplayerasp">Which professional player would you like to perform like, and why?</label></th>
                <td>
                    <textarea class='tiny' name='proplayerasp'><?php echo get_post_meta( $post->ID, 'proplayerasp', true); ?></textarea>
                </td>
            </tr>         
            <tr>
                <th><label for="proplayer">Which professional player <em>do</em> you perform like, and why?</label></th>
                <td>
                    <textarea class='tiny' name='proplayer'><?php echo get_post_meta( $post->ID, 'proplayer', true); ?></textarea>
                </td>
            </tr>
           <tr>
                <th><label for="chatup">What is your best chat up line?</label></th>
                <td>
                    <textarea class='tiny' name='chatup'><?php echo get_post_meta( $post->ID, 'chatup', true); ?></textarea>
                </td>
            </tr>
            
            <tr>
                <th><label for="growingup">When you were growing up, what did you want to be?</label></th>
                <td>
                    <textarea class='tiny' name='growingup'><?php echo get_post_meta( $post->ID, 'growingup', true); ?></textarea>
                </td>
            </tr>  
            <tr>
                <th><label for="superst">Do you have any prematch superstitions/routines?</label></th>
                <td>
                    <textarea class='tiny' name='superst'><?php echo get_post_meta( $post->ID, 'superst', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="possessions">Your most treasured possession/s:</label></th>
                <td>
                    <textarea class='tiny' name='possessions'><?php echo get_post_meta( $post->ID, 'possessions', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="breakfast">What do you normally eat for breakfast?</label></th>
                <td>
                    <textarea class='tiny' name='breakfast'><?php echo get_post_meta( $post->ID, 'breakfast', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="bestmem">Best achievement/memory of being in the club? </label></th>
                <td>
                    <textarea class='tiny' name='bestmem'><?php echo get_post_meta( $post->ID, 'bestmem', true); ?></textarea>
                </td>
            </tr>

                        
         <tr>
                <th><label for="notholiday">Where is the one place you'd never go on holiday?</label></th>
                <td>
                    <textarea class='tiny' name='notholiday'><?php echo get_post_meta( $post->ID, 'notholiday', true); ?></textarea>
                </td>
            </tr>
                     <tr>
                <th><label for="movielife">In the movie of your life, who would you be played by?</label></th>
                <td>
                    <textarea class='tiny' name='movielife'><?php echo get_post_meta( $post->ID, 'movielife', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="filmcry">What was the last film that made you cry?</label></th>
                <td>
                    <textarea class='tiny' name='filmcry'><?php echo get_post_meta( $post->ID, 'filmcry', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="cartoon">Favourite cartoon as a kid?</label></th>
                <td>
                    <textarea class='tiny' name='cartoon'><?php echo get_post_meta( $post->ID, 'cartoon', true); ?></textarea>
                </td>
            </tr>
           <tr>
                <th><label for="eventfromhistory">If you could turn back time and witness one event from history, what would it be?</label></th>
                <td>
                    <textarea class='tiny' name='eventfromhistory'><?php echo get_post_meta( $post->ID, 'eventfromhistory', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="sigdish">What is your signature dish?</label></th>
                <td>
                    <textarea class='tiny' name='sigdish'><?php echo get_post_meta( $post->ID, 'sigdish', true); ?></textarea>
                </td>
            </tr>

 
            
            <tr>
                <th><label for="lastmeal">What would your last meal be?</label></th>
                <td>
                    <textarea class='tiny' name='lastmeal'><?php echo get_post_meta( $post->ID, 'lastmeal', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="desertisland">Stranded on a desert island, what are your three essential items?</label></th>
                <td>
                    <textarea class='tiny' name='desertisland'><?php echo get_post_meta( $post->ID, 'desertisland', true); ?></textarea>
                </td>
            </tr>                    

            <tr>
                <th><label for="lastfifty">What would you buy with your last fifty pounds?</label></th>
                <td>
                    <textarea class='tiny' name='lastfifty'><?php echo get_post_meta( $post->ID, 'lastfifty', true); ?></textarea>
                </td>
            </tr>       
        </tbody>
    </table>
    
    <p>Of the current team members, which do you think ...</p>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="bestplayer">Is the best player?</label></th>
                <td>
                    <textarea class='tiny' name='bestplayer'><?php echo get_post_meta( $post->ID, 'bestplayer', true); ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="fastestplayer">Is the fastest player?</label></th>
                <td>
                    <textarea class='tiny' name='fastestplayer'><?php echo get_post_meta( $post->ID, 'fastestplayer', true); ?></textarea>
                </td>
            </tr>
                       <tr>
                <th><label for="longestshower">Takes the longest to shower?</label></th>
                <td>
                    <textarea class='tiny' name='longestshower'><?php echo get_post_meta( $post->ID, 'longestshower', true); ?></textarea>
                </td>
            </tr>     
           <tr>
                <th><label for="biggestmoaner">Is the biggest moaner?</label></th>
                <td>
                    <textarea class='tiny' name='biggestmoaner'><?php echo get_post_meta( $post->ID, 'biggestmoaner', true); ?></textarea>
                </td>
            </tr>
           <tr>
                <th><label for="dresssense">Has the worst dress sense?</label></th>
                <td>
                    <textarea class='tiny' name='dresssense'><?php echo get_post_meta( $post->ID, 'dresssense', true); ?></textarea>
                </td>
            </tr>  
                       <tr>
                <th><label for="lasttobar">Is last to the bar?</label></th>
                <td>
                    <textarea class='tiny' name='lasttobar'><?php echo get_post_meta( $post->ID, 'lasttobar', true); ?></textarea>
                </td>
            </tr>   
                       <tr>
                <th><label for="worstdancer">Is the worst Dancer?</label></th>
                <td>
                    <textarea class='tiny' name='worstdancer'><?php echo get_post_meta( $post->ID, 'worstdancer', true); ?></textarea>
                </td>
            </tr>
                       <tr>
                <th><label for="badinfluence">Is the worst influence on others?</label></th>
                <td>
                    <textarea class='tiny' name='badinfluence'><?php echo get_post_meta( $post->ID, 'badinfluence', true); ?></textarea>
                </td>
            </tr>                      
                       <tr>
                <th><label for="cheesegrindr">Has the cheesiest Grindr profile?</label></th>
                <td>
                    <textarea class='tiny' name='cheesegrindr'><?php echo get_post_meta( $post->ID, 'cheesegrindr', true); ?></textarea>
                </td>
            </tr>         

        </tbody>
    </table>
</div>
            
