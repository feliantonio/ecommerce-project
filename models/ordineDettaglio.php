<?php

class OrdineDettaglio
{
    private int $ordineDettaglioId = -1;
    private int $ordineId = -1;
    private int $prodottoId = -1;
    private int $qta = -1;
    private float $prezzoUnitario = -1;

    // GET

    public function GetOrdineDettaglioId(): int
    {
        return $this->ordineDettaglioId;
    }

    public function GetOrdineId(): int
    {
        return $this->ordineId;
    }

    public function GetProdottoId(): int
    {
        return $this->prodottoId;
    }

    public function GetQta(): int
    {
        return $this->qta;
    }

    public function GetPrezzoUnitario(): float
    {
        return $this->prezzoUnitario;
    }

    // SET

    public function SetOrdineDettaglioId(int $value)
    {
        $this->ordineDettaglioId = $value;
    }

    public function SetOrdineId(int $value)
    {
        $this->ordineId = $value;
    }

    public function SetProdottoId(int $value)
    {
        $this->prodottoId = $value;
    }

    public function SetQta(int $value)
    {
        $this->qta = $value;
    }

    public function SetPrezzoUnitario(float $value)
    {
        $this->prezzoUnitario = $value;
    }

    static public function SetOrdineDettaglio(
        OrdineDettaglio $d,
        ?int $ordineDettaglioId,
        ?int $ordineId,
        ?int $prodottoId,
        ?int $qta,
        ?float $prezzoUnitario
    )
    {
        $ordineDettaglioId != null ? $d->SetOrdineDettaglioId($ordineDettaglioId) : $d->SetOrdineDettaglioId(-1);
        $ordineId != null ? $d->SetOrdineId($ordineId) : $d->SetOrdineId(-1);
        $prodottoId != null ? $d->SetProdottoId($prodottoId) : $d->SetProdottoId(-1);
        $qta != null ? $d->SetQta($qta) : $d->SetQta(-1);
        $prezzoUnitario != null ? $d->SetPrezzoUnitario($prezzoUnitario) : $d->SetPrezzoUnitario(-1);
    }
}

?>
