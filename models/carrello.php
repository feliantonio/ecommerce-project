<?php

class Carrello
{
    private int $carrelloId = -1;
    private int $utenteId = -1;
    private int $prodottoId = -1;
    private int $qta = -1;
    private float $prezzo = -1;

    // GET

    public function GetCarrelloId() : int 
    {
        return $this -> carrelloId;    
    }

    public function GetUtenteId(): int
    {
        return $this->utenteId;
    }

    public function GetProdottoId(): int
    {
        return $this->prodottoId;
    }

    public function GetQta(): int
    {
        return $this->qta;
    }

    public function GetPrezzo(): float
    {
        return $this->prezzo;
    }

    // SET

    public function SetCarrelloId(int $value)
    {
        $this -> carrelloId = $value;
    }

    public function SetUtenteId(int $value)
    {
        $this->utenteId = $value;
    }

    public function SetProdottoId(int $value)
    {
        $this->prodottoId = $value;
    }

    public function SetQta(int $value)
    {
        $this->qta = $value;
    }
    
    public function SetPrezzo(int $value)
    {
        $this->prezzo = $value;
    }

    static public function SetCarrello(Carrello $c , int $carrelloId , int $utenteId , int $prodottoId , int $qta , float $prezzo)
    {
        $c -> SetCarrelloId($carrelloId);
        $c -> SetUtenteId($utenteId);
        $c -> SetProdottoId($prodottoId);
        $c -> SetQta($qta);
        $c -> SetPrezzo($prezzo);

    }
}

?>