<?php

$dbProd = new DbProdotto();
$dbCat = new DbCategoria();
$dbProdut = new DbProduttore();
$categorie = $dbCat->GetAllCategorie();
$produttori = $dbProdut->GetAllProduttori();

$editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$p = new Prodotto();
if ($editId !== null) {
    $p = $dbProd->GetProdById($editId);
}

$errore = '';

if (isset($_POST['salvaProdotto'])) {
    $nome = trim($_POST['prodotto'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $um = trim($_POST['um'] ?? '');
    $prezzo = $_POST['prezzo'] ?? '';
    $categoriaId = (int)($_POST['categoriaId'] ?? 0);
    $produttoreId = (int)($_POST['produttoreId'] ?? 0);
    $nomeImmagine = trim($_POST['nomeImmagine'] ?? '');
    $attivo = isset($_POST['attivo']) ? 1 : 0;
    $idPost = isset($_POST['prodottoId']) && (int)$_POST['prodottoId'] > 0 ? (int)$_POST['prodottoId'] : null;

    // ripopola il form con quanto inserito, che la validazione passi o no
    $p->SetProdottoId($idPost ?? -1);
    $p->SetProdotto($nome);
    $p->SetDescrizione($descrizione);
    $p->SetUm($um);
    $p->SetPrezzo(is_numeric($prezzo) ? (float)$prezzo : -1);
    $p->SetCategoriaId($categoriaId);
    $p->SetProduttoreId($produttoreId);
    $p->SetNomeImmagine($nomeImmagine);
    $p->SetAttivo($attivo);

    if ($nome === '' || !is_numeric($prezzo) || (float)$prezzo <= 0 || $categoriaId <= 0 || $produttoreId <= 0) {
        $errore = "Compila tutti i campi obbligatori: nome, prezzo (maggiore di zero), categoria e produttore.";
    } else {
        if ($idPost !== null) {
            $dbProd->UpdateProdotto($p);
        } else {
            $dbProd->InsertProdotto($p);
        }
        header("Location: prodotti.php");
        exit;
    }
}

?>

<?php if ($errore !== '') { ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errore) ?></div>
<?php } ?>

<form class="row g-3" method="post" action="">
    <input type="hidden" name="prodottoId" value="<?= $p->GetProdottoId() ?>">

    <div class="col-md-6">
        <label for="prodotto" class="form-label">Nome<span class="controlloObbligatorio">*</span></label>
        <input type="text" class="form-control" id="prodotto" name="prodotto" value="<?= htmlspecialchars($p->GetProdotto()) ?>">
    </div>

    <div class="col-md-3">
        <label for="prezzo" class="form-label">Prezzo (€)<span class="controlloObbligatorio">*</span></label>
        <input type="text" class="form-control" id="prezzo" name="prezzo" value="<?= $p->GetPrezzo() >= 0 ? $p->GetPrezzo() : '' ?>">
    </div>

    <div class="col-md-3">
        <label for="um" class="form-label">Unità di misura</label>
        <input type="text" class="form-control" id="um" name="um" value="<?= htmlspecialchars($p->GetUm()) ?>">
    </div>

    <div class="col-12">
        <label for="descrizione" class="form-label">Descrizione</label>
        <textarea class="form-control" id="descrizione" name="descrizione" rows="3"><?= htmlspecialchars($p->GetDescrizione()) ?></textarea>
    </div>

    <div class="col-md-6">
        <label for="categoriaId" class="form-label">Categoria<span class="controlloObbligatorio">*</span></label>
        <select class="form-select" id="categoriaId" name="categoriaId">
            <option value="">-- Seleziona --</option>
            <?php foreach ($categorie as $cat) { ?>
                <option value="<?= $cat->GetCategoriaId() ?>" <?= $p->GetCategoriaId() == $cat->GetCategoriaId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat->GetNome()) ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="produttoreId" class="form-label">Produttore<span class="controlloObbligatorio">*</span></label>
        <select class="form-select" id="produttoreId" name="produttoreId">
            <option value="">-- Seleziona --</option>
            <?php foreach ($produttori as $prod) { ?>
                <option value="<?= $prod->GetProduttoreId() ?>" <?= $p->GetProduttoreId() == $prod->GetProduttoreId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prod->GetNome()) ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-6">
        <label for="nomeImmagine" class="form-label">Nome immagine (file già presente in /images, senza estensione)</label>
        <input type="text" class="form-control" id="nomeImmagine" name="nomeImmagine" value="<?= htmlspecialchars($p->GetNomeImmagine()) ?>">
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="attivo" name="attivo" <?= $p->GetAttivo() == 1 ? 'checked' : '' ?>>
            <label class="form-check-label" for="attivo">Attivo</label>
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" name="salvaProdotto">Salva</button>
        <a class="btn btn-outline-secondary" href="prodotti.php">Annulla</a>
    </div>
</form>
