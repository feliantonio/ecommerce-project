<?php

require_once(Common::$PathModels . "prodotto.php");

class DbProdotto extends DbRepository
{
    public function GetProdById(int $id) : Prodotto
    {
        try 
        {
            $sql = "SELECT * from prodotti WHERE ProdottoID = :id";
            $param['id'] = $id;

            $rows = parent::Select($sql,$param);
            if ($rows == null || count($rows) == 0)
            {
                throw new Exception("Prodotto non trovato (ID: $id)");
            }
            $row = $rows[0];

            $p = new Prodotto;
            $p -> SetProdottoId($row['ProdottoID']);
            $p -> SetProdotto($row['Prodotto']);
            $p -> SetDescrizione($row['Descrizione']);
            $p -> SetUm($row['Um']);
            $p -> SetPrezzo($row['Prezzo']);
            $p -> SetProduttoreId($row['ProduttoreId']);
            $p -> SetCategoriaId($row['CategoriaId']);
            $p -> SetNomeImmagine($row['NomeImmagine']);
            $p -> SetAttivo($row['Attivo']);
            return $p;
        } 
        catch (Exception $e) 
        {
            die("ERROR: " . $e -> getMessage() . "[GetProdById]");
        }
    }
}

?>