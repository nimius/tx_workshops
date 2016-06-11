<?php
namespace NIMIUS\Workshops\Persistence;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Session class.
 *
 * An abstraction layer utilizing a namespaced session
 * for temporarily persisting data.
 */
class Session implements \TYPO3\CMS\Core\SingletonInterface {
	/**
	 * @var string
	 */
	protected static $namespace = 'workshops';


	/**
	 * Class constructor.
	 */
	public function __construct() {
		@session_start();
		if (!is_array($_SESSION[self::$namespace])) {
			$_SESSION[self::$namespace] = array();
		}
	}

	/**
	 * Stores the given value.
	 *
	 * @param string $key
	 * @param mixed $valie
	 * @return void
	 */
	public function set($key, $value) {
		$_SESSION[self::$namespace][$key] = $value;
	}

	/**
	 * Retrieves values for the given key.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		return $_SESSION[self::$namespace][$key];
	}

	/**
	 * Tests if the given key has data assigned.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has($key) {
		return !is_null($_SESSION[self::$namespace][$key]);
	}

	/**
	 * Removes key from session.
	 *
	 * @param string $key
	 * @return void
	 */
	public function remove($key) {
		unset($_SESSION[self::$namespace][$key]);
	}
}