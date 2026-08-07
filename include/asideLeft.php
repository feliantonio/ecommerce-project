<?php

$dbCatFilter = new DbCategoria();
$dbProdFilter = new DbProduttore();
$categorieFiltro = $dbCatFilter->GetAllCategorie();
$produttoriFiltro = $dbProdFilter->GetAllProduttori();

?>

<h6>Categorie</h6>
<nav class="nav flex-column mb-3">
    <a class="nav-link <?= $categoriaId === null ? 'fw-bold' : '' ?>" href="<?= Common::BuildQuery(['categoria' => null, 'page' => 1]) ?>">Tutte le categorie</a>
    <?php foreach ($categorieFiltro as $cat) { ?>
        <a class="nav-link <?= $categoriaId === $cat->GetCategoriaId() ? 'fw-bold' : '' ?>" href="<?= Common::BuildQuery(['categoria' => $cat->GetCategoriaId(), 'page' => 1]) ?>">
            <?= htmlspecialchars($cat->GetNome()) ?>
        </a>
    <?php } ?>
</nav>

<h6>Produttori</h6>
<nav class="nav flex-column">
    <a class="nav-link <?= $produttoreId === null ? 'fw-bold' : '' ?>" href="<?= Common::BuildQuery(['produttore' => null, 'page' => 1]) ?>">Tutti i produttori</a>
    <?php foreach ($produttoriFiltro as $prod) { ?>
        <a class="nav-link <?= $produttoreId === $prod->GetProduttoreId() ? 'fw-bold' : '' ?>" href="<?= Common::BuildQuery(['produttore' => $prod->GetProduttoreId(), 'page' => 1]) ?>">
            <?= htmlspecialchars($prod->GetNome()) ?>
        </a>
    <?php } ?>
</nav>
