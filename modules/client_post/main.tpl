<diV class="kn_kl_fon tet_men" style="padding-left:18px;">
<!--<p><button class="btn_pero" onclick="javascript:window.location='system.php?menu=234';">Работа с клиентом</button></p>-->
<p><strong>Имя:</strong><br>
{INFO_U_NAME}
<p><strong>ИИН:</strong><br>
{INFO_U_IIN}
<input type="hidden" id="info_user_iin" value="{INFO_U_IIN}" />
<p><strong>Email:</strong><br>
{INFO_U_EMAIL}
<p><strong>Объект страхования:</strong><br>
{INFO_U_OBJ}
<p><strong>Дата оформления полиса:</strong><br>
{INFO_U_DATE_OFORM}
<p><strong>Дата начала полиса:</strong><br>
{INFO_U_DATE_START}
<p><strong>Дата окончания полиса:</strong><br>
{INFO_U_DATE_END}


<p><strong>Телефоны:</strong><br />
{INFO_U_PHONE}
<p><strong>Комментарий к клиенту:</strong><br>

	<div class="text_scroll">
		{INFO_U_COMMENT}
	</div>
<p align="center">
<input type="hidden" name="call_lenght2" id="call_lenght2" value="0" />
<button class="pole_sav" onclick="javascript:SavePOST();"></button>
</p>
</diV>