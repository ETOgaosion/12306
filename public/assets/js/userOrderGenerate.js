
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    var actions = $("table td:last-child").html();
    // Append table with add row form on add new button click
    $(".add-new").click(function(){
        var index = $("table tbody tr:last-child").index();
        var row = '<tr>' +
            '<td><input type="text" class="form-control" name="UserName' + (index +  1).toString() + '" id="UserName"></td>' +
            '<td><input type="text" class="form-control" name="UserRealName' + (index +  1).toString() + '" id="UserRealName"></td>' +
            '<td><input type="text" class="form-control" name="UserTelNum' + (index +  1).toString() + '" id="UserTelNum"></td>' +
            '<td>' + actions + '</td>' +
            '</tr>';
        $("table").append(row);
        $('[data-toggle="tooltip"]').tooltip();
    });
});