<?php

class  Utente
{
    private int $utenteId = -1;
    public string $tipoUtente = "";
    public string $cognome = "";
    public string $nome = "";
    public string $mail = "";
    public string $password = "";
    public string $indirizzo = "";
    public string $cap = "";
    public string $provincia = "";
    public string $telefono = "";

    //UtenteId get and set functions
    public function SetUtenteId(int $val)
    {
        $this->utenteId = $val;
    }

    public function GetUtenteId(): int
    {
        return $this->utenteId;
    }

    //tipoUtente get and set functions
    public function SetTipoUtente(string $val): void
    {
        $this->tipoUtente = $val;
    }

    public function GetTipoUtente(): string
    {
        return $this->tipoUtente;
    }

    //Cognome get and set functions
    public function SetCognome(string $val): void
    {
        $this->cognome = $val;
    }

    public function GetCognome(): string
    {
        return $this->cognome;
    }

    //Nome get and set functions
    public function SetNome(string $val): void
    {
        $this->nome = $val;
    }

    public function GetNome(): string
    {
        return $this->nome;
    }

    //Mail get and set functions
    public function SetMail(string $val): void
    {
        $this->mail = $val;
    }

    public function GetMail(): string
    {
        return $this->mail;
    }

    //Password get and set functions
    public function SetPassword(string $val): void
    {
        $this->password = $val;
    }

    public function GetPassword(): string
    {
        return $this->password;
    }

    //Indirizzo get and set functions
    public function SetIndirizzo(string $val): void
    {
        $this->indirizzo = $val;
    }

    public function GetIndirizzo(): string
    {
        return $this->indirizzo;
    }

    //Cap get and set functions
    public function SetCap(string $val): void
    {
        $this->cap = $val;
    }

    public function GetCap(): string
    {
        return $this->cap;
    }

    //Provincia get and set functions
    public function SetProvincia(string $val): void
    {
        $this->provincia = $val;
    }

    public function GetProvincia(): string
    {
        return $this->provincia;
    }

    //Telefono get and set functions
    public function SetTelefono(string $val): void
    {
        $this->telefono = $val;
    }

    public function GetTelefono(): string
    {
        return $this->telefono;
    }

    static public function SetUtente(Utente $u , int $utenteId , ?string $nome , ?string $cognome , 
                                ?string $mail , ?string $tel , ?string $prov , 
                                ?string $indir , ?string $cap)
    {
        $utenteId != null ? $u->SetUtenteId($utenteId) : $u->SetUtenteId(-1);
        $nome != null ? $u->SetNome($nome) : $u->SetNome("");
        $cognome != null ? $u->SetCognome($cognome) : $u->SetCognome("");
        $mail != null ? $u->SetMail($mail) : $u->SetMail("");
        $tel != null ? $u->SetTelefono($tel) : $u->SetTelefono("");
        $prov != null ? $u->SetProvincia($prov) : $u->SetProvincia("");
        $indir != null ? $u->SetIndirizzo($indir) : $u->SetIndirizzo("");
        $cap != null ? $u->SetCap($cap) : $u->SetCap("");
    }
}
