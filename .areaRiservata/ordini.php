<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
require_once(Common::$PathClassiMaster . "masterPersonale.php");
require_once(Common::$PathDataDb . "dbOrdine.php");
require_once(Common::$PathDataDb . "dbProdotto.php");
$mst = new masterPersonale();
$mst->SetContenuto("ordini_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftAR.php");
$mst->SetTemplate(Common::$PathTemplates . "templateAreaRis.php");
$title = "I miei ordini";
require_once($mst->GetTemplate());
?>
