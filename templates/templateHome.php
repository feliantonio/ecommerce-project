<?php



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $mst->GetTitolo(); ?></title>
    <style>
        /* #footer {
            position: absolute;
            bottom: 0;
        } */
    </style>
</head>

<body>

    <!-- header -->
    <div class="container-fluid bg-info-subtle py-2">

        <?php
        require_once($mst->GetHeader());
        ?>

    </div>

    <br>

    <!-- main -->
    <div class="container">

        <main class="row">
            <?php if ($mst->GetAsideLeft() != "") { ?>
                <aside class="col-sm-3">
                    <?php require_once($mst->GetAsideLeft()); ?>
                </aside>
                <section class="col-sm-9">
                    <?php require_once($mst->GetContenuto()); ?>
                </section>
            <?php } else { ?>
                <section class="col-sm-12">
                    <?php require_once($mst->GetContenuto()); ?>
                </section>
            <?php } ?>
        </main>

    </div>

    <br>

    <!-- footer -->
    <div id="footer" class="container-fluid">

        <footer class="row bg-dark text-white">
            <?php
            if ($mst->GetFooter() != "")
            {
                require_once($mst->GetFooter());
            }
            ?>
        </footer>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>