<?php

$db = new DbUtente();
if (isset($_POST['PSubmit'])) {

    if($db -> UpdatePsw($_SESSION['UserMail'],$_POST['oldPsw'],$_POST['psw1']))
    {
        echo "<div class='alert alert-success' role='alert'>";
            echo "Password cambiata! Torna alla <a href='../index/index.php' class='alert-link'>Home</a>";
        echo "</div>";
    }
    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
        echo "La vecchia password non è corretta, riprova";
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
}

?>


<div class="row col-5 mb-2 mx-auto">
    <h2 class="shadow bg-primary rounded text-white text-center">Modifica Password</h2>
</div>

<form class="row g-3" action="" method="post">

    <div class="col-7 mx-auto">
        <label for="oldPsw" class="form-label">Vecchia Password</label>
        <input type="password" class="form-control" id="oldPsw" name="oldPsw" required>
    </div>

    <div class="col-7 mx-auto">
        <label for="psw1" class="form-label">Nuova Password</label>
        <input type="password" class="form-control" id="psw1" name="psw1" required>
    </div>

    <div class="col-7 mx-auto">
        <label for="psw2" class="form-label">Conferma Password</label>
        <input type="password" class="form-control" id="psw2" name="psw2" required>
    </div>

    <div class="col-7 mx-auto">
        <button type=" submit" class="btn btn-primary" id="submit" name="PSubmit">Aggiorna</button>
    </div>
</form>