<?php

require_once(Common::$PathModels . "categoria.php");

class DbCategoria extends DbRepository
{
    public function GetCategoriaById(int $id) : Categoria
    {
        try
        {
            $sql = "SELECT * from categorie WHERE CategoriaId = :id";
            $param['id'] = $id;

            $rows = parent::Select($sql,$param);
            if ($rows == null || count($rows) == 0)
            {
                throw new Exception("Categoria non trovata (ID: $id)");
            }
            $row = $rows[0];

            $c = new Categoria;
            $c -> SetCategoriaId($row['CategoriaId']);
            $c -> SetNome($row['Nome']);
            $c -> SetDescrizione($row['Descrizione']);
            return $c;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetCategoriaById]");
        }
    }

    public function GetAllCategorie() : array
    {
        try
        {
            $sql = "SELECT * from categorie ORDER BY Nome";

            $rows = parent::Select($sql);
            $categorie = [];
            foreach ($rows as $row)
            {
                $c = new Categoria;
                $c -> SetCategoriaId($row['CategoriaId']);
                $c -> SetNome($row['Nome']);
                $c -> SetDescrizione($row['Descrizione']);
                $categorie[] = $c;
            }
            return $categorie;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetAllCategorie]");
        }
    }

    // metodi ad uso esclusivo del pannello admin - vedi nota in dbProdotto.php
    // sulla scelta di controllare il ruolo anche qui, oltre alla guardia sul
    // controller .admin/*.php.

    public function InsertCategoria(Categoria $c) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "INSERT INTO categorie (Nome, Descrizione) VALUES (:nome, :descrizione);";
            $param['nome'] = $c -> GetNome();
            $param['descrizione'] = $c -> GetDescrizione();

            return parent::Insert($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[InsertCategoria]");
        }
    }

    public function UpdateCategoria(Categoria $c) : bool
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "UPDATE categorie SET Nome = :nome, Descrizione = :descrizione WHERE CategoriaId = :id;";
            $param['nome'] = $c -> GetNome();
            $param['descrizione'] = $c -> GetDescrizione();
            $param['id'] = $c -> GetCategoriaId();

            return parent::Update($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[UpdateCategoria]");
        }
    }
}

?>
