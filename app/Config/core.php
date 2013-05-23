<?php
/**
 * Application core configuration file.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Config
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

/** Load the production server settings if available. */
@include('production.php');


if(defined('APP_DEBUG'))
	Configure::write('debug', APP_DEBUG);
else
	Configure::write('debug', 2);

Configure::write('Error', array(
	'handler' => 'ErrorHandler::handleError',
	'level' => E_ALL & ~E_DEPRECATED,
	'trace' => true
));

Configure::write('Exception', array(
	'handler' => 'ErrorHandler::handleException',
	'renderer' => 'ExceptionRenderer',
	'log' => true
));

define('LOG_ERROR', LOG_ERR);


Configure::write('App.encoding', 'UTF-8');
Configure::write('App.name', 'Plugin Mirror');

Configure::write('App.svn_url', 'https://plugins.svn.wordpress.org');

Configure::write('App.max_cloning_per_ip', 4);

Configure::write('App.plugin_api_url', 'http://api.wordpress.org/plugins/info/1.0/%s.json');
Configure::write('App.plugin_app_url', 'http://www.pluginmirror.com/plugins/%s/');
Configure::write('App.plugin_git_url', 'git@github.com:wp-plugins/%s.git');
Configure::write('App.plugin_github_url', 'https://github.com/wp-plugins/%s/');
Configure::write('App.plugin_http_url', 'http://wordpress.org/plugins/%s/');
Configure::write('App.plugin_svn_url', 'https://plugins.svn.wordpress.org/%s/');
Configure::write('App.plugin_trac_url', 'http://plugins.trac.wordpress.org/browser/%s/');

Configure::write('App.profile_url', 'http://profiles.wordpress.org/%s/');

Configure::write('GitHub.org_name', 'wp-plugins');
Configure::write('GitHub.repo_description', 'WordPress.org Plugin Mirror');
Configure::write('GitHub.ssh_host', 'pluginmirror-github');
Configure::write('GitHub.user_agent', 'Plugin Mirror (http://www.pluginmirror.com/)');


Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');

Configure::write('Asset.timestamp', true);

date_default_timezone_set('UTC');


Configure::write('Security.level', 'medium');

if(defined('APP_SECURITY_SALT'))
	Configure::write('Security.salt', APP_SECURITY_SALT);

if(defined('APP_SECURITY_CIPHERSEED'))
	Configure::write('Security.cipherSeed', APP_SECURITY_CIPHERSEED);


if(defined('APP_CACHE_DISABLE'))
	Configure::write('Cache.disable', APP_CACHE_DISABLE);
else
	Configure::write('Cache.disable', true);

if(defined('APP_CACHE_CHECK'))
	Configure::write('Cache.check', APP_CACHE_CHECK);
else
	Configure::write('Cache.check', false);


Configure::write('Cache.engine', 'File');
if (extension_loaded('apc') && function_exists('apc_dec') &&
    (php_sapi_name() !== 'cli' || ini_get('apc.enable_cli')))
{
	Configure::write('Cache.engine', 'Apc');
}

Configure::write('Cache.duration', '+100 days');
if (Configure::read('debug') >= 1)
{
	Configure::write('Cache.duration', '+60 minutes');
}

Configure::write('Cache.prefix', 'app_');

Cache::config('_cake_core_', array(
	'engine' => Configure::read('Cache.engine'),
	'prefix' => Configure::read('Cache.prefix') . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => (Configure::read('Cache.engine') === 'File'),
	'duration' => Configure::read('Cache.duration')
));

Cache::config('_cake_model_', array(
	'engine' => Configure::read('Cache.engine'),
	'prefix' => Configure::read('Cache.prefix') . 'cake_model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => (Configure::read('Cache.engine') === 'File'),
	'duration' => Configure::read('Cache.duration')
));


Configure::write('Session', array(
	'defaults' => 'database',
	'handler' => array(
		'engine' => 'ComboSession',
		'model' => 'Session',
		'cache' => 'session'
	),
	'cookie' => 'session',
	'timeout' => 1440 // 1 day
));