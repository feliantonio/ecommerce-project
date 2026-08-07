<?php

class Categoria
{
    private int $categoriaId = -1;
    private string $nome = '';
    private string $descrizione = '';

    // GET

    public function GetCategoriaId(): int
    {
        return $this->categoriaId;
    }

    public function GetNome(): string
    {
        return $this->nome;
    }

    public function GetDescrizione(): string
    {
        return $this->descrizione;
    }

    // SET

    public function SetCategoriaId(int $value)
    {
        $this->categoriaId = $value;
    }

    public function SetNome(string $value)
    {
        $this->nome = $value;
    }

    public function SetDescrizione(string $value)
    {
        $this->descrizione = $value;
    }

    static public function SetCategoria(
        Categoria $c,
        ?int $categoriaId,
        ?string $nome,
        ?string $descrizione
    )
    {
        $categoriaId != null ? $c->SetCategoriaId($categoriaId) : $c->SetCategoriaId(-1);
        $nome != null ? $c->SetNome($nome) : $c->SetNome("");
        $descrizione != null ? $c->SetDescrizione($descrizione) : $c->SetDescrizione("");
    }
}

?>
