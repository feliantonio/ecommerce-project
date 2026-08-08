<?php

$dbProdut = new DbProduttore();

$editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$p = new Produttore();
if ($editId !== null) {
    $p = $dbProdut->GetProduttoreById($editId);
}

$errore = '';

if (isset($_POST['salvaProduttore'])) {
    $nome = trim($_POST['nome'] ?? '');
    $nazioneOrigine = trim($_POST['nazioneOrigine'] ?? '');
    $idPost = isset($_POST['produttoreId']) && (int)$_POST['produttoreId'] > 0 ? (int)$_POST['produttoreId'] : null;

    $p->SetProduttoreId($idPost ?? -1);
    $p->SetNome($nome);
    $p->SetNazioneOrigine($nazioneOrigine);

    if ($nome === '') {
        $errore = "Il nome del produttore è obbligatorio.";
    } else {
        if ($idPost !== null) {
            $dbProdut->UpdateProduttore($p);
        } else {
            $dbProdut->InsertProduttore($p);
        }
        header("Location: produttori.php");
        exit;
    }
}

?>

<?php if ($errore !== '') { ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errore) ?></div>
<?php } ?>

<form class="row g-3" method="post" action="">
    <input type="hidden" name="produttoreId" value="<?= $p->GetProduttoreId() ?>">

    <div class="col-md-6">
        <label for="nome" class="form-label">Nome<span class="controlloObbligatorio">*</span></label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($p->GetNome()) ?>">
    </div>

    <div class="col-md-6">
        <label for="nazioneOrigine" class="form-label">Nazione di origine</label>
        <input type="text" class="form-control" id="nazioneOrigine" name="nazioneOrigine" value="<?= htmlspecialchars($p->GetNazioneOrigine()) ?>">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" name="salvaProduttore">Salva</button>
        <a class="btn btn-outline-secondary" href="produttori.php">Annulla</a>
    </div>
</form>
