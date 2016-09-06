<p><strong>Имя</strong><br>
{R_NAME}
<p><strong>Дата регистрации</strong><br>
{R_DATE_REG}
<p><strong>Логин</strong><br>
{R_LOGIN}
<p><strong>Внутренний номер</strong><br>
{R_PHONE}
<p><strong>Аватарка</strong><br>
{R_AV}<br />
<form method="post" enctype="multipart/form-data" name="s_s">
<p>
<div class="file_upload">
	<button type="button">Выбрать</button>
	<div>Файл фото не выбран</div>
	<input type="file" name="av">
</div>
<p><button type="Submit" class="pole_sav" name="edt_av"></button></p>
</form>
<script>
$(function(){
    var wrapper = $( ".file_upload" ),
        inp = wrapper.find( "input" ),
        btn = wrapper.find( "button" ),
        lbl = wrapper.find( "div" );

    btn.focus(function(){
        inp.focus()
    });
    // Crutches for the :focus style:
    inp.focus(function(){
        wrapper.addClass( "focus" );
    }).blur(function(){
        wrapper.removeClass( "focus" );
    });

    var file_api = ( window.File && window.FileReader && window.FileList && window.Blob ) ? true : false;

    inp.change(function(){
        var file_name;
        if( file_api && inp[ 0 ].files[ 0 ] ) {
            file_name = inp[0].files[0].name;
        }
        else {
            //file_name = inp.val().replace("C:\\fakepath\\", '');
            file_name = inp.val();
        }
        if( !file_name.length )
            return;

        if( lbl.is( ":visible" ) ){
            lbl.text( file_name );
            btn.text( "Выбрать" );
        }else
            btn.text( file_name );
    }).change();

});
$(window).resize(function(){
    $('.file_upload input').triggerHandler("change");
});
</script>