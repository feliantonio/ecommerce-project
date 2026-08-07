<?php

$dbC = new DbCarrello;
$dbP = new DbProdotto;
$carrelli = $dbC->SelectCarrelli(Common::GetUserId());

?>

<form id="cartItemsForm" method="post" action="">
    <?php
    if (count($carrelli) == 0) {
        echo "<p class='text-muted'>Il carrello è vuoto.</p>";
    }
    foreach ($carrelli as $key => $value) {
        $p = $dbP->GetProdById($value->GetProdottoId());

        $dbC->DisplayCarrello($p, $value);
    }
    ?>
</form>
