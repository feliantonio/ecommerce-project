<?php


class DbCatalogo extends DbRepository
{
    private int $recXPage = -1;
    private int $totPages = -1;
    private int $totRec = -1;
    private int $currentPage = -1;

    public function __construct() {
        $this -> SetRecXPage(12);
        parent::__construct();
    }

    // GET 

    public function GetRecXPage()
    {
        return $this -> recXPage;
    }

    public function GetCurrentPage()
    {
        return $this->currentPage;
    }

    public function GetTotPages()
    {
        return $this->totPages;
    }

    public function GetTotRec()
    {
        return $this->totRec;
    }

    //SET

    public function SetRecXPage(int $value)
    {
        $this ->recXPage = $value;
    }

    public function SetCurrentPage(int $value)
    {
        $this->currentPage = $value;
    }

    public function SetTotPages(int $value)
    {
        $this -> totPages = $value;
    }

    public function SetTotRecord(int $value)
    {
        $this -> totRec = $value;
    }

    public function PageParams(string $search, ?int $categoriaId = null, ?int $produttoreId = null)
    {
        try
        {
            $sql = "SELECT Count(*) from prodotti WHERE prodotto like :search";
            $param['search'] = "%" . $search . "%";
            if ($categoriaId !== null) {
                $sql .= " AND CategoriaId = :categoriaId";
                $param['categoriaId'] = $categoriaId;
            }
            if ($produttoreId !== null) {
                $sql .= " AND ProduttoreId = :produttoreId";
                $param['produttoreId'] = $produttoreId;
            }
            $sql .= ";";

            // set variabili di classe
            $totRec = parent::Select($sql,$param)[0]['Count(*)'];
            $totPages = ceil($totRec / $this -> GetRecXPage());

            $this->SetTotPages($totPages);
            $this ->SetTotRecord($totRec);
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[PageParams]");
        }
    }

    // whitelist: query() non permette il bind di nomi di colonna, quindi
    // $sortField/$sortOrder vanno validati contro un elenco fisso prima di
    // essere concatenati nella query, altrimenti si apre a SQL injection.
    private const ALLOWED_SORT_FIELDS = ['ProdottoID', 'Prodotto', 'Prezzo'];
    private const ALLOWED_SORT_ORDERS = ['ASC', 'DESC'];

    public function SelectCatalogo(string $search , string $sortField , string $sortOrder , int $limit , int $offset , ?int $categoriaId = null , ?int $produttoreId = null) : array
    {
        try
        {
            if (!in_array($sortField, self::ALLOWED_SORT_FIELDS, true)) {
                $sortField = 'ProdottoID';
            }
            if (!in_array(strtoupper($sortOrder), self::ALLOWED_SORT_ORDERS, true)) {
                $sortOrder = 'ASC';
            }
            $sql = "SELECT * from prodotti WHERE prodotto like :search";
            $param['search'] = "%" . $search . "%";
            if ($categoriaId !== null) {
                $sql .= " AND CategoriaId = :categoriaId";
                $param['categoriaId'] = $categoriaId;
            }
            if ($produttoreId !== null) {
                $sql .= " AND ProduttoreId = :produttoreId";
                $param['produttoreId'] = $produttoreId;
            }
            $sql .= " ORDER BY $sortField $sortOrder LIMIT $limit OFFSET $offset;";
            $rows = parent::Select($sql , $param);
            return $rows;
        }
        catch (Exception $e)
        {
            die("ERROR: " . $e -> getMessage() . "[SelectCatalogo]");
        }
    }

    public function DisplayCatalogo(array $ar, string $uTpye) 
    {
        foreach ($ar as $key => $value) {
            $src = $value['NomeImmagine'];
            echo "<a href='../.dettaglio/dettaglio.php?id=". $value['ProdottoID'] ."' class='col-auto mb-3 mx-auto' role='button' style='text-decoration: none'>";
            echo "    <div class='card' style='width: 18rem;'>";
            echo "        <img src='../images/$src.jpg' class='card-img-top'>";
            echo "        <div class='card-body relative'>";
            echo "            <h5 class='card-title'>" . htmlspecialchars($value['Prezzo']) . "€</h5>";
            echo "            <p class='card-text text-black'>" . $value['Prodotto'] . "</p>";
            if ($uTpye == "G")
            {
                echo "<form class = 'd-inline' action='' method='post'>";
                echo "    <button type='submit' name='idC' value ='" . $value['ProdottoID'] . "' class='btn btn-primary disabled'><svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-cart' viewBox='0 0 16 16'>
                <path d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z'/>
                </svg></button>";
                echo "</form>";
            }
            // elseif ($_SESSION['bln'] == 1) 
            // {
            //     echo "<form action='' method='post'>";
            //     echo "    <button type='submit' name='idC' value ='" . $value['ProdottoID'] . "' class='btn btn-primary disabled'>";
            //     echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-check-circle-fill' viewBox='0 0 16 16'>
            //         <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z'/>
            //         </svg>";
            //     echo "</button>";
            //     echo "</form>";
            // }
            else
            {
                echo "<form class = 'd-inline' action='' method='post'>";
                echo "    <button type='submit' name='idC' value ='" . $value['ProdottoID'] . "' class='btn btn-primary'><svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-cart' viewBox='0 0 16 16'>
                <path d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z'/>
                </svg></button>";
                echo "</form>";
            }
            echo "        </div>";
            echo "    </div>";
            echo "</a>";
        }

        echo "<div class='col-3 mx-auto'>";
        echo "  <nav aria-label='Page navigation example'>";
        echo "    <ul class='pagination'>";
        if($this -> currentPage == 1)
        {
            echo "      <li class='page-item'><a class='page-link disabled' href='#'>Previous</a></li>";
        }
        else
        {
            echo "      <li class='page-item'><a class='page-link' href='" . Common::BuildQuery(['page' => $this->currentPage - 1]) . "'>Previous</a></li>";
        }
        if($this->currentPage != 1)
        {
            echo "      <li class='page-item'><a class='page-link' href='" . Common::BuildQuery(['page' => 1]) . "'>1</a></li>";
        }
        if($this->currentPage - 2 > 1)
        {
            echo "      <li class='page-item'><a class='page-link' href='".Common::BuildQuery(['page' => $this -> currentPage - 2])."'>".($this -> currentPage - 2)."</a></li>";
        }
        if ($this->currentPage - 1 > 1)
        {
            echo "      <li class='page-item'><a class='page-link' href='".Common::BuildQuery(['page' => $this -> currentPage - 1])."'>".($this -> currentPage - 1)."</a></li>";
        }
        echo "      <li class='page-item'><a class='page-link disabled' href='#'>".$this -> currentPage."</a></li>";
        if ($this->currentPage + 1 < $this->totPages)
        {
            echo "      <li class='page-item'><a class='page-link' href='".Common::BuildQuery(['page' => $this -> currentPage + 1])."'>".($this -> currentPage + 1)."</a></li>";
        }
        if ($this->currentPage + 2 < $this->totPages)
        {
            echo "      <li class='page-item'><a class='page-link' href='".Common::BuildQuery(['page' => $this -> currentPage + 2])."'>".($this -> currentPage + 2)."</a></li>";
        }
        if($this->currentPage != $this->totPages)
        {
            echo "      <li class='page-item'><a class='page-link' href='" . Common::BuildQuery(['page' => $this->totPages]) . "'>".($this ->totPages)."</a></li>";
        }

        if($this->currentPage == $this->totPages)
        {
            echo "      <li class='page-item'><a class='page-link disabled' href='#'>Next</a></li>";
        }
        else {
            echo "      <li class='page-item'><a class='page-link' href='" . Common::BuildQuery(['page' => $this->currentPage + 1]) . "'>Next</a></li>";
        }
        echo "    </ul>";
        echo "  </nav>";
        echo "</div>";
    }
}


?>