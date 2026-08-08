<?php

$dbProd = new DbProdotto();
$dbCat = new DbCategoria();
$dbProdut = new DbProduttore();

if (isset($_POST['toggleAttivo'])) {
    $idToggle = (int)$_POST['toggleAttivo'];
    $nuovoStato = (int)$_POST['nuovoStato'];
    $dbProd->SetAttivo($idToggle, $nuovoStato);
}

$prodotti = $dbProd->GetAllProdotti();

$categorieMap = [];
foreach ($dbCat->GetAllCategorie() as $cat) {
    $categorieMap[$cat->GetCategoriaId()] = $cat->GetNome();
}
$produttoriMap = [];
foreach ($dbProdut->GetAllProduttori() as $prod) {
    $produttoriMap[$prod->GetProduttoreId()] = $prod->GetNome();
}

?>

<div class="mb-3">
    <a class="btn btn-primary" href="prodottoForm.php">+ Nuovo prodotto</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prodotto</th>
                <th>Categoria</th>
                <th>Produttore</th>
                <th>Prezzo</th>
                <th>Stato</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($prodotti) == 0) { ?>
                <tr><td colspan="7" class="text-muted">Nessun prodotto presente.</td></tr>
            <?php } ?>
            <?php foreach ($prodotti as $p) { ?>
                <tr>
                    <td><?= $p->GetProdottoId() ?></td>
                    <td><?= htmlspecialchars($p->GetProdotto()) ?></td>
                    <td><?= htmlspecialchars($categorieMap[$p->GetCategoriaId()] ?? '-') ?></td>
                    <td><?= htmlspecialchars($produttoriMap[$p->GetProduttoreId()] ?? '-') ?></td>
                    <td><?= number_format($p->GetPrezzo(), 2, ',', '.') ?>€</td>
                    <td>
                        <?php if ($p->GetAttivo() == 1) { ?>
                            <span class="badge bg-success">Attivo</span>
                        <?php } else { ?>
                            <span class="badge bg-secondary">Disattivato</span>
                        <?php } ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-outline-primary btn-sm" href="prodottoForm.php?id=<?= $p->GetProdottoId() ?>">Modifica</a>
                        <form class="d-inline" method="post" action="">
                            <input type="hidden" name="toggleAttivo" value="<?= $p->GetProdottoId() ?>">
                            <input type="hidden" name="nuovoStato" value="<?= $p->GetAttivo() == 1 ? 0 : 1 ?>">
                            <button type="submit" class="btn btn-sm <?= $p->GetAttivo() == 1 ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                <?= $p->GetAttivo() == 1 ? 'Disattiva' : 'Riattiva' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
