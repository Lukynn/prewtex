<?php

/**
 * @author Radek Hübner <radek@hurass.cz>
 * @copyright (c) 2016, Radek Hübner
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/DatabasePanel.php';

use Tracy\Debugger,
	Prewtex\DatabasePanel;

$mode = isset($approvedIPs) && $approvedIPs ? $approvedIPs : Debugger::DETECT;
$logDir = __DIR__ . "/../log";

Debugger::enable($mode, $logDir, isset($emails) ? $emails : NULL);

$dbDiagnostics = new DatabasePanel();
