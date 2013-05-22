<?php
/**
 * Database-backed session datasource, optimized through the persistent cache.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model.Datasource.Session
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('DatabaseSession', 'Model/Datasource/Session');

/**
 * Database-backed session datasource, optimized through the persistent cache.
 *
 * @package app.Model.Datasource.Session
 */
class ComboSession extends DatabaseSession
	implements CakeSessionHandlerInterface
{

	/**
	 * @var mixed
	 */
	public $cacheKey;

	/**
	 *
	 */
	public function __construct()
	{
		$this->cacheKey = Configure::read('Session.handler.cache');
		parent::__construct();
	}

	/**
	 * @param int|string $id
	 *
	 * @return mixed
	 */
	public function read($id)
	{
		$result = Cache::read($id, $this->cacheKey);
		if ($result) {
			return $result;
		}

		return parent::read($id);
	}

	/**
	 * @param int   $id
	 * @param mixed $data
	 * @return bool
	 */
	public function write($id, $data)
	{
		Cache::write($id, $data, $this->cacheKey);

		return parent::write($id, $data);
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function destroy($id)
	{
		Cache::delete($id, $this->cacheKey);

		return parent::destroy($id);
	}

	/**
	 * @param null $expires
	 * @return bool
	 */
	public function gc($expires = null)
	{
		return Cache::gc($this->cacheKey) && parent::gc($expires);
	}

}