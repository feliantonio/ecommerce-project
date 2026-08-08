<?php

require_once("IDbManager.php");

class DbManager implements IDBManager
{
    public ?PDO $con = null;

    public function OpenConnection() : ?PDO
    {
        $this -> con = null;
        try 
        {
            $c = $_SESSION["DbType"] . ":host=" . $_SESSION["DbHost"] . ";dbname=" . $_SESSION["DbName"];
            $con = new PDO($c, $_SESSION["DbUserName"], $_SESSION["DbUserPassword"]);
            $this -> con = $con;
        }
        catch (Exception $e)
        {
            die("Connessione al database non riuscita: " . $e->getMessage());
        }
        return $con;
    }
    
    public function CloseConnection(): void
    {
        $this -> con = null;
    }

    public function IsConnected() : bool 
    {
        if($this -> con == null) return false;
        return true;
    }

    public function Delete(string $sql, ?array $ar = null): bool
    {
        //aperetura e test connessione
        $con = $this -> OpenConnection();
        if(!$this -> IsConnected()) return false;

        try 
        {
            //apertura transaction
            $con -> beginTransaction();
            //se $ar parametri è null exec normale di un delete
            if($ar == null || count($ar) == 0)
            {
                $con->exec($sql);
            }
            else
            {
                //se $ar parametri non è null prepare e execute e commit
                $st = $con->prepare($sql);
                $st->execute($ar);

            }
            $con->commit();

            return true;
        } 
        catch (Exception $e) 
        {
            // se c'è stato un errore in transaction 
            if ($con != null && $con->inTransaction()) 
            {
                $con->rollback();
                die("Cancellazione non riuscita [DELETE]: " . $e->getMessage());
            }
            // errore prima di transaction
            die(" " . $e->getMessage());
        } 
        finally 
        {
            $this->CloseConnection();
        }
        
    }

    public function Insert(string $sql, ?array $ar = null): bool
    {
        //aperetura e test connessione
        $con = $this->OpenConnection();
        if (!$this->IsConnected()) return False;

        try {
            //apertura transaction
            $con->beginTransaction();
            //se $ar parametri è null exec normale di un delete
            if ($ar == null || count($ar) == 0) 
            {
                $con->exec($sql);
            } 
            else 
            {
                //se $ar parametri non è null prepare e execute e commit
                $st = $con->prepare($sql);
                $st->execute($ar);

            }
            $con->commit();

            return true;
        } 
        catch (Exception $e) 
        {
            // se c'è stato un errore in transaction 
            if ($con != null && $con->inTransaction()) 
            {
                $con->rollback();
                die("Inserimento nuovo utente non riuscita [INSERT]: " . $e->getMessage());
            }
            // errore prima di transaction
            die(" " . $e->getMessage());
        } 
        finally 
        {
            $this->CloseConnection();
        }
    }

    public function Update(string $sql, ?array $ar = null): bool
    {
        //aperetura e test connessione
        $con = $this->OpenConnection();
        if (!$this->IsConnected()) return False;

        try {
            //apertura transaction
            $con->beginTransaction();
            //se $ar parametri è null exec normale di un delete
            if ($ar == null || count($ar) == 0) {
                $con->exec($sql);
            } 
            else 
            {
                //se $ar parametri non è null prepare e execute e commit
                $st = $con->prepare($sql);
                $st->execute($ar);

            }
            $con->commit();

            return true;
        } 
        catch (Exception $e) 
        {
            // se c'è stato un errore in transaction 
            if ($con != null && $con->inTransaction()) {
                $con->rollback();
                die("Aggiornamento non riuscito [UPDATE]: " . $e->getMessage());
            }
            // errore prima di transaction
            die(" " . $e->getMessage());
        } 
        finally 
        {
            $this->CloseConnection();
        }
    }

    public function Select(string $sql, ?array $ar = null): ?array
    {
        // inizializzazione array di ritorno
        $rows = null;

        //aperetura e test connessione
        $con = $this->OpenConnection();
        if (!$this->IsConnected()) return $rows;

        try {
            //se $ar parametri è null exec normale di un delete
            if ($ar == null || count($ar) == 0) {
                $st = $con->query($sql);
            }
            else
            {
                //se $ar parametri non è null prepare e execute
                $st = $con->prepare($sql);
                $st->execute($ar);
            }
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } 
        catch (Exception $e) 
        {
            die("Select fallita [SELECT]: " . $e->getMessage());
        } 
        finally 
        {
            $this->CloseConnection();
        }
    }
}
