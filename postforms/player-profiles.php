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
                    <input class='regular-text' type='text' name='living' value='<?php echo get_post_meta( $post->ID, 'living', true); ?>' />
                </td>
            </tr>            
            <tr>
                <th><label for="position">Usual Position</label></th>
                <td>
                    <input  class='regular-text' type='text' name='position' value='<?php echo get_post_meta( $post->ID, 'position', true); ?>' />
                    <span class="description">On the <strong>pitch</strong>, that is...</span>
                </td>
            </tr>
            
            <tr>
                <th><label for="followed">What club or team do you follow?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='followed' value='<?php echo get_post_meta( $post->ID, 'followed', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="exp">How long have you been playing?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='exp' value='<?php echo get_post_meta( $post->ID, 'exp', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="jexp">How much did you know about rugby when you joined?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='jexp' value='<?php echo get_post_meta( $post->ID, 'jexp', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="whydoyouplay">Why do you play for the Bisons?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='whydoyouplay' value='<?php echo get_post_meta( $post->ID, 'whydoyouplay', true); ?>' />
                </td>
            </tr> 
            <tr>
                <th><label for="proplayerasp">Which professional player would you like to perform like, and why?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='proplayerasp' value='<?php echo get_post_meta( $post->ID, 'proplayerasp', true); ?>' />
                </td>
            </tr>         
            <tr>
                <th><label for="proplayer">Which professional player <em>do</em> you perform like, and why?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='proplayer' value='<?php echo get_post_meta( $post->ID, 'proplayer', true); ?>' />
                </td>
            </tr>
           <tr>
                <th><label for="chatup">What is your best chat up line?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='chatup' value='<?php echo get_post_meta( $post->ID, 'chatup', true); ?>' />
                </td>
            </tr>
            
            <tr>
                <th><label for="growingup">When you were growing up, what did you want to be?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='growingup' value='<?php echo get_post_meta( $post->ID, 'growingup', true); ?>' />
                </td>
            </tr>  
            <tr>
                <th><label for="superst">Do you have any prematch superstitions/routines?</label></th>
                <td>
                    <input  class='regular-text' type='text' name='superst' value='<?php echo get_post_meta( $post->ID, 'superst', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="possessions">Your most treasured possession/s:</label></th>
                <td>
                    <input  class='regular-text' type='text' name='possessions' value='<?php echo get_post_meta( $post->ID, 'possessions', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="breakfast">What do you normally eat for breakfast?</label></th>
                <td>
                    <input class='regular-text' type='text' name='breakfast' value='<?php echo get_post_meta( $post->ID, 'breakfast', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="bestmem">Best achievement/memory of being in the club? </label></th>
                <td>
                    <input class='regular-text' type='text' name='bestmem' value='<?php echo get_post_meta( $post->ID, 'bestmem', true); ?>' />
                </td>
            </tr>

                        
         <tr>
                <th><label for="notholiday">Where is the one place you'd never go on holiday?</label></th>
                <td>
                    <input class='regular-text' type='text' name='notholiday' value='<?php echo get_post_meta( $post->ID, 'notholiday', true); ?>' />
                </td>
            </tr>
                     <tr>
                <th><label for="movielife">In the movie of your life, who would you be played by?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='movielife' value='<?php echo get_post_meta( $post->ID, 'movielife', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="filmcry">What was the last film that made you cry?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='filmcry' value='<?php echo get_post_meta( $post->ID, 'filmcry', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="cartoon">Favourite cartoon as a kid?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='cartoon' value='<?php echo get_post_meta( $post->ID, 'cartoon', true); ?>' />
                </td>
            </tr>
           <tr>
                <th><label for="eventfromhistory">If you could turn back time and witness one event from history, what would it be?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='eventfromhistory' value='<?php echo get_post_meta( $post->ID, 'eventfromhistory', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="sigdish">What is your signature dish?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='sigdish' value='<?php echo get_post_meta( $post->ID, 'sigdish', true); ?>' />
                </td>
            </tr>

 
            
            <tr>
                <th><label for="lastmeal">What would your last meal be?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='lastmeal' value='<?php echo get_post_meta( $post->ID, 'lastmeal', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="desertisland">Stranded on a desert island, what are your three essential items?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='desertisland' value='<?php echo get_post_meta( $post->ID, 'desertisland', true); ?>' />
                </td>
            </tr>                    

            <tr>
                <th><label for="lastfifty">What would you buy with your last fifty pounds?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='lastfifty' value='<?php echo get_post_meta( $post->ID, 'lastfifty', true); ?>' />
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
                    <input class='regular-text'  type='text' name='bestplayer' value='<?php echo get_post_meta( $post->ID, 'bestplayer', true); ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="fastestplayer">Is the fastest player?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='fastestplayer' value='<?php echo get_post_meta( $post->ID, 'fastestplayer', true); ?>' />
                </td>
            </tr>
                       <tr>
                <th><label for="longestshower">Takes the longest to shower?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='longestshower' value='<?php echo get_post_meta( $post->ID, 'longestshower', true); ?>' />
                </td>
            </tr>     
           <tr>
                <th><label for="biggestmoaner">Is the biggest moaner?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='biggestmoaner' value='<?php echo get_post_meta( $post->ID, 'biggestmoaner', true); ?>' />
                </td>
            </tr>
           <tr>
                <th><label for="dresssense">Has the worst dress sense?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='dresssense' value='<?php echo get_post_meta( $post->ID, 'dresssense', true); ?>' />
                </td>
            </tr>  
                       <tr>
                <th><label for="lasttobar">Is last to the bar?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='lasttobar' value='<?php echo get_post_meta( $post->ID, 'lasttobar', true); ?>' />
                </td>
            </tr>   
                       <tr>
                <th><label for="worstdancer">Is the worst Dancer?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='worstdancer' value='<?php echo get_post_meta( $post->ID, 'worstdancer', true); ?>' />
                </td>
            </tr>
                       <tr>
                <th><label for="badinfluence">Is the worst influence on others?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='badinfluence' value='<?php echo get_post_meta( $post->ID, 'badinfluence', true); ?>' />
                </td>
            </tr>                      
                       <tr>
                <th><label for="cheesegrindr">Has the cheesiest Grindr profile?</label></th>
                <td>
                    <input class='regular-text'  type='text' name='cheesegrindr' value='<?php echo get_post_meta( $post->ID, 'cheesegrindr', true); ?>' />
                </td>
            </tr>         

        </tbody>
    </table>
</div>
            
