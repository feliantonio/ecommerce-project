<?php

require_once(".." . DIRECTORY_SEPARATOR . "common.php");
require_once(Common::$PathClassiMaster . "masterCarrello.php");
require_once(Common::$PathDataDb . "dbProdotto.php");
require_once(Common::$PathDataDb . "dbCarrello.php");
require_once(Common::$PathDataDb . "dbOrdine.php");
require_once(Common::$PathModels . "carrello.php");
require_once(Common::$PathModels . "prodotto.php");
$mst = new masterCarrrello();
$mst->SetContenuto("carrello_inc.php");
$mst->SetAsideLeft(Common::$PathInclude . "AsideLeftCR.php");
$mst->SetAsideRight(Common::$PathInclude . "AsideRightCR.php");
$mst->SetTemplate(Common::$PathTemplates . "templateCarrello.php");
$title = "Il tuo carrello";

// gestione azioni sul carrello (eliminazione, pagamento) prima del render
if ($_SERVER['REQUEST_METHOD'] == "POST" && Common::GetUserType() != "G") {
    $dbCart = new DbCarrello();
    $userId = Common::GetUserId();

    if (isset($_POST['deleteAllCart'])) {
        $dbCart->DeleteAll($userId);
        header("Location: carrello.php");
        exit;
    }

    if (isset($_POST['deleteSelected']) && isset($_POST['selected']) && is_array($_POST['selected'])) {
        $dbCart->DeleteSelected($userId, $_POST['selected']);
        header("Location: carrello.php");
        exit;
    }

    if (isset($_POST['paga'])) {
        $righeCarrello = $dbCart->SelectCarrelli($userId);
        if (count($righeCarrello) > 0) {
            $dbOrd = new DbOrdine();
            $dbOrd->CreaOrdine($userId, $righeCarrello);
            $dbCart->DeleteAll($userId);
        }
        header("Location: ../index/index.php");
        exit;
    }
}

require_once($mst->GetTemplate());

?>