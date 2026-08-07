<?php

require_once(Common::$PathModels . "ordine.php");
require_once(Common::$PathModels . "ordineDettaglio.php");

class DbOrdine extends DbRepository
{
    // Crea un ordine a partire dalle righe correnti del carrello (Carrello[]).
    // Ritorna l'OrdineId appena creato.
    //
    // NB: DbManager::Insert() ritorna solo bool, non l'id generato
    // (lastInsertId()) — cambiare quell'interfaccia condivisa impatterebbe
    // tutti i repository. Per restare coerenti con il livello di rigore del
    // resto del progetto, dopo l'insert si ri-seleziona l'ultimo ordine
    // dell'utente per recuperarne l'id: accettabile per un progetto
    // demo/didattico a basa concorrenza, non robusto in caso di richieste
    // concorrenti dello stesso utente.
    public function CreaOrdine(int $utenteId, array $righeCarrello) : int
    {
        try
        {
            $imponibile = 0.0;
            foreach ($righeCarrello as $riga) {
                $imponibile += $riga->GetQta() * $riga->GetPrezzo();
            }
            $iva = $imponibile * 0.22;
            $totale = $imponibile + $iva;

            $sql = "INSERT INTO ordini (UtenteId, Imponibile, Iva, Totale) VALUES (:utenteId, :imponibile, :iva, :totale);";
            $param = [
                'utenteId' => $utenteId,
                'imponibile' => $imponibile,
                'iva' => $iva,
                'totale' => $totale,
            ];
            parent::Insert($sql, $param);

            $sql = "SELECT OrdineId FROM ordini WHERE UtenteId = :utenteId ORDER BY OrdineId DESC LIMIT 1;";
            $row = parent::Select($sql, ['utenteId' => $utenteId])[0];
            $ordineId = (int)$row['OrdineId'];

            foreach ($righeCarrello as $riga) {
                $sqlDett = "INSERT INTO ordine_dettagli (OrdineId, ProdottoId, Qta, PrezzoUnitario) VALUES (:ordineId, :prodottoId, :qta, :prezzo);";
                parent::Insert($sqlDett, [
                    'ordineId' => $ordineId,
                    'prodottoId' => $riga->GetProdottoId(),
                    'qta' => $riga->GetQta(),
                    'prezzo' => $riga->GetPrezzo(),
                ]);
            }

            return $ordineId;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[CreaOrdine]");
        }
    }

    public function GetOrdiniByUtente(int $utenteId) : array
    {
        try
        {
            $sql = "SELECT * FROM ordini WHERE UtenteId = :id ORDER BY DataOrdine DESC;";
            $rows = parent::Select($sql, ['id' => $utenteId]) ?? [];
            $ordini = [];
            foreach ($rows as $row) {
                $o = new Ordine;
                $o -> SetOrdineId($row['OrdineId']);
                $o -> SetUtenteId($row['UtenteId']);
                $o -> SetDataOrdine($row['DataOrdine']);
                $o -> SetImponibile($row['Imponibile']);
                $o -> SetIva($row['Iva']);
                $o -> SetTotale($row['Totale']);
                $ordini[] = $o;
            }
            return $ordini;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetOrdiniByUtente]");
        }
    }

    public function GetDettagliOrdine(int $ordineId) : array
    {
        try
        {
            $sql = "SELECT * FROM ordine_dettagli WHERE OrdineId = :id;";
            $rows = parent::Select($sql, ['id' => $ordineId]) ?? [];
            $dettagli = [];
            foreach ($rows as $row) {
                $d = new OrdineDettaglio;
                $d -> SetOrdineDettaglioId($row['OrdineDettaglioId']);
                $d -> SetOrdineId($row['OrdineId']);
                $d -> SetProdottoId($row['ProdottoId']);
                $d -> SetQta($row['Qta']);
                $d -> SetPrezzoUnitario($row['PrezzoUnitario']);
                $dettagli[] = $d;
            }
            return $dettagli;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[GetDettagliOrdine]");
        }
    }
}

?>
