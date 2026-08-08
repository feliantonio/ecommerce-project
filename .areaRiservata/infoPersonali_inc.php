<?php

$db = new DbUtente;
$u = new Utente;
$sql = "SELECT utenteId,nome,cognome,mail,telefono,provincia,indirizzo,cap FROM utenti WHERE UtenteId = :id;";
$param['id'] = $_SESSION['UserId'];
$ar = $db->Select($sql, $param)[0];
Utente::SetUtente(
    $u,
    $ar['utenteId'],
    $ar['nome'],
    $ar['cognome'],
    $ar['mail'],
    $ar['telefono'],
    $ar['provincia'],
    $ar['indirizzo'],
    $ar['cap']
);

if (isset($_POST['USubmit'])) {
    Utente::SetUtente(
        $u,
        $ar['utenteId'],
        $_POST['nome'],
        $_POST['cognome'],
        $_POST['mail'],
        $_POST['tel'],
        $_POST['provincia'],
        $_POST['indirizzo'],
        $_POST['cap']
    );

    if($db -> UpdateUser($u))
    {
        echo "<div class='alert alert-success' role='alert'>Utente aggiornato</div>";
    }

}

?>

<form class="row g-3" action="" method="post">

    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value = "<?= $u->GetNome() != "" ? $u->GetNome() : ""?>">
    </div>

    <div class="col-md-6">
        <label for="cognome" class="form-label">Cognome</label>
        <input type="text" class="form-control" id="cognome" name="cognome" value="<?= $u->GetCognome() != "" ? $u->GetCognome() : "" ?>">
    </div>

    <div class="col-12">
        <label for="mail" class="form-label">Mail</label>
        <input type="email" class="form-control" id="mail" name="mail" value="<?= $u->GetMail() != "" ? $u->GetMail() : "" ?>">
    </div>

    <div class="col-12">
        <label for="tel" class="form-label">Telefono</label>
        <input type="text" class="form-control" id="tel" name="tel" value="<?= $u->GetTelefono() != "" ? $u->GetTelefono() : "" ?>">
    </div>

    <div class="col-md-6">
        <label for="provincia" class="form-label">Provincia</label>
        <input type="text" class="form-control" id="provincia" name="provincia" value="<?= $u->GetProvincia() != "" ? $u->GetProvincia() : "" ?>">
    </div>

    <div class="col-md-4">
        <label for="indirizzo" class="form-label">Indirizzo</label>
        <input type="text" class="form-control" id="indirizzo" name="indirizzo" value="<?= $u->GetIndirizzo() != "" ? $u->GetIndirizzo() : "" ?>">
    </div>

    <div class="col-md-2">
        <label for="cap" class="form-label">CAP</label>
        <input type="text" class="form-control" id="cap" name="cap" value="<?= $u->GetCap() != "" ? $u->GetCap() : "" ?>">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary" id="submit" name="USubmit">Aggiorna</button>
    </div>
</form>