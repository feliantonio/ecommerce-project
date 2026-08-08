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
<?php

$catal->PageParams($search, $categoriaIds, $produttoreIds);
$offset = ($catal->GetCurrentPage() - 1) * $catal->GetRecXPage();
$ar = $catal->SelectCatalogo($search, $sort_field, $sort_order, $catal->GetRecXPage(), $offset, $categoriaIds, $produttoreIds);
if (!isset($_GET['id'])) {
    $catal->DisplayCatalogo($ar, Common::GetUserType());
} else {
    echo $_GET['id'];
}
?>