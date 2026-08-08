<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
Common::RequireAdmin();
require_once(Common::$PathClassiMaster . "masterAdmin.php");
require_once(Common::$PathDataDb . "dbOrdine.php");
require_once(Common::$PathDataDb . "dbProdotto.php");
require_once(Common::$PathDataDb . "dbUtente.php");
$mst = new masterAdmin();
$mst->SetContenuto("ordini_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftAdmin.php");
$mst->SetTemplate(Common::$PathTemplates . "templateAreaRis.php");
$title = "Tutti gli ordini";
require_once($mst->GetTemplate());
