$(document).ready(function () {
    console.log($(window).height());
    $('input[type=checkbox][name=admin-login]').change(function () {
        if ($(this).is(':checked')) {
            $('#loginAuthDiv').show();
            $('#loginInputRegion').height('75%');
            $('#indexMainView').height('270px');
            if ($(window).height() <= 270) {
                $('#indexMainContainer').height('270px');
            }
            else {
                $('#indexMainContainer').height('100vh');
            }
        }
        else {
            $('#loginAuthDiv').hide();
            $('#loginInputRegion').height('50%');
            $('#indexMainView').height('220px');
            if ($(window).height() <= 220) {
                $('#indexMainContainer').height('220px');
            }
            else {
                $('#indexMainContainer').height('100vh');
            }
        }
    });

    $('input[type=checkbox][name=admin-register]').change(function () {
        if ($(this).is(':checked')) {
            $('#registerAuthorDiv').show();
            $('#registerAuthenDiv').show();
            $('#registerInputRegion').height('75%');
            $('#indexMainView').height('520px');
            if ($(window).height() <= 800) {
                $('#indexMainContainer').height('800px');
            }
            else {
                $('#indexMainContainer').height('100vh');
            }
        }
        else {
            $('#registerAuthorDiv').hide();
            $('#registerAuthenDiv').hide();
            $('#registerInputRegion').height('50%');
            $('#indexMainView').height('370px');
            if ($(window).height() <= 600) {
                $('#indexMainContainer').height('600px');
            }
            else {
                $('#indexMainContainer').height('100vh');
            }
        }
    });

    $('#nav-login-tab').click(function () {
        $('input[type=checkbox][name=admin-login]').prop('checked',false);
        $('#registerAuthorDiv').hide();
        $('#registerAuthenDiv').hide();
        $('#indexMainView').height('220px');
        if ($(window).height() <= 600) {
            $('#indexMainContainer').height('600px');
        }
        else {
            $('#indexMainContainer').height('100vh');
        }
    });

    $('#nav-register-tab').click(function () {
        $('input[type=checkbox][name=admin-register]').prop('checked',false);
        $('#loginAuthDiv').hide();
        $('#indexMainView').height('370px');
        if ($(window).height() <= 600) {
            $('#indexMainContainer').height('600px');
        }
        else {
            $('#indexMainContainer').height('100vh');
        }
    });
});

