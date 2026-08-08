<?php $currentPage = basename($_SERVER['SCRIPT_NAME']); ?>
<nav class="nav flex-column">
    <a class="nav-link <?= $currentPage == 'index.php' ? 'fw-bold' : '' ?>" href="../.admin/index.php">Home</a>
    <a class="nav-link <?= $currentPage == 'prodotti.php' || $currentPage == 'prodottoForm.php' ? 'fw-bold' : '' ?>" href="../.admin/prodotti.php">Prodotti</a>
    <a class="nav-link <?= $currentPage == 'categorie.php' || $currentPage == 'categoriaForm.php' ? 'fw-bold' : '' ?>" href="../.admin/categorie.php">Categorie</a>
    <a class="nav-link <?= $currentPage == 'produttori.php' || $currentPage == 'produttoreForm.php' ? 'fw-bold' : '' ?>" href="../.admin/produttori.php">Produttori</a>
    <a class="nav-link <?= $currentPage == 'ordini.php' ? 'fw-bold' : '' ?>" href="../.admin/ordini.php">Tutti gli ordini</a>
</nav>
