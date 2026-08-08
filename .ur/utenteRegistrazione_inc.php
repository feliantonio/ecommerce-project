<!-- ####################################### -->
<?php
$pass1 = "";
$pass2 = "";

$u = new Utente();
$db = new DbUtente();

if (isset($_POST["btnSubmit"])) {

    $u->SetNome($_POST["nome"]);
    $u->SetMail($_POST["mail"]);
    $u->SetTelefono($_POST["tel"]);
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];

    if ($db->Register($u, $pass1)) {
        header("Location:../index/index.php");
    }
}

?>
<!-- ####################################### -->


<div class="container">

    <div class="row col-3 mb-2 mx-auto text-center">
        <h2 class="shadow bg-primary rounded text-white">Crea Account</h2>
    </div>

    <form class="row" action="" method="post" onsubmit="convalidaSub()">

        <div class="col-md-5 mb-2 mx-auto">
            <label for="nome" class="form-label">Nome<span class="controlloObbligatorio">*</span></label>
            <input type="text" class="form-control ctrlinput" id="nome" name="nome" value = "<?= $u->GetNome()?>" onchange="convalidaNome()"> 
            <div id="erNome"></div>
        </div>

        <div class="col-md-5 mb-2 mx-auto">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" class="form-control ctrlinput" id="cognome" name="cognome" value="<?=$u->GetCognome()?>">
            <div id="erCog"></div>
        </div>

        <div class="col-md-5 mb-2 mx-auto">
            <label for="mail" class="form-label">Mail<span class="controlloObbligatorio">*</span></label>
            <input type="email" class="form-control ctrlinput" id="mail" name="mail" value="<?=$u->GetMail()?>" onchange="convalidaMail()">
            <div id="erMail"></div>
        </div>

        <div class="col-md-5 mb-2 mx-auto">
            <label for="tel" class="form-label">Telefono</label>
            <input type="text" class="form-control ctrlinput" id="tel" name="tel" value="<?=$u->GetTelefono()?>">
            <div id="erTel"></div>
        </div>

        <div class="col-md-4 mx-auto mb-2">
            <label for="provincia" class="form-label">Provincia</label>
            <input type="text" class="form-control ctrlinput" id="provincia" name="provincia" value="<?=$u->GetProvincia()?>">
            <div id="erProv"></div>
        </div>

        <div class="col-md-3 mx-auto mb-2">
            <label for="indirizzo" class="form-label">Indirizzo</label>
            <input type="text" class="form-control ctrlinput" id="indirizzo" name="indirizzo" value="<?=$u->GetIndirizzo()?>">
            <div id="erInd"></div>
        </div>

        <div class="col-md-2 mx-auto mb-2">
            <label for="cap" class="form-label">CAP</label>
            <input type="text" class="form-control ctrlinput" id="cap" name="cap" value="<?=$u->GetCap()?>">
            <div id="erCap"></div>
        </div>

        <div class="col-md-5 mb-2 mx-auto">
            <label class="form-label">Password:<span class="controlloObbligatorio">*</span></label>
            <input class="form-control ctrlinput" type="password" id="pass1" name="pass1" value="<?= $pass1 ?>" onchange="convalidaPw()">
            <div id="erPw"></div>
        </div>

        <div class="col-md-5 mb-2 mx-auto">
            <label class="form-label">Conferma password:<span class="controlloObbligatorio">*</span></label>
            <input class="form-control ctrlinput" type="password" id="pass2" name="pass2" value="<?= $pass2 ?>" onchange="conPw()">
            <div id="erConPw"></div>
        </div>

        <div class="row col-4 m-2 mx-auto">
            <button class="btn btn-primary col-3 mx-auto" type="submit" value="submit" name="btnSubmit">Submit</button>
        </div>
    </form>



<script>
    function convalidaSub() {
        ok = false;
        if (convalidaNome() & convalidaMail() & convalidaPw() & conPw()) {
            ok = true;
        }

        if (ok == false) {
            event.preventDefault();
        }

    }

    function convalidaNome() {
        ok = true;
        try {
            ctrlEr = document.getElementById("erNome");
            ctrlEr.textContent = "";

            ctrl = document.getElementById("nome");
            ctrl.style.borderColor = "green";

            regex = /[a-zA-Z0-9 _]/g;
            stringInput = ctrl.value;
            corr = stringInput.match(regex);

            if (corr && corr.length >= 3) return ok;
            ok = false;
            ctrl.style.borderColor = "red";

            ctrlEr.textContent = "Inserire nome valido";
            ctrlEr.style.color = "red";
        } catch (e) {
            alert("si è verificato un errore" + e);
        } finally {
            return ok;
        }
    }

    function convalidaMail() {
        ok = true;
        try {
            ctrlEr = document.getElementById("erMail");
            ctrlEr.textContent = "";

            ctrl = document.getElementById("mail");
            ctrl.style.borderColor = "green";

            m = ctrl.value;
            mRegEx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (mRegEx.test(m)) return ok;
            ok = false;
            ctrl.style.borderColor = "red";

            ctrlEr.textContent = "Inserire mail valida";
            ctrlEr.style.color = "red";
        } catch (e) {
            alert("si è verificato un errore" + e);
        } finally {
            return ok;
        }
    }

    function convalidaPw() {
        ok = false;
        try {
            ctrlEr = document.getElementById("erPw");
            ctrlEr.textContent = "";

            ctrl = document.getElementById("pass1");
            ctrl.style.borderColor = "green";

            p = ctrl.value;
            pRegEx = /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/;

            if (!pRegEx.test(p)) {
                ctrl.style.borderColor = "red";
                ctrlEr.textContent = "La Password deve contenere almeno 1 lettera maiuscola, 1 lettera minuscola, 1 carattere speciale ed essere lunga minimo 8 caratteri";
                ctrlEr.style.color = "red";
                return ok;
            }
            ok = true;
        } catch (e) {
            alert("si è verificato un errore" + e);
        } finally {
            return ok;
        }
    }

    function conPw() {
        ok = true;
        try {
            ctrlEr = document.getElementById("erConPw");
            ctrlEr.textContent = "";

            document.getElementById("pass1").style.borderColor = "green";
            document.getElementById("pass2").style.borderColor = "green";

            pass1 = document.getElementById("pass1").value;
            pass2 = document.getElementById("pass2").value;

            if ((pass1 != pass2) || (pass1.trim().length == 0) || (pass2.trim().length == 0)) {
                document.getElementById("pass1").style.borderColor = "red";
                document.getElementById("pass2").style.borderColor = "red";

                ctrlEr.textContent = "Le passowrd non combaciano";
                ctrlEr.style.color = "red";
                ok = false;
            }

        } catch (e) {
            alert("si è verificato un errore" + e);
        } finally {
            return ok;
        }

    }


    // function convalidaCognome() {
    //     ok = true;
    //     try {
    //         ctrlEr = document.getElementById("erCog");
    //         ctrlEr.textContent = "";

    //         ctrl = document.getElementById("cognome");
    //         ctrl.style.borderColor = "green";

    //         if (ctrl.value == "") return ok;

    //         regex = /[a-zA-Z0-9 _]/g;
    //         stringInput = ctrl.value;
    //         corr = stringInput.match(regex);

    //         if (corr && corr.length >= 3) return ok;
    //         ok = false;
    //         ctrl.style.borderColor = "red";

    //         ctrlEr.textContent = "Inserire cognome valido";
    //         ctrlEr.style.color = "red";
    //     } catch (e) {
    //         alert("si è verificato un errore" + e);
    //     } finally {
    //         return ok;
    //     }
    // }

    // function convalidaTel() {
    //     ok = true;
    //     try {
    //         ctrlEr = document.getElementById("erTel");
    //         ctrlEr.textContent = "";

    //         ctrl = document.getElementById("tel");
    //         ctrl.style.borderColor = "green";

    //         if (ctrl.value == "") return ok;

    //         regex = /[0-9{8,}]/g;
    //         stringInput = ctrl.value;
    //         corr = stringInput.match(regex);

    //         if (corr && corr.length >= 8) return ok;
    //         ok = false;
    //         ctrl.style.borderColor = "red";

    //         ctrlEr.textContent = "Inserire numero di telefono valida";
    //         ctrlEr.style.color = "red";
    //     } catch (e) {
    //         alert("si è verificato un errore" + e);
    //     } finally {
    //         return ok;
    //     }
    // }

    // function convalidaProv() {
    //     ok = true;
    //     try {
    //         ctrlEr = document.getElementById("erProv");
    //         ctrlEr.textContent = "";

    //         ctrl = document.getElementById("provincia");
    //         ctrl.style.borderColor = "green";

    //         if (ctrl.value == "") return ok;

    //         regex = /[a-zA-Z{1,2}]/g;
    //         stringInput = ctrl.value;
    //         corr = stringInput.match(regex);

    //         if (corr && corr.length == 2) return ok;
    //         ok = false;
    //         ctrl.style.borderColor = "red";

    //         ctrlEr.textContent = "Inserire provincia valida";
    //         ctrlEr.style.color = "red";
    //     } catch (e) {
    //         alert("si è verificato un errore" + e);
    //     } finally {
    //         return ok;
    //     }
    // }

    // function convalidaInd() {
    //     ok = true;
    //     try {
    //         ctrlEr = document.getElementById("erInd");
    //         ctrlEr.textContent = "";

    //         ctrl = document.getElementById("indirizzo");
    //         ctrl.style.borderColor = "green";

    //         if (ctrl.value == "") return ok;

    //         regex = /[a-z0-9, {8,}]/g;
    //         stringInput = ctrl.value;
    //         corr = stringInput.match(regex);

    //         if (corr && corr.length >= 8) return ok;
    //         ok = false;
    //         ctrl.style.borderColor = "red";

    //         ctrlEr.textContent = "Inserire indirizzo valido";
    //         ctrlEr.style.color = "red";
    //     } catch (e) {
    //         alert("si è verificato un errore" + e);
    //     } finally {
    //         return ok;
    //     }
    // }


</script>