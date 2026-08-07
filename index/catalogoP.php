<?php
// echo "search: " . $search . " sort_order: " . $sort_order . " sort_field: " . $sort_field;
// echo "<br>";
// echo "params: record per pagina: " . $catal -> GetRecXPage();
// echo "<br>";
// echo "params: record record totali: " . $catal->GetTotRec();
// echo "<br>";
// echo "params: pagine totali: " . $catal->GetTotPages();
// echo "<br>";
// echo "params: pagina corrente: " . $catal->GetCurrentPage();
// var_dump($ar);

?>
<div class="row">

    <a href="" role="button" style="text-decoration: none">
        <?php

        $catal->PageParams($search, $categoriaId, $produttoreId);
        $offset = ($catal->GetCurrentPage() - 1) * $catal->GetRecXPage();
        $ar = $catal->SelectCatalogo($search, $sort_field, $sort_order, $catal->GetRecXPage(), $offset, $categoriaId, $produttoreId);
        if (!isset($_GET['id'])) {
            $catal->DisplayCatalogo($ar, Common::GetUserType());
        } else {
            echo $_GET['id'];
        }
        ?>
    </a>
</div>