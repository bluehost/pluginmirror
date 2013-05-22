<?php
/**
 * Application bootstrap file.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Config
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

// Configures all composer packages for autoload.
App::build(array('Vendor' => array(ROOT . DS . 'vendor' . DS)));
App::import('Vendor', 'autoload');

CakePlugin::loadAll();


Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));


App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));


Cache::config('default', array(
	'engine' => Configure::read('Cache.engine'),
	'prefix' => Configure::read('Cache.prefix') . 'default_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => (Configure::read('Cache.engine') === 'File'),
	'duration' => Configure::read('Cache.duration')
));

Cache::config('session', array(
	'engine' => Configure::read('Cache.engine'),
	'prefix' => Configure::read('Cache.prefix') . 'session_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => (Configure::read('Cache.engine') === 'File'),
	'duration' => '+12 hours'
));
