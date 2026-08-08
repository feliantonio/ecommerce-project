<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
Common::RequireAdmin();
require_once(Common::$PathClassiMaster . "masterAdmin.php");
require_once(Common::$PathDataDb . "dbProduttore.php");
$mst = new masterAdmin();
$mst->SetContenuto("produttori_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftAdmin.php");
$mst->SetTemplate(Common::$PathTemplates . "templateAreaRis.php");
$title = "Gestione Produttori";
require_once($mst->GetTemplate());
