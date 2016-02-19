<?php

/**
 * @author Radek Hübner <radek@hurass.cz>
 * @copyright (c) 2016, Radek Hübner
 */

namespace Prewtex;

use Tracy;

/**
 * Class DatabasePanel
 * @package Prewtex
 */
class DatabasePanel implements Tracy\IBarPanel
{
	/**
	 * @var string
	 */
	public $host;

	/**
	 * @var string
	 */
	public $dbName;

	/**
	 * @var array
	 */
	public $queries = array();

	/**
	 * @var int
	 */
	public $totalTime = 0;

	/**
	 * @return string
	 */
	public function getTab()
	{
		return "
			<span title=\"Database\">
				<svg viewBox=\"0 0 2048 2048\"><path fill=\"#aaa\" d=\"M1024 896q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0 768q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-384q237 0 443-43t325-127v170q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-170q119 84 325 127t443 43zm0-1152q208 0 385 34.5t280 93.5 103 128v128q0 69-103 128t-280 93.5-385 34.5-385-34.5-280-93.5-103-128v-128q0-69 103-128t280-93.5 385-34.5z\"></path></svg>
				<span class=\"tracy-label\">". count($this->queries) ." queries ". ($this->totalTime ? "/ ". sprintf("%0.1f", $this->totalTime * 1000) . " ms" : "") . " </span>
			</span>
		";
    }

	/**
	 * @return string
	 */
	public function getPanel()
	{
		if (empty($this->queries)) {
			return "";
		}

		return
			$this->renderStyles() .
			sprintf("<h1>Queries: %s, %s, host: %s/%s</h1>",
				count($this->queries),
				($this->totalTime ? "time: " . sprintf("%0.3f", $this->totalTime * 1000) . " ms" : ""),
				$this->host,
				$this->dbName
			) .
			"<div class=\"nette-inner tracy-inner DatabasePanel\">" .
				implode("<br>", array_filter(array(
					$this->renderPanelQueries()
				))) .
			"</div>";
    }

	/**
	 * @return string
	 */
	private function renderStyles()
	{
		return "<style>
			#nette-debug td.DatabasePanel-sql { background: white !important }
			#nette-debug .DatabasePanel-source { color: #125EAE !important; }
			#nette-debug DatabasePanel tr table { margin: 8px 0; max-height: 150px; overflow:auto }
			#tracy-debug td.DatabasePanel-sql { background: white !important}
			#tracy-debug .DatabasePanel-source { color: #125EAE !important; cursor: pointer !important; }
			#tracy-debug DatabasePanel tr table { margin: 8px 0; max-height: 150px; overflow:auto }
		</style>";
	}

	private function renderPanelQueries()
	{
		if (empty($this->queries)) {
			return "";
		}

		$s = "";
		foreach ($this->queries as $query) {
			$s .= "
				<tr class='".(str_replace(".", "_", basename($query["source"]["file"]))).$query["source"]["line"]."' onclick='filterTracyFiles(\"".(str_replace(".", "_", basename($query["source"]["file"]))).$query["source"]["line"]."\")'>
					<td>" . sprintf("%0.3f", $query["time"] * 1000) . "</td>" .
					"<td class=\"DatabasePanel-sql\">" .
						$query["sql"] .
						($query["source"] ? ("<br>
							<span class='DatabasePanel-source'>" .
								('.../' . basename(dirname($query["source"]["file"]))) . '/<b>' . (basename($query["source"]["file"])) . "</b>:" . $query["source"]["line"] . "
							</span>"
						)  : "") . "
					</td>
				</tr>";
		}

		$script = "
			<script>
				function filterTracyFiles(sql_class) {
					if ($('.DatabasePanel').find('tr.'+sql_class).hasClass('filtered')) {
						$('.DatabasePanel').find('tr').show();
						$('.DatabasePanel').find('tr').removeClass('filtered');
					} else {
						$('.DatabasePanel').find('tr').hide();
						$('.DatabasePanel').find('tr.'+sql_class).show().addClass('filtered');
					}
				}
			</script>
		";

		return "<table><tr><th>ms</th><th>SQL Statement</th></tr>" . $s . "</table>" . $script;
	}
}