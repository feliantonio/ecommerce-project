<?php $currentPage = basename($_SERVER['SCRIPT_NAME']); ?>
<nav class="nav flex-column">
    <a class="nav-link <?= $currentPage == 'infoPersonali.php' ? 'fw-bold' : '' ?>" href="../.areaRiservata/infoPersonali.php">Modifica informazioni personali</a>
    <a class="nav-link <?= $currentPage == 'modPassword.php' ? 'fw-bold' : '' ?>" href="../.areaRiservata/modPassword.php">Modifica password</a>
    <a class="nav-link <?= $currentPage == 'ordini.php' ? 'fw-bold' : '' ?>" href="../.areaRiservata/ordini.php">I miei ordini</a>
</nav>