/**
 * Created by Toshiba on 27/03/2018.
 */

$('#mat').on('change', function() {
    //alert( this.value );
    var datos={
        "mat":this.value
    }
    var response = '';
    $.ajax({
        type: "POST",
        url: "./controllers/alumnos_controller.php",
        data: datos,
        success : function(text)
        {
            response = text;
        }
    });
    alert(response);
})
