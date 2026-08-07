<?php

// pannello indipendente: calcola il proprio subtotale invece di dipendere
// dall'ordine di render di carrello_inc.php.
const IVA_ALIQUOTA = 0.22;

$dbCartSummary = new DbCarrello();
$righeCarrello = $dbCartSummary->SelectCarrelli(Common::GetUserId());

$imponibile = 0.0;
foreach ($righeCarrello as $riga) {
    $imponibile += $riga->GetQta() * $riga->GetPrezzo();
}
$iva = $imponibile * IVA_ALIQUOTA;
$totale = $imponibile + $iva;

?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Riepilogo ordine</h5>

        <div class="d-flex justify-content-between">
            <span>Subtotale</span>
            <span><?= number_format($imponibile, 2, ',', '.') ?>€</span>
        </div>
        <div class="d-flex justify-content-between text-muted">
            <span>IVA (22%)</span>
            <span><?= number_format($iva, 2, ',', '.') ?>€</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold fs-5">
            <span>Totale</span>
            <span><?= number_format($totale, 2, ',', '.') ?>€</span>
        </div>

        <form method="post" action="" class="mt-3" onsubmit="return confirm('Procedere con il pagamento (demo)?');">
            <button type="submit" name="paga" value="1" class="btn btn-success w-100" <?= count($righeCarrello) == 0 ? 'disabled' : '' ?>>
                Paga (demo)
            </button>
            <p class="text-muted small mt-2 mb-0">Pagamento simulato: nessuna transazione reale viene effettuata. L'ordine viene registrato e il carrello svuotato.</p>
        </form>
    </div>
</div>
