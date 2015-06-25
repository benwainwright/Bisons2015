<?php
if (!INCLUDED) exit;

session_start();
delete_user_meta(get_current_user_id(), '2FA_secret');
delete_user_meta(get_current_user_id(), '2FA_setup');

unset($_SESSION['bisons_skip2FA']);
