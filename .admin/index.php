<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
Common::RequireAdmin();
require_once(Common::$PathClassiMaster . "masterAdmin.php");
require_once(Common::$PathDataDb . "dbProdotto.php");
require_once(Common::$PathDataDb . "dbCategoria.php");
require_once(Common::$PathDataDb . "dbProduttore.php");
require_once(Common::$PathDataDb . "dbOrdine.php");
$mst = new masterAdmin();
$mst->SetContenuto("index_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftAdmin.php");
$mst->SetTemplate(Common::$PathTemplates . "templateAreaRis.php");
$title = "Pannello Admin";
require_once($mst->GetTemplate());
