<?php

$dbProd = new DbProdotto();
$dbCat = new DbCategoria();
$dbProdut = new DbProduttore();
$dbOrd = new DbOrdine();

$numProdotti = count($dbProd->GetAllProdotti());
$numCategorie = count($dbCat->GetAllCategorie());
$numProduttori = count($dbProdut->GetAllProduttori());
$numOrdini = count($dbOrd->GetAllOrdini());

?>

<div class="row g-3">
    <div class="col-sm-6 col-lg-3">
        <a class="card text-decoration-none h-100" href="prodotti.php">
            <div class="card-body text-center">
                <div class="display-6"><?= $numProdotti ?></div>
                <div class="card-text">Prodotti</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a class="card text-decoration-none h-100" href="categorie.php">
            <div class="card-body text-center">
                <div class="display-6"><?= $numCategorie ?></div>
                <div class="card-text">Categorie</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a class="card text-decoration-none h-100" href="produttori.php">
            <div class="card-body text-center">
                <div class="display-6"><?= $numProduttori ?></div>
                <div class="card-text">Produttori</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a class="card text-decoration-none h-100" href="ordini.php">
            <div class="card-body text-center">
                <div class="display-6"><?= $numOrdini ?></div>
                <div class="card-text">Ordini</div>
            </div>
        </a>
    </div>
</div>
