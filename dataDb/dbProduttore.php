<?php

require_once(Common::$PathModels . "produttore.php");

class DbProduttore extends DbRepository
{
    public function GetProduttoreById(int $id) : Produttore
    {
        try
        {
            $sql = "SELECT * from produttori WHERE ProduttoreId = :id";
            $param['id'] = $id;

            $rows = parent::Select($sql,$param);
            if ($rows == null || count($rows) == 0)
            {
                throw new Exception("Produttore non trovato (ID: $id)");
            }
            $row = $rows[0];

            $p = new Produttore;
            $p -> SetProduttoreId($row['ProduttoreId']);
            $p -> SetNome($row['Nome']);
            $p -> SetNazioneOrigine($row['NazioneOrigine']);
            return $p;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetProduttoreById]");
        }
    }

    public function GetAllProduttori() : array
    {
        try
        {
            $sql = "SELECT * from produttori ORDER BY Nome";

            $rows = parent::Select($sql);
            $produttori = [];
            foreach ($rows as $row)
            {
                $p = new Produttore;
                $p -> SetProduttoreId($row['ProduttoreId']);
                $p -> SetNome($row['Nome']);
                $p -> SetNazioneOrigine($row['NazioneOrigine']);
                $produttori[] = $p;
            }
            return $produttori;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetAllProduttori]");
        }
    }
}

?>
