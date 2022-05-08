<?php
$pageTitle = "hello";
$assetsDir = "../../assets/";
$login = false;
?>

<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Bootstrap Icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- App CSS -->
    <link href="<?= $assetsDir ?>css/app.css" rel="stylesheet">

    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

    <!-- popper js link -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/popper-utils.min.js"></script>

    <!-- jQuery js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- App js -->
    <script src="<?= $assetsDir ?>js/app.js"></script>

    <title><?= $pageTitle ?></title>
</head>
<body>
<video autoplay muted loop id="video-background" class="position-fixed"
       style="height: 100vh; width: 100%; object-fit: cover">
    <source src="<?= $assetsDir ?>media/videos/train-bg.mp4" type="video/mp4">
    Your browser is old
</video>
<nav class="navbar navbar-expand fixed-top navbar-dark bg-blueviolet" style="height: 75px">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="../index">Train Database</a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="collapseNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <?php
                    if ($login) {
                        echo <<<END
                            <a class="nav-link" href="../userSpace"><i class="bi bi-person-circle" style="font-size: 1.5rem;"></i></a>
                            END;
                    } else {
                        echo <<<END
                            <a class="nav-link" href="../login"><i class="bi bi-person" style="font-size: 1.5rem;"></i></a>
                            END;
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
</nav>