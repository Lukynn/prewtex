<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (file_exists(_PS_ROOT_DIR_ . '/config/settings.inc.php')) {
	include_once(_PS_ROOT_DIR_ . '/config/settings.inc.php');
}

use Prewtex\DatabasePanel;

/**
 * Class Db
 */
abstract class Db extends DbCore
{
	/**
	 * @var DatabasePanel
	 */
	private $dbDiagnostics;

	/**
	 * Instantiates a database connection
	 *
	 * @param string $server Server address
	 * @param string $user User login
	 * @param string $password User password
	 * @param string $database Database name
	 * @param bool $connect If false, don't connect in constructor (since 1.5.0.1)
	 */
	public function __construct($server, $user, $password, $database, $connect = true)
	{
		parent::__construct($server, $user, $password, $database, $connect);

		global $dbDiagnostics;

		if (isset($dbDiagnostics)) {
			$this->dbDiagnostics = $dbDiagnostics;
			$this->dbDiagnostics->host = $server;
			$this->dbDiagnostics->dbName = $database;
		}
	}


	/**
	 * Execute a query and get result resource
	 *
	 * @param string|DbQuery $sql
	 * @return bool|mysqli_result|PDOStatement|resource
	 * @throws PrestaShopDatabaseException
	 */
	public function query($sql)
	{
		if (!isset($this->dbDiagnostics)) {
			return parent::query($sql);
		}

		$source = NULL;
		foreach (debug_backtrace(FALSE) as $row) {
			if (isset($row['file'])) {
				if (isset($row['class']) && stripos($row['class'], '\\' . "Db") !== FALSE) {
					if (!in_array('Doctrine\Common\Persistence\Proxy', class_implements($row['class']))) {
						continue;
					} elseif (isset($row['function']) && $row['function'] === '__load') {
						continue;
					}
				} elseif (stripos($row['file'], DIRECTORY_SEPARATOR . "Db") !== FALSE) {
					continue;
				}

				$source = array(
					"file" => $row['file'],
					"line" => (int) $row['line'],
				);
				break;
			}
		}

		\Tracy\Debugger::timer('database'); // Start timer for query

		parent::query($sql);

		$this->displayError($sql);

		$this->dbDiagnostics->totalTime += $time = Tracy\Debugger::timer('database');

		$this->dbDiagnostics->queries[] = array(
			"sql" => $sql,
			"time" => $time,
			"source" => $source,
		);

		return $this->result;
	}

	/**
	 * Displays last SQL error
	 *
	 * @param string|bool $sql
	 * @throws Exception
	 */
	public function displayError($sql = false)
	{
		if (!isset($this->dbDiagnostics)) {

			parent::displayError($sql);

		} else {

			if ($this->getNumberError()) {
				throw new \Exception($this->getMsgError() . $sql);
			}
		}
	}
}
