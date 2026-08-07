<?php

class Ordine
{
    private int $ordineId = -1;
    private int $utenteId = -1;
    private string $dataOrdine = '';
    private float $imponibile = -1;
    private float $iva = -1;
    private float $totale = -1;

    // GET

    public function GetOrdineId(): int
    {
        return $this->ordineId;
    }

    public function GetUtenteId(): int
    {
        return $this->utenteId;
    }

    public function GetDataOrdine(): string
    {
        return $this->dataOrdine;
    }

    public function GetImponibile(): float
    {
        return $this->imponibile;
    }

    public function GetIva(): float
    {
        return $this->iva;
    }

    public function GetTotale(): float
    {
        return $this->totale;
    }

    // SET

    public function SetOrdineId(int $value)
    {
        $this->ordineId = $value;
    }

    public function SetUtenteId(int $value)
    {
        $this->utenteId = $value;
    }

    public function SetDataOrdine(string $value)
    {
        $this->dataOrdine = $value;
    }

    public function SetImponibile(float $value)
    {
        $this->imponibile = $value;
    }

    public function SetIva(float $value)
    {
        $this->iva = $value;
    }

    public function SetTotale(float $value)
    {
        $this->totale = $value;
    }

    static public function SetOrdine(
        Ordine $o,
        ?int $ordineId,
        ?int $utenteId,
        ?string $dataOrdine,
        ?float $imponibile,
        ?float $iva,
        ?float $totale
    )
    {
        $ordineId != null ? $o->SetOrdineId($ordineId) : $o->SetOrdineId(-1);
        $utenteId != null ? $o->SetUtenteId($utenteId) : $o->SetUtenteId(-1);
        $dataOrdine != null ? $o->SetDataOrdine($dataOrdine) : $o->SetDataOrdine("");
        $imponibile != null ? $o->SetImponibile($imponibile) : $o->SetImponibile(-1);
        $iva != null ? $o->SetIva($iva) : $o->SetIva(-1);
        $totale != null ? $o->SetTotale($totale) : $o->SetTotale(-1);
    }
}

?>
