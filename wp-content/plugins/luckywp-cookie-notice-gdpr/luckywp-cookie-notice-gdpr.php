<?php
/*
Plugin Name: LuckyWP Cookie Notice (GDPR)
Plugin URI: https://theluckywp.com/product/cookie-notice-gdpr/
Description: The plugin allows you to notify visitors about the use of cookies (necessary to comply with the GDPR in the EU).
Version: 1.2
Author: LuckyWP
Author URI: https://theluckywp.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: luckywp-cookie-notice-gdpr
Domain Path: /languages

LuckyWP Cookie Notice (GDPR) is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LuckyWP Cookie Notice (GDPR) is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LuckyWP Cookie Notice (GDPR). If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

require 'lwpcngAutoloader.php';
$lwpcngAutoloader = new lwpcngAutoloader();
$lwpcngAutoloader->register();
$lwpcngAutoloader->addNamespace('luckywp\cookieNoticeGdpr', __DIR__);

$config = require(__DIR__ . '/config/plugin.php');
(new \luckywp\cookieNoticeGdpr\plugin\Plugin($config))->run('1.2', __FILE__, 'lwpcng_');

require_once __DIR__ . '/functions.php';
