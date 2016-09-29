<script type="text/javascript" charset="utf-8">
    function closePerebros(){
        $('#DivPerebros').hide();
        $('#client_code1c').val(" ");
        $('textarea#description').val(" ");
        $('#waitGear').hide();
    }
    function showPerebros(client_code1c){
        $('#client_code1c').val(client_code1c);
        $('#waitGear').show();
        $('#DivPerebros').show();
    }
    function Perebros(){
        var client_code1c = $('#client_code1c').val();
        var super_code1c = $('#super_code1c').val();
        var manager_code1c = $('#oper_code').val();
        var description = $('textarea#description').val();
        console.log('client_code1c='+client_code1c);
        console.log('super_code1c='+super_code1c);
        console.log('manager_code1c='+manager_code1c);
        console.log('description='+description);
        //alert(client_code1c+"+"+super_code1c+"+"+manager_code1c+"+"+description);
        $.post("modules/move_client/move_client.php", {client_code1c:client_code1c, super_code1c:super_code1c, manager_code1c:manager_code1c, description:description},
                function(data){
                    //alert(data);
                    var obj = jQuery.parseJSON(data);
                    if(obj.result=='OK'){
                        //alert(obj.return);
                        closePerebros();
                        swal({   title: "Успешно",   text: "Клиент передан менеджеру",   html: true });
                    }
                    else{
                        //
                        swal("Ошибка", "Сбой соединения с базой!", "error");
                    }
                });

    }
</script>