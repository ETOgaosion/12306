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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Bootstrap Icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- App CSS -->
    <link href="<?= $assetsDir ?>css/app.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <title><?= $pageTitle ?></title>
</head>
<body>
    <header>
        <video autoplay muted loop id="video-background" class="position-absolute end-0 bottom-0 h-auto w-auto">
            <source src="<?= $assetsDir ?>media/videos/train-bg.mp4" type="video/mp4"> Your brower is old
        </video>
    </header>
    <nav class="navbar navbar-expand navbar-light bg-light" style="--bs-bg-opacity: .5">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/index">Train Database</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="collapseNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                    <?php
                        if ($login) {
                            echo <<<END
                            <a class="nav-link" href="/login"><i class="bi bi-person"></i></a>
                            END;
                        }
                        else {
                            echo <<<END
                            <a class="nav-link" href="/userSpace"><i class="bi bi-person"></i></a>
                            END;
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>