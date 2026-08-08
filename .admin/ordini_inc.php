<?php

$dbO = new DbOrdine();
$dbP = new DbProdotto();
$dbU = new DbUtente();
$ordini = $dbO->GetAllOrdini();

?>

<?php if (count($ordini) == 0) { ?>
    <p class="text-muted">Non è ancora stato effettuato nessun ordine.</p>
<?php } ?>

<div class="accordion" id="accordionOrdini">
    <?php foreach ($ordini as $ordine) {
        $panelId = "ordine" . $ordine->GetOrdineId();
        $dettagli = $dbO->GetDettagliOrdine($ordine->GetOrdineId());
        $cliente = $dbU->GetUtenteById($ordine->GetUtenteId());
    ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $panelId ?>">
                    Ordine #<?= $ordine->GetOrdineId() ?> — Cliente: <?= htmlspecialchars($cliente->GetNome() . " " . $cliente->GetCognome()) ?> (<?= htmlspecialchars($cliente->GetMail()) ?>)
                    — <?= htmlspecialchars($ordine->GetDataOrdine()) ?> — Totale: <?= number_format($ordine->GetTotale(), 2, ',', '.') ?>€
                </button>
            </h2>
            <div id="<?= $panelId ?>" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Prodotto</th>
                                <th>Quantità</th>
                                <th>Prezzo unitario</th>
                                <th>Totale riga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dettagli as $riga) {
                                $p = $dbP->GetProdById($riga->GetProdottoId());
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($p->GetProdotto()) ?></td>
                                    <td><?= $riga->GetQta() ?></td>
                                    <td><?= number_format($riga->GetPrezzoUnitario(), 2, ',', '.') ?>€</td>
                                    <td><?= number_format($riga->GetQta() * $riga->GetPrezzoUnitario(), 2, ',', '.') ?>€</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <div class="text-end">
                            <div>Subtotale: <?= number_format($ordine->GetImponibile(), 2, ',', '.') ?>€</div>
                            <div>IVA: <?= number_format($ordine->GetIva(), 2, ',', '.') ?>€</div>
                            <div class="fw-bold">Totale: <?= number_format($ordine->GetTotale(), 2, ',', '.') ?>€</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
