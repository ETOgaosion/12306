$(document).ready(function () {
    $('input[type=checkbox][name=admin-login]').change(function () {
        if ($(this).is(':checked')) {
            $('#loginAuthDiv').show();
            $('#loginInputRegion').height('75%');
            $('#indexMainView').height('50%');
        }
        else {
            $('#loginAuthDiv').hide();
            $('#loginInputRegion').height('50%');
            $('#indexMainView').height('37.5%');
        }
    });

    $('input[type=checkbox][name=admin-register]').change(function () {
        if ($(this).is(':checked')) {
            $('#registerAuthorDiv').show();
            $('#registerAuthenDiv').show();
            $('#registerInputRegion').height('75%');
            $('#indexMainView').height('70%');
            $('#indexMainContainer').height('120vh');
        }
        else {
            $('#registerAuthorDiv').hide();
            $('#registerAuthenDiv').hide();
            $('#registerInputRegion').height('50%');
            $('#indexMainView').height('60%');
            $('#indexMainContainer').height('100vh');
        }
    });

    $('#nav-login-tab').click(function () {
        $('input[type=checkbox][name=admin-login]').prop('checked',false);
        $('#registerAuthorDiv').hide();
        $('#registerAuthenDiv').hide();
        $('#indexMainView').height('37.5%');
        $('#indexMainContainer').height('100vh');
    });

    $('#nav-register-tab').click(function () {
        $('input[type=checkbox][name=admin-register]').prop('checked',false);
        $('#loginAuthDiv').hide();
        $('#indexMainView').height('60%');
        $('#indexMainContainer').height('100vh');
    });
});

