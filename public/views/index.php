<?php
$pageTitle = "index";
$assetsDir = "../assets/";
$login = false;
?>

<!-- View js -->
<script src="<?= $assetsDir ?>js/index.js"></script>
<div class="container d-flex align-items-center justify-content-center position-absolute top-0 start-0 bottom-0 end-0" id="indexMainContainer">
    <div class="bg-light" style="border-radius: 1rem; width: 40%; height: 220px" id="indexMainView">
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
        <div class="tab-content" id="nav-tabContent" style="height: 85%">
            <div class="tab-pane fade show active h-100" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
                <form class="h-100 p-3 d-flex flex-column justify-content-between" id="loginForm">
                    <div class="p-1 position-relative" style="height: 50%" id="loginInputRegion">
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="loginUserNameInput" class="form-label">User Name:</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="loginUserNameInput" class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="loginPasswordInput" class="form-label">Password:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" id="loginPasswordInput" class="form-control" aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="loginAuthDiv" style="display: none;">
                            <div class="col-5 align-items-center">
                                <label for="loginAuthInput" class="form-label">Authentication:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" id="loginAuthInput" class="form-control"
                                       aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                    </div>
                    <div class="row pb-4 h-25">
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary pt-1" id="loginSubmit">Submit</button>
                        </div>
                        <div class="col-6 d-flex justify-content-center align-items-center form-check">
                            <input class="form-check-input p-1" type="checkbox" value="" id="admin-login"
                                   name="admin-login">
                            <label class="form-check-label p-1" for="admin-login">
                                Admin login
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade h-100" id="nav-register" role="tabpanel" aria-labelledby="nav-register-tab">
                <form class="h-100 ps-3 pe-3 d-flex flex-column justify-content-between" id="registerForm">
                    <div class="p-1 position-relative" style="height: 75%" id="registerInputRegion">
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerUserNameInput" class="form-label">User Name:</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="registerUserNameInput" class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerPasswordInput" class="form-label">Password:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" id="registerPasswordInput" class="form-control" aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerEmailInput" class="form-label">Email:</label>
                            </div>
                            <div class="col-7">
                                <input type="email" id="registerEmailInput" class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerTelNumInput" class="form-label">Tel-Num:</label>
                            </div>
                            <div class="col-7">
                                <input type="tel" id="registerTelNumInput" class="form-control"">
                            </div>
                        </div>
                        <div class="row align-items-center p-1">
                            <div class="col-5 align-items-center">
                                <label for="registerRealNameInput" class="form-label">Real Name:</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="registerRealNameInput" class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="registerAuthorDiv" style="display: none;">
                            <div class="col-5 align-items-center">
                                <label for="registerAuthorityInput" class="form-label">Authority:</label>
                            </div>
                            <div class="col-7">
                                <input type="number" id="registerAuthorityInput" class="form-control">
                            </div>
                        </div>
                        <div class="row align-items-center p-1" id="registerAuthenDiv" style="display: none;">
                            <div class="col-5 align-items-center">
                                <label for="registerAuthenticationInput" class="form-label">Authentication:</label>
                            </div>
                            <div class="col-7">
                                <input type="password" id="registerAuthenticationInput" class="form-control"
                                       aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                    </div>
                    <div class="row pb-4 h-25">
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary pt-1" id="registerSubmit">Submit</button>
                        </div>
                        <div class="col-6 d-flex justify-content-center align-items-center form-check">
                            <input class="form-check-input p-1" type="checkbox" value="" id="admin-register"
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
</div>
