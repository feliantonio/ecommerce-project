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

    // metodi ad uso esclusivo del pannello admin - vedi nota in dbProdotto.php
    // sulla scelta di controllare il ruolo anche qui, oltre alla guardia sul
    // controller .admin/*.php.

    public function InsertProduttore(Produttore $p) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "INSERT INTO produttori (Nome, NazioneOrigine) VALUES (:nome, :nazioneOrigine);";
            $param['nome'] = $p -> GetNome();
            $param['nazioneOrigine'] = $p -> GetNazioneOrigine();

            return parent::Insert($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[InsertProduttore]");
        }
    }

    public function UpdateProduttore(Produttore $p) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "UPDATE produttori SET Nome = :nome, NazioneOrigine = :nazioneOrigine WHERE ProduttoreId = :id;";
            $param['nome'] = $p -> GetNome();
            $param['nazioneOrigine'] = $p -> GetNazioneOrigine();
            $param['id'] = $p -> GetProduttoreId();

            return parent::Update($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[UpdateProduttore]");
        }
    }
}

?>
