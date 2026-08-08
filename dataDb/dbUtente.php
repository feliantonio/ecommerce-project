<?php
require_once(Common::$PathModels . "utente.php");

class DbUtente extends DbRepository
{

    public function ConvalidaPassword(string $password): bool
    {
        return true;
        $regex="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$";
        return preg_match($regex,$password);
    }

    public function ConvalidaMail(string $mail): bool
    {
        return true;
        $regex = "^[a-zA-Z0-9._]+@[a-zA-Z]+\.[a-zA-Z]{2,}$";
        $ok = preg_match($regex,$mail);
        if($ok==1){
            return true;
        } else {return false;}
    }

    public function ConvalidaNC() : bool
    {
        return true;
    }

    public function mailExist(string $mail) : bool 
    {
        $sql = "SELECT * FROM utenti WHERE mail = :mail;";

        $param['mail'] = $mail;
        $rows = $this -> Select($sql,$param);
        if($rows !== null && count($rows) > 0)
        {
            return true;
        }
        return false;
    }

    public function Register(Utente $utente, string $password) : bool
    {
        try
        {
            //secondo controllo su server di mail e password
            if (!$this->ConvalidaMail($utente->GetMail())) {
                throw new Exception("Errore: La Mail non soddisfa i criteri rihiesti");
            }

            if($this -> mailExist($utente->GetMail())){
                throw new Exception('Errore: Mail già esistente');
            }

            if (!$this->ConvalidaPassword($password)) {
                throw new Exception("Errore: La Password non soddisfa i criteri rihiesti");
            }

            $sql = "INSERT INTO utenti ( Nome, Mail, Telefono, TipoUtente, Password )
                    VALUES (:nome, :mail, :telefono,'U', :password);";

            $parameters['nome'] = $utente->GetNome();
            $parameters['mail'] = $utente->GetMail();
            $parameters['telefono'] = $utente->GetTelefono();
            $parameters['password'] = password_hash($password,PASSWORD_DEFAULT);

            if(parent :: Insert($sql,$parameters))
            {
                $this->Login($utente->GetMail(),$password);
                return true;
            }
            return false;
        }
        catch (Exception $e) 
        {
            die("Registrazione non riuscita [REGISTER]: " . $e -> getMessage());
        }

    }

    public function Login(string $mail,string $password) : bool
    {
        $this -> Logout();
        try {
            #controlli javascript, se scrivi già una mail che non può esserci non fai i controlli con php
            if(!$this -> convalidaMail($mail))
            {
                throw new Exception("Errore: La Mail non soddisfa i criteri rihiesti");
            }
            if (!$this->ConvalidaPassword($password)) 
            {
                throw new Exception("Errore: La Password non soddisfa i criteri rihiesti");
            }

            $sql = "SELECT TipoUtente,Nome,Password,UtenteId FROM utenti WHERE mail=:mail;";
            $param['mail']=$mail;
            $rows = parent::Select($sql,$param)[0];
            if($rows == 0 || $rows == null)
            {
                return false;
            }

            if(password_verify($password,$rows['Password']))
            {
                Common::SetUserId($rows['UtenteId']);
                Common::SetUserName($rows['Nome']);
                Common::SetUserType($rows['TipoUtente']);
                Common::SetUserMail($mail);
                return true;
            }
            return false;

        }
        catch(Exception $e) {
            die("ERROR[Login]: " . $e -> getMessage());
        }
    }

    public function Logout()
    {
        Common::Logout();
    }

    public function UpdateUser(Utente $u) : bool
    {
        try 
        {
            // controlli server su "tutti" i campi , DA CONCLUDERE
            if (!$this->convalidaMail($u->GetMail())) {
                throw new Exception("Errore: La Mail non soddisfa i criteri rihiesti");
            }
            elseif (!$this->ConvalidaNC($u -> GetCognome())) {
                throw new Exception("Errore: Il Cognome non soddisfa i criteri rihiesti");
            }
            elseif (!$this->ConvalidaNC($u->GetNome())) {
                throw new Exception("Errore: Il Nome non soddisfa i criteri rihiesti");
            }

            $sql = "UPDATE utenti SET nome = :nome           ,
                                      cognome = :cognome     , 
                                      mail = :mail           ,
                                      telefono = :telefono  ,
                                      indirizzo = :indirizzo ,
                                      provincia = :provincia ,
                                      cap = :cap
                    WHERE utenteId = :id;";
            //parametri update
            $params['nome'] = $u -> GetNome();
            $params['cognome'] = $u -> GetCognome();
            $params['mail'] = $u -> GetMail();
            $params['telefono'] = $u -> GetTelefono();
            $params['indirizzo'] = $u -> GetIndirizzo();
            $params['provincia'] = $u -> GetProvincia();
            $params['cap'] = $u -> GetCap();
            (string)$params['id'] = $u -> GetUtenteId();

            parent::Update($sql,$params);
            return true;
        }
        catch (Exception $e) 
        {
            die("ERROR[UpdateUser]: " . $e->getMessage());
        }
    }

    public function UpdatePsw(string $mail , string $old , string $new) : bool 
    { 
        try {
            $sql = "SELECT password from utenti where mail = :mail;";
            $param['mail'] = $mail;
            $row = parent::Select($sql,$param)[0];
            if(password_verify($old,$row['password']))
            {
                $sql = "UPDATE utenti SET password = :new WHERE mail = :mail;";
                $param['new'] = password_hash($new,PASSWORD_DEFAULT);
                parent::Update($sql , $param);
                if($this -> Login($mail,$new))
                {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            die("ERROR: impossibile aggiornare la password[UpdatePsw]" . $e->getMessage());
        }
    }

    // uso esclusivo del pannello admin (vedi nota in dbProdotto.php sul
    // controllo del ruolo anche a livello dati) - serve a risolvere il nome
    // dell'acquirente nella pagina "tutti gli ordini".
    public function GetUtenteById(int $id) : Utente
    {
        try
        {
            if (Common::GetUserType() !== "A") {
                throw new Exception("Accesso negato: operazione riservata agli amministratori");
            }

            $sql = "SELECT * FROM utenti WHERE UtenteId = :id;";
            $param['id'] = $id;
            $rows = parent::Select($sql, $param);
            if ($rows == null || count($rows) == 0)
            {
                throw new Exception("Utente non trovato (ID: $id)");
            }
            $row = $rows[0];

            $u = new Utente;
            $u -> SetUtenteId($row['UtenteId']);
            $u -> SetNome($row['Nome']);
            $u -> SetCognome($row['Cognome']);
            $u -> SetMail($row['Mail']);
            $u -> SetTelefono($row['Telefono']);
            $u -> SetTipoUtente($row['TipoUtente']);
            $u -> SetIndirizzo($row['Indirizzo']);
            $u -> SetProvincia($row['Provincia']);
            $u -> SetCap($row['Cap']);
            return $u;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetUtenteById]");
        }
    }
}
