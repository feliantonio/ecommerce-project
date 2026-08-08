<?php

$dbCatFilter = new DbCategoria();
$dbProdFilter = new DbProduttore();
$categorieFiltro = $dbCatFilter->GetAllCategorie();
$produttoriFiltro = $dbProdFilter->GetAllProduttori();
$selectedCategorie = $categoriaIds ?? [];
$selectedProduttori = $produttoreIds ?? [];

?>

<form method="get" action="">
    <input type="hidden" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES) ?>">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort_field, ENT_QUOTES) ?>">
    <input type="hidden" name="order" value="<?= htmlspecialchars($sort_order, ENT_QUOTES) ?>">
    <input type="hidden" name="page" value="1">

    <h6>Categorie</h6>
    <a class="nav-link d-block mb-2 <?= empty($selectedCategorie) ? 'fw-bold' : '' ?>" href="<?= Common::BuildQuery(['categoria' => null, 'page' => 1]) ?>">Tutte le categorie</a>
    <?php foreach ($categorieFiltro as $cat) { ?>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="categoria[]" id="cat<?= $cat->GetCategoriaId() ?>" value="<?= $cat->GetCategoriaId() ?>" <?= in_array($cat->GetCategoriaId(), $selectedCategorie, true) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cat<?= $cat->GetCategoriaId() ?>">
                <?= htmlspecialchars($cat->GetNome()) ?>
            </label>
        </div>
    <?php } ?>

    <h6 class="mt-3">Produttori</h6>
    <a class="nav-link d-block mb-2 <?= empty($selectedProduttori) ? 'fw-bold' : '' ?>" href="<?= Common::BuildQuery(['produttore' => null, 'page' => 1]) ?>">Tutti i produttori</a>
    <?php foreach ($produttoriFiltro as $prod) { ?>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="produttore[]" id="prod<?= $prod->GetProduttoreId() ?>" value="<?= $prod->GetProduttoreId() ?>" <?= in_array($prod->GetProduttoreId(), $selectedProduttori, true) ? 'checked' : '' ?>>
            <label class="form-check-label" for="prod<?= $prod->GetProduttoreId() ?>">
                <?= htmlspecialchars($prod->GetNome()) ?>
            </label>
        </div>
    <?php } ?>

    <button type="submit" class="btn btn-primary btn-sm w-100 mt-3">Filtra</button>
</form>
