<?php

$dbCat = new DbCategoria();

$editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$c = new Categoria();
if ($editId !== null) {
    $c = $dbCat->GetCategoriaById($editId);
}

$errore = '';

if (isset($_POST['salvaCategoria'])) {
    $nome = trim($_POST['nome'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $idPost = isset($_POST['categoriaId']) && (int)$_POST['categoriaId'] > 0 ? (int)$_POST['categoriaId'] : null;

    $c->SetCategoriaId($idPost ?? -1);
    $c->SetNome($nome);
    $c->SetDescrizione($descrizione);

    if ($nome === '') {
        $errore = "Il nome della categoria è obbligatorio.";
    } else {
        if ($idPost !== null) {
            $dbCat->UpdateCategoria($c);
        } else {
            $dbCat->InsertCategoria($c);
        }
        header("Location: categorie.php");
        exit;
    }
}

?>

<?php if ($errore !== '') { ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errore) ?></div>
<?php } ?>

<form class="row g-3" method="post" action="">
    <input type="hidden" name="categoriaId" value="<?= $c->GetCategoriaId() ?>">

    <div class="col-md-6">
        <label for="nome" class="form-label">Nome<span class="controlloObbligatorio">*</span></label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($c->GetNome()) ?>">
    </div>

    <div class="col-12">
        <label for="descrizione" class="form-label">Descrizione</label>
        <textarea class="form-control" id="descrizione" name="descrizione" rows="3"><?= htmlspecialchars($c->GetDescrizione()) ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" name="salvaCategoria">Salva</button>
        <a class="btn btn-outline-secondary" href="categorie.php">Annulla</a>
    </div>
</form>
