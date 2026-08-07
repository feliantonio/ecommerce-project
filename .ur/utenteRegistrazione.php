<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
require_once(Common::$PathClassiMaster . "masterPersonale.php");
require_once(Common::$PathDataDb . "dbUtente.php");
require_once(Common::$PathModels . "utente.php");
$mst = new masterPersonale();
$mst->SetContenuto("utenteRegistrazione_inc.php");
$mst->SetTemplate(Common::$PathTemplates . "templatePersonale.php");
$title = "";
require_once($mst->GetTemplate());
?>