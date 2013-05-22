<?php
/**
 * CakePHP routes configuration file.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Config
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

Router::connect('/contributors/:name',
                array('controller' => 'contributors', 'action' => 'view'),
                array('pass' => array('name')));

Router::connect('/descriptions/:id',
                array('controller' => 'descriptions', 'action' => 'view'),
                array('pass' => array('id')));

Router::connect('/plugins/:slug',
                array('controller' => 'plugins', 'action' => 'view'),
                array('pass' => array('slug')));
Router::connect('/plugins/:slug/:action',
                array('controller' => 'plugins'),
                array('pass' => array('slug')));

Router::connect('/plugins_states/:id',
                array('controller' => 'plugins_states', 'action' => 'view'),
                array('pass' => array('id')));

Router::connect('/tags/:name',
                array('controller' => 'tags', 'action' => 'view'),
                array('pass' => array('name')));

Router::connect('/states/:name',
                array('controller' => 'states', 'action' => 'view'),
                array('pass' => array('name')));

Router::connect('/status', array('controller' => 'stats', 'action' => 'index'));

Router::connect('/:controller', array('action' => 'index'));
Router::connect('/:controller/:action/*');

Router::parseExtensions('json', 'xml');

CakePlugin::routes();