//
// manipulation de la table PromoCodes
// 
function DppAddRow(object)
{
    if ($('#visites').val() && $('#code').val() ) {
        var ligne = '<tr><td>'+$('#visites').val()+'</td><td>'+$('#code').val()+'</td><td>'+$('#message').val()+'</td><td><a href"#"  onclick="DppDeleteRow(this);"><img src="/dynaprice/web/img/delete_obj.gif"></a></td></tr>';
        $('#tableau tr:last').after(ligne);
    }
    $('#visites').val('');
    $('#code').val('');
    $('#message').val('');
}
function DppDeleteRow(r)
{
    if (confirm("delete row ?")) {
        $(r).parents('tr').first().remove();
    }
}
function DppComputeTabCode(object) // sur onSubmit
{   
    var zin = object;
    var top = false;
    zin.val(''); 
    $('#tableau').find("tr").each(function() {
        if (zin.val()) {
            zin.val(zin.val()+',');
            }
        if (top) {
            zin.val(zin.val()+'['+$(this).find("td").eq(0).text()+',"'+$(this).find("td").eq(1).text()+'","'+ $(this).find("td").eq(2).text()+'"]');  
        }
        top = true;
    });
    if (zin.val()) {
        zin.val('['+zin.val()+']');
    } else {
        zin.val("");
    }
    return true;
}
