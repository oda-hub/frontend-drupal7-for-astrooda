<?php

/**
 * @file
 * Hooks provided by the auto_login_url module.
 */

/**
 * Hook that runs before login.
 *
 * @param object $user
 *  User to login.
 * @param string $destination
 *  Destination link after login.
 */
function hook_pre_auto_login_url($user, $destination) {
  watchdog('my_module', 'User @user, destination @destination',
    array('@user' => $user->id, '@destination' => $destination));
}


/**
 * Hook that runs after login.
 *
 * @param object $user
 *  User to login.
 * @param string $destination
 *  Destination link after login.
 */
function hook_post_auto_login_url($user, $destination) {
  watchdog('my_module', 'User @user, destination @destination',
    array('@user' => $user->id, '@destination' => $destination));
}
