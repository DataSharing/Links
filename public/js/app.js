$(document).ready(function () {
    setTimeout(function () {
        $(".notif").remove();
    }, 7000);
});

function check_duplicate()
{
    var shortcut = $('#shortcut').val();
    $.ajax({
        async: false,
        type: "POST",
        url: "./ajax/duplicates.php",
        data: "shortcut=" + shortcut ,
        success: function (html) {
            $("#duplicate").html(html);
        }
    });
}