<?php

use app\tools\Session;
use app\controllers\ViewCtrl;

ViewCtrl::includePageHeader(array('pageTitle' => "index", 'assetsDir' => "assets/", 'login' => false));
$assetsDir = 'assets/';
?>

    <!-- View js -->
    <script src="<?= $assetsDir ?>js/index.js"></script>
    <div class="d-flex align-items-center justify-content-center position-absolute start-0 end-0"
         style="top: 75px; bottom: 100px" id="indexMainContainer">
        <?php
        if ((array_key_exists('loginFailed', $_SESSION) && $_SESSION['loginFailed']) ||
            (array_key_exists('loginSucceed', $_SESSION) && $_SESSION['loginSucceed']) ||
            (array_key_exists('registerFailed', $_SESSION) && $_SESSION['registerFailed']) ||
            (array_key_exists('registerSucceed', $_SESSION) && $_SESSION['registerSucceed']) ||
            (array_key_exists('preorderFailed', $_SESSION) && $_SESSION['preorderFailed']) ||
            (array_key_exists('orderSucceed', $_SESSION) && $_SESSION['orderSucceed']) ||
            (array_key_exists('orderFailed', $_SESSION) && $_SESSION['orderFailed'])) {
            echo <<<END
    <div class="apple-block position-relative" style="border-radius: 1rem; width: 100%; max-width: 500px; height: 260px" id="indexMainView">
END;
        } else {
            echo <<<END
    <div class="apple-block position-relative" style="border-radius: 1rem; width: 100%; max-width: 500px; height: 220px" id="indexMainView">
END;
        }
        ?>
        <nav style="height: 15%">
            <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-login-tab" data-bs-toggle="tab" data-bs-target="#nav-login"
                        type="button" role="tab" aria-controls="nav-login" aria-selected="true">login
                </button>
                <button class="nav-link" id="nav-register-tab" data-bs-toggle="tab" data-bs-target="#nav-register"
                        type="button" role="tab" aria-controls="nav-register" aria-selected="false">register
                </button>
            </div>
        </nav>
        <?php
        if (array_key_exists('loginFailed', $_SESSION) && $_SESSION['loginFailed']) {
            echo <<<END
        <div class="alert alert-danger" role="alert" id="resInfoDiv">
        Login failed because {$_SESSION['loginFailReason']}!
        </div>
END;
        } elseif (array_key_exists('registerFailed', $_SESSION) && $_SESSION['registerFailed']) {
            echo <<<END
        <div class="alert alert-danger" role="alert" id="resInfoDiv">
        Register Failed becausee {$_SESSION['registerFailReason']}!
        </div>
END;
        } elseif (array_key_exists('preorderFailed', $_SESSION) && $_SESSION['preorderFailed']) {
            echo <<<END
        <div class="alert alert-danger" role="alert" id="resInfoDiv">
        Preorder failed!
        </div>
END;
        } elseif (array_key_exists('orderSucceed', $_SESSION) && $_SESSION['orderSucceed']) {
            echo <<<END
        <div class="alert alert-success" role="alert" id="resInfoDiv">
        Order Succeed!
        </div>
END;
        } elseif (array_key_exists('orderFailed', $_SESSION) && $_SESSION['orderFailed']) {
            echo <<<END
        <div class="alert alert-danger" role="alert" id="resInfoDiv">
        Order failed, status: {$_SESSION['orderStatus']}!
        </div>
END;
        } elseif (array_key_exists('loginSucceed', $_SESSION) && $_SESSION['loginSucceed']) {
            echo <<<END
        <div class="alert alert-success" role="alert" id="resInfoDiv">
        Login Succeed!
        </div>
END;
        } elseif (array_key_exists('registerSucceed', $_SESSION) && $_SESSION['registerSucceed']) {
            echo <<<END
        <div class="alert alert-success" role="alert" id="resInfoDiv">
        Register Succeed!
        </div>
END;
        }
        ?>
        <div class="tab-content position-absolute start-0 end-0 bottom-0" id="nav-tabContent" style="height: 170px">
            <div class="tab-pane fade show active h-100" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
                <form class="h-100 p-3 d-flex flex-column justify-content-between" id="loginForm" action="login"
                      method="post">
                    <div class="p-1 position-relative" style="height: 50%" id="loginInputRegion">
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="loginUserNameInput" class="form-label">User Name:</label>
                            </div>
                            <div class="col-7">
                                <input type="text" name="loginUserNameInput" id="loginUserNameInput"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="loginPasswordInput" class="form-label">Password:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" name="loginPasswordInput" id="loginPasswordInput"
                                       class="form-control" aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="loginAuthDiv" style="display: none;">
                            <div class="col-5 align-items-center">
                                <label for="loginAuthInput" class="form-label">Authentication:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" name="loginAuthInput" id="loginAuthInput" class="form-control"
                                       aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                    </div>
                    <div class="row pb-4 h-25">
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <button type="submit" formmethod="post" class="btn btn-primary pt-1" id="loginSubmit">
                                Submit
                            </button>
                        </div>
                        <div class="col-6 d-flex justify-content-center align-items-center form-check">
                            <input class="form-check-input p-1" type="checkbox" value="Yes" id="admin-login"
                                   name="admin-login">
                            <label class="form-check-label p-1" for="admin-login">
                                Admin login
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade h-100" id="nav-register" role="tabpanel" aria-labelledby="nav-register-tab">
                <form class="h-100 ps-3 pe-3 d-flex flex-column justify-content-between" id="registerForm"
                      action="register" method="post">
                    <div class="p-1 position-relative" style="height: 75%" id="registerInputRegion">
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerUserNameInput" class="form-label">User Name:</label>
                            </div>
                            <div class="col-7">
                                <input type="text" name="registerUserNameInput" id="registerUserNameInput"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerPasswordInput" class="form-label">Password:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" name="registerPasswordInput" id="registerPasswordInput"
                                       class="form-control" aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerEmailInput" class="form-label">Email:</label>
                            </div>
                            <div class="col-7">
                                <input type="email" name="registerEmailInput" id="registerEmailInput"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerTelNumInput" class="form-label">Tel-Num:</label>
                            </div>
                            <div class="col-7">
                                <input type="tel" name="registerTelNumInput" id="registerTelNumInput"
                                       class="form-control"">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="registerRealNameInputDiv">
                            <div class="col-5 align-items-center">
                                <label for="registerRealNameInput" class="form-label">Real Name:</label>
                            </div>
                            <div class="col-7">
                                <input type="text" name="registerRealNameInput" id="registerRealNameInput"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="registerAuthorDiv" style="display: none;">
                            <div class="col-5 align-items-center">
                                <label for="registerAuthorityInput" class="form-label">Authority:</label>
                            </div>
                            <div class="col-7">
                                <input type="number" name="registerAuthorityInput" id="registerAuthorityInput"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="registerAuthenDiv" style="display: none;">
                            <div class="col-5 align-items-center">
                                <label for="registerAuthenticationInput" class="form-label">Authentication:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" name="registerAuthenticationInput"
                                       id="registerAuthenticationInput" class="form-control"
                                       aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                    </div>
                    <div class="row pb-4 h-25">
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <button type="submit" formmethod="post" class="btn btn-primary pt-1" id="registerSubmit">
                                Submit
                            </button>
                        </div>
                        <div class="col-6 d-flex justify-content-center align-items-center form-check">
                            <input class="form-check-input p-1" type="checkbox" value="Yes" id="admin-register"
                                   name="admin-register">
                            <label class="form-check-label p-1" for="admin-register">
                                Admin Register
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
ViewCtrl::includePageFooter();
?>