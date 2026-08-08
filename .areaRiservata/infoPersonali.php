<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
require_once(Common::$PathClassiMaster . "masterPersonale.php");
require_once(Common::$PathDataDb . "dbUtente.php");
require_once(Common::$PathModels . "utente.php");
$mst = new masterPersonale();
$mst->SetContenuto("infoPersonali_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftAR.php");
$mst->SetTemplate(Common::$PathTemplates . "templateAreaRis.php");
$title = "Il Mio Account";
require_once($mst->GetTemplate());
?>
