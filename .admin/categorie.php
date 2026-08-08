<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
Common::RequireAdmin();
require_once(Common::$PathClassiMaster . "masterAdmin.php");
require_once(Common::$PathDataDb . "dbCategoria.php");
$mst = new masterAdmin();
$mst->SetContenuto("categorie_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftAdmin.php");
$mst->SetTemplate(Common::$PathTemplates . "templateAreaRis.php");
$title = "Gestione Categorie";
require_once($mst->GetTemplate());
