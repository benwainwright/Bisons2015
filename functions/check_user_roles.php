<?php

/**
 * Helper function to check a specific user against a single role or a list of roles
 * @param string|array $roles This can be a string containing the role to be tested against, or an array of roles
 * @param integer $user_id A specific user to test against. If this isn't set, the function will default to the current user
 * @return bool
 */
function check_user_role( $roles, $user_id = null ) {

    // If a numeric user_id is passed into the function, use it, otherwise use the current_user.
    if ( is_numeric( $user_id ) )
        $user = get_userdata( $user_id );
    else
        $user = wp_get_current_user();

    // If there isn't a user logged in at all, or none was passed into the function, return false
    if ( empty( $user ) )
        return false;

    // If the roles variable is an array, loop through it and test to see if the current user has that role. As soon as the role is found, return true;
    if (is_array($roles) ) {
        foreach($roles as $role) {
            if (in_array( $role, (array) $user->roles )) return true;
        }
    } else {

        // If the roles variable is a string, check to see if the current user has it, then return either true or false.
        return in_array( $roles, (array) $user->roles );
    }

}