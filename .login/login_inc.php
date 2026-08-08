<?php



?>

<script>
    function passwordErrata() {
        alert("inserita mail/password errata");
    }
</script>


<?php

$utente = new Utente();
$db = new DbUtente;

if (isset($_POST['btnSubmit'])) {

    $mail = ($_POST['mail']);
    $pass1 = $_POST['pass1'];

    if ($db->Login($mail, $pass1)) {
        header("Location: ../index/index.php");
    } else {
        echo "<script>passwordErrata();</script>";
    }
}

?>
<div class="container">
    <div class="row col-5 mb-2 mx-auto text-center">
        <h2>LOGIN</h2>
    </div>

    <form action="login.php" method="post">

        <div class="row col-4 mb-2 mx-auto">
            <label class="form-label">Mail:<span class="controlloObbligatorio">*</span></label>
            <input class="form-control ctrlinput" type="email" id="mail" name="mail" placeholder="Inserire mail valida" value="<?= $utente->GetMail() ?>">
        </div>
        <div class="row col-4 mb-2 mx-auto">
            <label class="form-label">Password:<span class="controlloObbligatorio">*</span></label>
            <input class="form-control ctrlinput" type="password" id="pass1" name="pass1" placeholder="Inserire password" value="<?= $utente->GetPassword() ?>">
        </div>
        <div class="row col-2 mb-2 mx-auto">
            <button class="btn btn-primary" type="submit" value="submit" name="btnSubmit">Login</button>
        </div>
    </form>
    <a href="../views/userUpdate.php">user update</a>
</div>
    <script>
        // function convalidaNome() {
        //     ok = true;
        //     try {
        //         ctrl = document.getElementById("nome");

        //         ctrl.style.borderColor = "black";

        //         regEx = /[A-Za-z0-9]/g;
        //         stringInput = ctrl.value;
        //         corr = stringInput.match(regEx);

        //         if(corr && corr.length>=3) {return ok;}
        //         else{ok = false;
        //             ctrl.style.borderColor = "red";}
        //     } catch(e) {
        //         alert ("si è verificato un errore" +e);
        //         ok = false;
        //     } finally { return ok;}
        // }

        function convalidaMail() {
            ok = true;
            try {
                ctrl = document.getElementById("mail");
                ctrl.style.borderColor = "black";

                m = ctrl.value;
                mRegEx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; //mail regex

                if (mRegEx.test(m)) {
                    return ok;
                } else {
                    ok = false;
                    ctrl.style.borderColor = "red";
                }
            } catch (e) {
                alert("si è verificato un errore" + e);
                ok = false;
            } finally {
                return ok;
            }
        }

        function convalidaPw() {
            ok = false;
            try {
                ctrl = document.getElementById("pass1");
                ctrl.style.borderColor = "black";

                p = ctrl.value;
                pRegEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
                if (pRegEx.test(p)) {
                    ctrl.style.borderColor = "red";
                    return ok;
                } else {
                    ok = true;
                }

            } catch (e) {
                alert("si è verificato un errore" + e);
            } finally {
                return ok;
            }
        }

        function conPw() {
            ok = true;
            try {
                document.getElementById("pass1").style.borderColor = "black";
                document.getElementById("pass2").style.borderColor = "black";

                pass1 = document.getElementById("pass1").value;
                pass2 = document.getElementById("pass2").value;

                if ((pass1 != pass2) || (pass1.trim().length == 0) || (pass2.trim().length == 0)) {
                    document.getElementById("pass1").style.borderColor = "red";
                    document.getElementById("pass2").style.borderColor = "red";
                    ok = false;
                }
            } catch (e) {
                alert("si è verificato un errore" + e);
                ok = false;
            } finally {
                return ok;
            }
        }

        function convalidaSub() {
            return true;
            if (convalidaMail() & convalidaPw() & conPw()) {
                ok = true;
            } else {
                ok = false;
            }

            if (ok == false) event.preventDefault();
        }
    </script>