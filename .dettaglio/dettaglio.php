<?php
require_once(".." . DIRECTORY_SEPARATOR . "common.php");
require_once(Common::$PathClassiMaster."masterDettaglio.php");
require_once(Common::$PathModels. "prodotto.php");
require_once(Common::$PathModels. "categoria.php");
require_once(Common::$PathModels. "produttore.php");
require_once(Common::$PathModels. "carrello.php");
require_once(Common::$PathDataDb . "dbProdotto.php");
require_once(Common::$PathDataDb . "dbCategoria.php");
require_once(Common::$PathDataDb . "dbProduttore.php");
require_once(Common::$PathDataDb . "dbCarrello.php");
$mst = new MasterDettaglio();
$mst->SetContenuto("dettaglio_inc.php");
$mst->SetTemplate(Common::$PathTemplates."templateHome.php");

// id prodotto da querystring, validato prima di interrogare il db
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("ERROR: parametro 'id' mancante o non valido [dettaglio]");
}

//creazione prodotto
$dbP = new DbProdotto();
$p = $dbP -> GetProdById($id);

// aggiunta al carrello (stesso pattern usato in index/index.php)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['idC']) && Common::GetUserType() != "G") {
        $qta = isset($_POST['qta']) ? max(1, (int)$_POST['qta']) : 1;
        $dbCart = new DbCarrello();
        $cart = new Carrello();
        Carrello::SetCarrello($cart, -1, Common::GetUserId(), $p -> GetProdottoId(), $qta, $p -> GetPrezzo());
        if ($dbCart -> AggiungiProd($cart)) {
            header("Location: ../.carrello/carrello.php");
            exit;
        }
    }
}

// produttore e categoria collegati al prodotto
$dbPr = new DbProduttore();
$produttore = $dbPr -> GetProduttoreById($p -> GetProduttoreId());
$dbC = new DbCategoria();
$categoria = $dbC -> GetCategoriaById($p -> GetCategoriaId());

// imposta titolo col nome del prodotto
$title = $p -> GetProdotto();
require_once($mst -> GetTemplate());
?>
