<?php

require_once(Common::$PathDataDb . "dbCarrello.php");

if (isset($_POST['logout'])) {
    Common::Logout();
}
$catal = new DbCatalogo();

$sort_field = isset($_GET['sort']) ? ($_GET['sort']) : 'ProdottoID';
$sort_order = isset($_GET['order']) ? ($_GET['order']) : 'ASC';
$catal->SetCurrentPage(isset($_GET['page']) ? (int)($_GET['page']) : 1);
// echo $catal->GetCurrentPage();
$search = isset($_GET['search']) ? ($_GET['search']) : '';
$categoriaId = isset($_GET['categoria']) && $_GET['categoria'] !== '' ? (int)$_GET['categoria'] : null;
$produttoreId = isset($_GET['produttore']) && $_GET['produttore'] !== '' ? (int)$_GET['produttore'] : null;

// numero di articoli nel carrello, per il badge sull'icona (solo utenti loggati)
$cartCount = 0;
if ($_SESSION['UserType'] != 'G') {
    $dbCartCount = new DbCarrello();
    $cartCount = $dbCartCount->CountItems(Common::GetUserId());
}

?>

<div class="row">
    <div class="col-2 text-center">
        <img src="../images/logoShopping.png" alt="logo" class="rounded">
    </div>

    <div class="col-6">

        <form class="d-flex row" action="" method="get">

            <div class="col-4">
                <div class = "input-group">
                    <select class="form-select" id="sort" name="sort" aria-label="Default select example">
                        <option value="ProdottoId" <?= ($sort_field == 'ProdottoID' ? 'selected' : "") ?>>ID</option>
                        <option value="Prodotto" <?= ($sort_field == 'Prodotto' ? 'selected' : "") ?>>Prodotto</option>
                        <option value="Prezzo" <?= ($sort_field == 'Prezzo' ? 'selected' : "") ?>>Prezzo</option>
                    </select>
                    
                    <select class="form-select" name="order" id="order" aria-label="Default select example">
                        <option value="ASC" <?= ($sort_order == 'ASC' ? 'selected' : "") ?>>ASC</option>
                        <option value="DESC" <?= ($sort_order == 'DESC' ? 'selected' : "") ?>>DESC</option>
                    </select>
                </div>
            </div>

            <div class="col-8">
                <div class = "input-group">
                    <input class="form-control" type="text" id="search" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES) ?>" placeholder="Search" aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>

    </div>

    <div class="col-2 text-center">

        <?php
        if ($_SESSION['UserType'] == "G") {
            echo "<div>";
            echo "<a href='.." . DIRECTORY_SEPARATOR . ".ur" . DIRECTORY_SEPARATOR . "utenteRegistrazione.php'>Registrati</a>";
            echo "</div>";
            echo "<div>";
            echo "<a href='.." . DIRECTORY_SEPARATOR . ".login" . DIRECTORY_SEPARATOR . "login.php'>Accedi</a>";
            echo "</div>";
        } else {
            echo "<div class = 'd-grid gap-1'>";
            echo "<div class = 'shadow bg-primary rounded text-white'>";
            echo "Ciao " . $_SESSION['UserName'] . "!";
            echo "</div>";
            echo "<form action = '' method = 'post'>";
            echo "<button type='submit' name = 'logout' class='btn btn-primary btn-sm'>Logout</button>";
            echo "</form>";
            echo "</div>";
        }

        ?>
    </div>

    <div class="col-1 text-center">
        <a href="../.areaRiservata/infoPersonali.php" class="<?= $_SESSION['UserType'] == 'G' ? 'nav-link disabled' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="mx-auto bi bi-person" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
            </svg>
        </a>
    </div>

    <div class="col-1 text-center position-relative">
        <a href="../.carrello/carrello.php" class="<?= $_SESSION['UserType'] == 'G' ? 'nav-link disabled' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="mt-1 bi bi-bag-check" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
            </svg>
            <?php if ($cartCount > 0) { ?>
                <span class="badge bg-danger rounded-pill position-absolute top-0 end-0"><?= $cartCount ?></span>
            <?php } ?>
        </a>
    </div>
</div>

<div class=row>

</div>