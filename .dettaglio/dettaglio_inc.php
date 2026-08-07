<div class="card mx-auto" style="max-width: 1000px;">
    <div class="row g-0">
        <div class="col-md-4">
            <img src="../images/<?= htmlspecialchars($p->GetNomeImmagine()) ?>.jpg" class="img-fluid rounded-start">
        </div>
        <div class="col-md-8 position-relative">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($p->GetProdotto()) ?></h5>
                <p class="card-text text-muted mb-1">
                    <?= htmlspecialchars($categoria->GetNome()) ?>
                    &middot;
                    <?= htmlspecialchars($produttore->GetNome()) ?> (<?= htmlspecialchars($produttore->GetNazioneOrigine()) ?>)
                </p>
                <p class="card-text"><?= htmlspecialchars($p->GetDescrizione()) ?></p>
                <p class="card-text fs-5 fw-bold"><?= number_format($p->GetPrezzo(), 2, ',', '.') ?> &euro;</p>
                <form action="" method="post" class="d-flex align-items-center gap-2">
                    <input type="number" name="qta" min="1" value="1" class="form-control" style="width: 6rem;" <?= Common::GetUserType() == 'G' ? 'disabled' : '' ?>>
                    <button type="submit" name="idC" value="<?= $p->GetProdottoId() ?>" class="btn btn-primary" <?= Common::GetUserType() == 'G' ? 'disabled' : '' ?>>
                        Aggiungi al carrello
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
