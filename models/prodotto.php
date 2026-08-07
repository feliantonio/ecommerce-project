<?php

class Prodotto
{
    private int $prodottoId = -1;
    private string $prodotto = '';
    private string $descrizione = '';
    private string $um = '';
    private float $prezzo = -1;
    private int $produttoreId = -1;
    private int $categoriaId = -1;
    private string $nomeImmagine = '';
    private int $attivo = 1;

    // GET

    public function GetProdottoId() : int
    {
        return $this -> prodottoId;
    }

    public function GetProdotto(): string
    {
        return $this->prodotto;
    }

    public function GetDescrizione(): string
    {
        return $this->descrizione;
    }

    public function GetUm(): string
    {
        return $this->um;
    }

    public function GetPrezzo(): float
    {
        return $this->prezzo;
    }

    public function GetProduttoreId(): int
    {
        return $this->produttoreId;
    }

    public function GetCategoriaId(): int
    {
        return $this->categoriaId;
    }

    public function GetNomeImmagine(): string
    {
        return $this->nomeImmagine;
    }

    public function GetAttivo(): int
    {
        return $this->attivo;
    }

    // SET 

    public function SetProdottoId(int $value)
    {
        $this -> prodottoId = $value;
    }

    public function SetProdotto(string $value)
    {
        $this->prodotto = $value;
    }

    public function SetDescrizione(string $value)
    {
        $this->descrizione = $value;
    }

    public function SetUm(string $value)
    {
        $this->um = $value;
    }

    public function SetPrezzo(float $value)
    {
        $this->prezzo = $value;
    }

    public function SetProduttoreId(int $value)
    {
        $this->produttoreId = $value;
    }

    public function SetCategoriaId(int $value)
    {
        $this->categoriaId = $value;
    }

    public function SetNomeImmagine(string $value)
    {
        $this->nomeImmagine = $value;
    }

    public function SetAttivo(int $value)
    {
        $this->attivo = $value;
    }

    static public function SetProd(
        Prodotto $p,
        ?int $prodottoId,
        ?string $prodotto,
        ?string $descrizione,
        ?string $um,
        ?float $prezzo,
        ?int $produttoreId,
        ?int $categoriaId,
        ?string $nomeImmagine,
        ?string $attivo
    ) 
    {
        $prodottoId != null ? $p->SetProdottoId($prodottoId) : $p->SetProdottoId(-1);
        $prodotto != null ? $p->SetProdotto($prodotto) : $p->SetProdotto("");
        $descrizione != null ? $p->SetDescrizione($descrizione) : $p->SetDescrizione("");
        $um != null ? $p->SetUm($um) : $p->SetUm("");
        $prezzo != null ? $p->SetPrezzo($prezzo) : $p->SetPrezzo(-1);
        $produttoreId != null ? $p->SetProduttoreId($produttoreId) : $p->SetProduttoreId(-1);
        $categoriaId != null ? $p->SetCategoriaId($categoriaId) : $p->SetCategoriaId(-1);
        $nomeImmagine != null ? $p->SetNomeImmagine($nomeImmagine) : $p->SetNomeImmagine("");
        $attivo != null ? $p->SetAttivo($attivo) : $p->SetAttivo("");
    }
}


?>