
    <?php
    $host = 'localhost';
    $dbName = 'catalogo23_5cat';
    $user = 'root';
    $password = '';

    // if(!isset($_GET['page']))
    // {
    $currentPage = isset($_GET['page']) ? ($_GET['page']) : 1;
    $search = isset($_GET['search']) ? ($_GET['search']) : '';
    $sort_field = isset($_GET['sort']) ? ($_GET['sort']) : 'ProdottoID';
    $sort_order = isset($_GET['order']) ? ($_GET['order']) : 'ASC';
    // }
    // else
    // {
    //     $currentPage = isset($_GET['page']) ? ($_GET['page']) : 1;
    //     $sss = explode("+",$_GET['search']);
    //     $search = $sss[0];
    //     $sort_field = $sss[1];
    //     $sort_order = $sss[2];
    // }
    $recordsPerPage = 10;
    $offset = ($currentPage - 1) * $recordsPerPage;
    
    
    
    #connessione
    $db = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
    
    //totale record nel db
    $query = 'SELECT count(*) FROM prodotti WHERE prodotto like :search';
    $st = $db->prepare($query);
    $param['search'] = "%" . $search . "%";
    $st->execute($param);
    $totalRecord = $st->fetchColumn();
    $totalPages = ceil($totalRecord / $recordsPerPage);

    // echo "search: " . $search . " sort_order: " . $sort_order . " sort_field: " . $sort_field;
    // echo "<br>";
    // echo "params: record per pagina: " . $recordsPerPage;
    // echo "<br>";
    // echo "params: record record totali: " . $totalRecord;
    // echo "<br>";
    // echo "params: pagine totali: " . $totalPages;
    // echo "<br>";
    // echo "params: pagina corrente: " . $currentPage;

    // selezione prodotti dopo filtri o anche no filtri, dipende da $search
    $query = 'SELECT * FROM prodotti WHERE prodotto like :search';
    $query .= " ORDER BY $sort_field $sort_order LIMIT :limit offset :offset ;";

    $st = $db->prepare($query);
    $st->bindParam(':offset', $offset, PDO::PARAM_INT);
    $st->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
    $st->bindValue(':search', '%' . $search . '%');
    $st->execute();


    // ricerca prodotti
    echo '<form action="" method="get">';
    echo '<input type="text" id="search" name="search" placeholder="ricerca prodotti" value="' . $search . '">';

    echo '<select name="sort" id="sort">
                <option value="ProdottoId" ' . ($sort_field == 'ProdottoID' ? 'selected' : "") . '>ID</option>
                <option value="Prodotto" ' . ($sort_field == 'Prodotto' ? 'selected' : "") . '>Prodotto</option>
                <option value="Prezzo" ' . ($sort_field == 'Prezzo' ? 'selected' : "") . '>Prezzo</option>
              </select>
              <select name="order" id="order">
                <option value="ASC" ' . ($sort_field == 'ASC' ? 'selected' : "") . '>ASC</option>
                <option value="DESC" ' . ($sort_field == 'DESC' ? 'selected' : "") . '>DESC</option>
              </select>';
    echo '<button type="submit">conferma</button>';
    echo '</form>';


    // visualizza prodotti
    $id = 0;
    echo '<table>';
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $id++ . '</td>';
        echo '<td>' . $row['ProdottoID'] . '</td>';
        echo '<td>' . $row['Prodotto'] . '</td>';
        echo '<td>' . $row['Prezzo'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<div>';



    if ($currentPage > 1) {
        echo '<a href="?page=' . ($currentPage - 1) . '&search=' . $search .'">precedente</a>';
    }
    for ($page = 1; $page <= $totalPages; $page++) {
        if ($page == $currentPage) {
            echo '<strong> ' . $page . ' </strong>';
            # se il numero del link è 1 o quello dell'ultima pagina o 2 numeri prima o dopo della pagina corrente, mettilo tra i link per le pagine
        } elseif ($page == 1 || $page == $totalPages || ($page >= $currentPage - 2) && ($page <= $currentPage + 2)) {
            echo '<a href="?page=' . $page . '&search=' . $search . '"> ' . $page . ' </a> ';
        } elseif (($page >= $currentPage - 3) && ($page <= $currentPage + 3)) {
            echo '...';
        }
        // else {
        //     echo '<a href="?page='.$page.'">'.$page.'</a> ';
        // }
    }
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '&search=' . $search . '">successiva</a> ';
    }
    echo '</div>';
    ?>