<?php
    require_once(".." . DIRECTORY_SEPARATOR . "common.php");
    require_once(Common::$PathClassiMaster."masterHome.php");
    require_once(Common::$PathModels . "prodotto.php");
    require_once(Common::$PathDataDb . "dbCatalogo.php");
    require_once(Common::$PathDataDb . "dbCarrello.php");
    require_once(Common::$PathDataDb . "dbProdotto.php");
    require_once(Common::$PathDataDb . "dbCategoria.php");
    require_once(Common::$PathDataDb . "dbProduttore.php");
    require_once(Common::$PathModels . "carrello.php");
    $mst = new MasterHome();
    $mst->SetContenuto("catalogoP.php");
    // $mst->SetContenuto("catalogoProdotti.php");
    $mst -> SetTemplate(Common::$PathTemplates."templateHome.php");
    if ($_SERVER['REQUEST_METHOD']=="POST"){
        if(isset($_POST['idC']) && Common::GetUserType() != "G")
        {
            $dbP = new DbProdotto;
            $dbC = new DbCarrello;
            $cart = new Carrello;
            $p = $dbP -> GetProdById($_POST['idC']);
            Carrello::SetCarrello($cart,$p -> GetProdottoId(), Common::GetUserId() , $p -> GetProdottoId() , 1 , $p -> GetPrezzo());

            // qta a 1 di default se aggiungi un prodotto dalla home
            if ($dbC -> AggiungiProd($cart))
            {
                // $success = "<div class='alert alert-success up col-3' role='alert'>";
                // $success .= "Prodotto Aggiungo al Carrello!";
                // $success .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                // $success .= "</div>";
                header("Location: ../index/index.php");
            }
        }
    }
    require_once($mst -> GetTemplate());
?>