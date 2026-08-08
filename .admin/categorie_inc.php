<?php

$dbCat = new DbCategoria();
// GetAllCategorie() ordina per nome (serve così al filtro del catalogo
// pubblico) - qui in admin vogliamo l'ordine di inserimento (per ID).
$categorie = $dbCat->GetAllCategorie();
usort($categorie, fn($a, $b) => $a->GetCategoriaId() <=> $b->GetCategoriaId());

?>

<div class="mb-3">
    <a class="btn btn-primary" href="categoriaForm.php">+ Nuova categoria</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrizione</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categorie) == 0) { ?>
                <tr><td colspan="4" class="text-muted">Nessuna categoria presente.</td></tr>
            <?php } ?>
            <?php foreach ($categorie as $cat) { ?>
                <tr>
                    <td><?= $cat->GetCategoriaId() ?></td>
                    <td><?= htmlspecialchars($cat->GetNome()) ?></td>
                    <td><?= htmlspecialchars($cat->GetDescrizione()) ?></td>
                    <td class="text-end">
                        <a class="btn btn-outline-primary btn-sm" href="categoriaForm.php?id=<?= $cat->GetCategoriaId() ?>">Modifica</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
