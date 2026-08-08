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

    // metodi ad uso esclusivo del pannello admin - ognuno controlla il ruolo
    // in autonomia (oltre alla guardia gia' presente su ogni controller
    // .admin/*.php), cosi' il livello dati resta protetto anche se in futuro
    // venissero chiamati da un punto che dimentica di richiamare la guardia.

    public function GetAllProdotti() : array
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "SELECT * from prodotti ORDER BY ProdottoID;";
            $rows = parent::Select($sql);

            $prodotti = [];
            foreach ($rows as $row) {
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
                $prodotti[] = $p;
            }
            return $prodotti;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetAllProdotti]");
        }
    }

    public function InsertProdotto(Prodotto $p) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "INSERT INTO prodotti (Prodotto, Descrizione, Um, Prezzo, ProduttoreId, CategoriaId, NomeImmagine, Attivo)
                    VALUES (:prodotto, :descrizione, :um, :prezzo, :produttoreId, :categoriaId, :nomeImmagine, :attivo);";

            $param['prodotto'] = $p -> GetProdotto();
            $param['descrizione'] = $p -> GetDescrizione();
            $param['um'] = $p -> GetUm();
            $param['prezzo'] = $p -> GetPrezzo();
            $param['produttoreId'] = $p -> GetProduttoreId();
            $param['categoriaId'] = $p -> GetCategoriaId();
            $param['nomeImmagine'] = $p -> GetNomeImmagine();
            $param['attivo'] = $p -> GetAttivo();

            return parent::Insert($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[InsertProdotto]");
        }
    }

    public function UpdateProdotto(Prodotto $p) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "UPDATE prodotti SET Prodotto = :prodotto,
                                        Descrizione = :descrizione,
                                        Um = :um,
                                        Prezzo = :prezzo,
                                        ProduttoreId = :produttoreId,
                                        CategoriaId = :categoriaId,
                                        NomeImmagine = :nomeImmagine,
                                        Attivo = :attivo
                    WHERE ProdottoID = :id;";

            $param['prodotto'] = $p -> GetProdotto();
            $param['descrizione'] = $p -> GetDescrizione();
            $param['um'] = $p -> GetUm();
            $param['prezzo'] = $p -> GetPrezzo();
            $param['produttoreId'] = $p -> GetProduttoreId();
            $param['categoriaId'] = $p -> GetCategoriaId();
            $param['nomeImmagine'] = $p -> GetNomeImmagine();
            $param['attivo'] = $p -> GetAttivo();
            $param['id'] = $p -> GetProdottoId();

            return parent::Update($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[UpdateProdotto]");
        }
    }

    public function SetAttivo(int $id, int $attivo) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "UPDATE prodotti SET Attivo = :attivo WHERE ProdottoID = :id;";
            $param['attivo'] = $attivo;
            $param['id'] = $id;

            return parent::Update($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[SetAttivo]");
        }
    }
}

?>