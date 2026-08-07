<?php


class DbCarrello extends DbRepository
{

    public function AggiungiProd(Carrello $c) : bool 
    {
        try 
        {
            $qEs = 0;
            $sql = "SELECT qta from carrello WHERE utenteid = :userId and articoloID = :prodId;";
            $params['userId'] = $c->GetUtenteId();
            $params['prodId'] = $c -> GetProdottoId();

            $rows = parent::Select($sql,$params);

            if ($rows != null)
            {
                foreach ($rows as $key => $value) {
                    $qEs = $value['qta'] + $c->GetQta();
                }
    
                $sql = "UPDATE carrello SET qta = $qEs WHERE utenteid = :userId and articoloID = :prodId;";
                parent::Update($sql,$params);
            }

            if ($qEs == 0)
            {
                $sql = "INSERT INTO carrello (UtenteId,ArticoloId,Qta,Prezzo) 
                    VALUES(" . $c->GetUtenteId() . " , " . $c->GetProdottoId() . " , " . $c->GetQta() . " , " . $c->GetPrezzo() . ")";

                parent::Insert($sql);
            }
            return true;
        } 
        catch (Exception $e) 
        {
            die("ERROR: " . $e -> getMessage() . "[AggiungiProd]");
        }
    }

    public function SelectCarrelli(int $utenteId) : ?array {
        try
        {
            $sql = "SELECT * from carrello WHERE utenteid = :id;";
            $param['id'] = $utenteId;
            $rows = parent::Select($sql,$param) ?? [];
            $carrelli = [];
            foreach ($rows as $key => $value) {
                $cart = new Carrello;
                Carrello::SetCarrello($cart, $value['CarrelloId'] , $value['UtenteId'] , $value['ArticoloId'] , $value['Qta'] , $value['Prezzo']);
                $carrelli[] = $cart;
            }
            return $carrelli;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e->getMessage() . "[SelectCarrello]");
        }
    }

    // numero totale di articoli (somma delle quantità) nel carrello di un utente,
    // usato per il badge sull'icona del carrello nell'header.
    public function CountItems(int $utenteId) : int
    {
        try
        {
            $sql = "SELECT COALESCE(SUM(Qta), 0) as tot from carrello WHERE utenteid = :id;";
            $param['id'] = $utenteId;
            $row = parent::Select($sql, $param)[0];
            return (int)$row['tot'];
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e->getMessage() . "[CountItems]");
        }
    }

    // svuota completamente il carrello di un utente
    public function DeleteAll(int $utenteId) : bool
    {
        try
        {
            $sql = "DELETE FROM carrello WHERE UtenteId = :id;";
            $param['id'] = $utenteId;
            return parent::Delete($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e->getMessage() . "[DeleteAll]");
        }
    }

    // elimina solo le righe di carrello scelte, sempre filtrate per UtenteId
    // in modo che un form manomesso non possa eliminare righe di altri utenti.
    public function DeleteSelected(int $utenteId, array $carrelloIds) : bool
    {
        try
        {
            $carrelloIds = array_map('intval', $carrelloIds);
            if (count($carrelloIds) == 0) {
                return true;
            }

            $placeholders = [];
            $param = ['utenteId' => $utenteId];
            foreach ($carrelloIds as $i => $id) {
                $key = "id$i";
                $placeholders[] = ":$key";
                $param[$key] = $id;
            }

            $sql = "DELETE FROM carrello WHERE UtenteId = :utenteId AND CarrelloId IN (" . implode(',', $placeholders) . ");";
            return parent::Delete($sql, $param);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e->getMessage() . "[DeleteSelected]");
        }
    }

    public function DisplayCarrello(Prodotto $p, Carrello $c)
    {
        $src = $p -> GetNomeImmagine();

        echo "<div class='card border-primary mx-auto mb-3' style='max-width: 580px;'>";
        echo "    <div class='row g-0'>";
        echo "        <div class='col-md-1 d-flex align-items-center justify-content-center'>";
        echo "        <input type='checkbox' class='form-check-input' form='cartItemsForm' name='selected[]' value='" . $c->GetCarrelloId() . "' aria-label='Seleziona articolo'>";
        echo "        </div>";
        echo "        <div class='col-md-3'>";
        echo "        <img src='../images/$src.jpg' class='img-fluid rounded-start'>";
        echo "        </div>";
        echo "        <div class='col-md-8'>";
        echo "        <div class='card-body'>";
        echo "            <h5 class='card-title text-primary-emphasis'>". htmlspecialchars($p->GetProdotto()) ."</h5>";
        echo "            <p class='card-text text-black'>". htmlspecialchars($p->GetDescrizione()) ."</p>";
        echo "            <span class='card-text fs-5 fw-bold text-primary-emphasis d-flex justify-content-end'>Totale: ". number_format($c->GetQta() * $p->GetPrezzo(), 2, ',', '.') ."€</span>";
        echo "            <span class='card-text fw-semibold d-flex justify-content-end'>Quantità: " . $c->GetQta() . "</span>";
        echo "        </div>";
        echo "        </div>";
        echo "    </div>";
        echo "</div>";


    }
}