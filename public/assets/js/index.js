$(document).ready(function () {
    $('input[type=checkbox][name=admin-login]').change(function () {
        if ($(this).is(':checked')) {
            $('#loginAuthDiv').show();
            $('#nav-tabContent').height('220px');
            $('#loginInputRegion').height('75%');
            if ($('#resInfoDiv').length){
                $('#indexMainView').height('370px');
            }
            else {
                $('#indexMainView').height('270px');
            }
        }
        else {
            $('#loginAuthDiv').hide();
            $('#nav-tabContent').height('170px');
            $('#loginInputRegion').height('50%');
            if ($('#resInfoDiv').length){
                $('#indexMainView').height('320px');
            }
            else {
                $('#indexMainView').height('220px');
            }
        }
    });

    $('input[type=checkbox][name=admin-register]').change(function () {
        if ($(this).is(':checked')) {
            $('#registerAuthorDiv').show();
            $('#registerAuthenDiv').show();
            $('#registerRealNameInputDiv').hide();
            $('#nav-tabContent').height('370px');
            $('#registerInputRegion').height('75%');
            if ($('#resInfoDiv').length){
                $('#indexMainView').height('520px');
            }
            else {
                $('#indexMainView').height('420px');
            }
        }
        else {
            $('#registerAuthorDiv').hide();
            $('#registerAuthenDiv').hide();
            $('#registerRealNameInputDiv').show();
            $('#nav-tabContent').height('320px');
            $('#registerInputRegion').height('50%');
            if ($('#resInfoDiv').length){
                $('#indexMainView').height('470px');
            }
            else {
                $('#indexMainView').height('370px');
            }
        }
    });

    $('#nav-login-tab').click(function () {
        $('input[type=checkbox][name=admin-login]').prop('checked',false);
        $('#registerAuthorDiv').hide();
        $('#registerAuthenDiv').hide();
        $('#nav-tabContent').height('170px');
        if ($('#resInfoDiv').length){
            $('#indexMainView').height('260px');
        }
        else {
            $('#indexMainView').height('220px');
        }
    });

    $('#nav-register-tab').click(function () {
        $('input[type=checkbox][name=admin-register]').prop('checked',false);
        $('#loginAuthDiv').hide();
        $('#nav-tabContent').height('320px');
        if ($('#resInfoDiv').length){
            $('#indexMainView').height('470px');
        }
        else {
            $('#indexMainView').height('370px');
        }
    });
});

