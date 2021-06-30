<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('lwpcng_general');
delete_option('lwpcng_appearance');
delete_option('lwpcng_scripts');
delete_option('lwpcng_advanced');
delete_option('lwpcng_rate_time');
