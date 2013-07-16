<?php
/**
 * The Stats controller handles all actions related to statistic pages.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * Stats Controller
 *
 * @package app.Controller
 *
 * @property Stat $Stat
 */
class StatsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Paginator' => array(
			'maxLimit' => 2016, // 14 days of stats
			'limit' => 288, // 2 days of stats
			'fields' => array(
				'total', 'cloned', 'cloning', 'refreshing',
				'removed', 'updating', 'created_iso8601'
			),
			'order' => array('Stat.created' => 'DESC')
		),
	);

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		if(in_array($this->RequestHandler->ext, array('json', 'xml'))) {
			$this->set('stats', $this->Paginator->paginate());
		} else {
			$this->set('title_for_layout', __('Status'));
			$this->set('latest_stat', $this->Stat->find('first', array(
				'order' => 'Stat.created DESC',
			)));
		}
	}

}
