<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'main';
$query_builder = TRUE;

$db['main'] = array(
	'hostname' => DB_HOST,
	'username' => ENVIRONMENT !== 'production' ? DB_USERNAME_DEV : DB_USERNAME,
	'password' => ENVIRONMENT !== 'production' ? DB_PASSWORD_DEV : DB_PASSWORD,
	'database' => ENVIRONMENT !== 'production' ? MAIN_DB_DEV : MAIN_DB,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['hr'] = array(
	'hostname' => DB_HOST,
	'username' => ENVIRONMENT !== 'production' ? DB_USERNAME_DEV : DB_USERNAME,
	'password' => ENVIRONMENT !== 'production' ? DB_PASSWORD_DEV : DB_PASSWORD,
	'database' => ENVIRONMENT !== 'production' ? HR_DB_DEV : HR_DB,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['qhse'] = array(
	'hostname' => DB_HOST,
	'username' => ENVIRONMENT !== 'production' ? DB_USERNAME_DEV : DB_USERNAME,
	'password' => ENVIRONMENT !== 'production' ? DB_PASSWORD_DEV : DB_PASSWORD,
	'database' => ENVIRONMENT !== 'production' ? QHSE_DB_DEV : QHSE_DB,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['general'] = array(
	'hostname' => DB_HOST,
	'username' => ENVIRONMENT !== 'production' ? DB_USERNAME_DEV : DB_USERNAME,
	'password' => ENVIRONMENT !== 'production' ? DB_PASSWORD_DEV : DB_PASSWORD,
	'database' => ENVIRONMENT !== 'production' ? GENERAL_DB_DEV : GENERAL_DB,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['mtn'] = array(
	'hostname' => DB_HOST,
	'username' => ENVIRONMENT !== 'production' ? DB_USERNAME_DEV : DB_USERNAME,
	'password' => ENVIRONMENT !== 'production' ? DB_PASSWORD_DEV : DB_PASSWORD,
	'database' => ENVIRONMENT !== 'production' ? MTN_DB_DEV : MTN_DB,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['chat'] = array(
	'hostname' => DB_HOST,
	'username' => ENVIRONMENT !== 'production' ? DB_USERNAME_DEV : DB_USERNAME,
	'password' => ENVIRONMENT !== 'production' ? DB_PASSWORD_DEV : DB_PASSWORD,
	'database' => ENVIRONMENT !== 'production' ? CHAT_DB_DEV : CHAT_DB,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt'  => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);