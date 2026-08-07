<?php

class Produttore
{
    private int $produttoreId = -1;
    private string $nome = '';
    private string $nazioneOrigine = '';

    // GET

    public function GetProduttoreId(): int
    {
        return $this->produttoreId;
    }

    public function GetNome(): string
    {
        return $this->nome;
    }

    public function GetNazioneOrigine(): string
    {
        return $this->nazioneOrigine;
    }

    // SET

    public function SetProduttoreId(int $value)
    {
        $this->produttoreId = $value;
    }

    public function SetNome(string $value)
    {
        $this->nome = $value;
    }

    public function SetNazioneOrigine(string $value)
    {
        $this->nazioneOrigine = $value;
    }

    static public function SetProduttore(
        Produttore $p,
        ?int $produttoreId,
        ?string $nome,
        ?string $nazioneOrigine
    )
    {
        $produttoreId != null ? $p->SetProduttoreId($produttoreId) : $p->SetProduttoreId(-1);
        $nome != null ? $p->SetNome($nome) : $p->SetNome("");
        $nazioneOrigine != null ? $p->SetNazioneOrigine($nazioneOrigine) : $p->SetNazioneOrigine("");
    }
}

?>
