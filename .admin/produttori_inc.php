<?php

$dbProdut = new DbProduttore();
// GetAllProduttori() ordina per nome (serve così al filtro del catalogo
// pubblico) - qui in admin vogliamo l'ordine di inserimento (per ID).
$produttori = $dbProdut->GetAllProduttori();
usort($produttori, fn($a, $b) => $a->GetProduttoreId() <=> $b->GetProduttoreId());

?>

<div class="mb-3">
    <a class="btn btn-primary" href="produttoreForm.php">+ Nuovo produttore</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Nazione di origine</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($produttori) == 0) { ?>
                <tr><td colspan="4" class="text-muted">Nessun produttore presente.</td></tr>
            <?php } ?>
            <?php foreach ($produttori as $prod) { ?>
                <tr>
                    <td><?= $prod->GetProduttoreId() ?></td>
                    <td><?= htmlspecialchars($prod->GetNome()) ?></td>
                    <td><?= htmlspecialchars($prod->GetNazioneOrigine()) ?></td>
                    <td class="text-end">
                        <a class="btn btn-outline-primary btn-sm" href="produttoreForm.php?id=<?= $prod->GetProduttoreId() ?>">Modifica</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
