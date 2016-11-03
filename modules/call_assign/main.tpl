<form method="post" enctype="multipart/form-data" name="s_s">
    <p><b>{ASSIGN_RESULT}</b></p>
    <p>
        <strong>Офис:</strong>
        <select name="office_id" id="office_id" onchange="changeOfficeCode();">
            {OFFICES_ROWS}
        </select>
    <input type="hidden" value="{OFFICE_CODE}" name="office_code" id="office_code">
    </p>
    <p><strong>Телефон</strong><br>
        <input type="text" name="telnumber" class="pole_vvoda" style="padding-left:10px; width:300px;">
    <p>
        <button type="Submit" name="send_phone" class="btn_cour" onclick="javascript:hideShowDiv2('waitGear', 1);">Поставить в очередь</button>
</form>
