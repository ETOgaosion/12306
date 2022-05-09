$(document).ready(function () {
    console.log($(window).height());
    $('input[type=checkbox][name=admin-login]').change(function () {
        if ($(this).is(':checked')) {
            $('#loginAuthDiv').show();
            $('#loginInputRegion').height('75%');
            $('#indexMainView').height('270px');
        }
        else {
            $('#loginAuthDiv').hide();
            $('#loginInputRegion').height('50%');
            $('#indexMainView').height('220px');
        }
    });

    $('input[type=checkbox][name=admin-register]').change(function () {
        if ($(this).is(':checked')) {
            $('#registerAuthorDiv').show();
            $('#registerAuthenDiv').show();
            $('#registerInputRegion').height('75%');
            $('#indexMainView').height('500px');
        }
        else {
            $('#registerAuthorDiv').hide();
            $('#registerAuthenDiv').hide();
            $('#registerInputRegion').height('50%');
            $('#indexMainView').height('370px');
        }
    });

    $('#nav-login-tab').click(function () {
        $('input[type=checkbox][name=admin-login]').prop('checked',false);
        $('#registerAuthorDiv').hide();
        $('#registerAuthenDiv').hide();
        $('#indexMainView').height('220px');
    });

    $('#nav-register-tab').click(function () {
        $('input[type=checkbox][name=admin-register]').prop('checked',false);
        $('#loginAuthDiv').hide();
        $('#indexMainView').height('370px');
    });
});

