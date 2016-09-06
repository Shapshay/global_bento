<diV class="kn_kl_fon tet_men" style="padding-left:18px;">
<!--<p><button class="btn_pero" onclick="javascript:window.location='system.php?menu=234';">Работа с клиентом</button></p>-->
<p><strong>Наименование:</strong><br>
{INFO_U_FIO}
<p><strong>Имя:</strong><br>
{INFO_U_NAME}
<p><strong>Дата предыдущего ТО:</strong><br>
{INFO_U_DATE_PREV_TO}
<p><strong>Гос.номер:</strong><br>
{INFO_U_GN}
<p><strong>Тех.паспорт:</strong><br>
{INFO_U_PN}
<p><strong>Марка авто:</strong><br>
{INFO_U_MARK}
<p><strong>Модель авто:</strong><br>
{INFO_U_MODEL}
<p><strong>Год авто:</strong><br>
{INFO_U_YEAR}
<p><strong>ИИН:</strong><br>
{INFO_U_IIN}
<input type="hidden" id="info_user_iin" value="{INFO_U_IIN}" />
<p><strong>РНН:</strong><br>
{INFO_U_RNN}
<input type="hidden" id="info_user_rnn" value="{INFO_U_RNN}" />
<p><strong>Телефоны:</strong><br />
{INFO_U_PHONE}
<p><strong>Email:</strong><br>
{INFO_U_EMAIL}
<p><strong>Страховка</strong><br>
{INFO_U_POLIS}
<p><strong>Комментарий к клиенту:</strong><br>

	<div class="text_scroll">
		{INFO_U_COMMENT}
	</div>
<p align="center">
<input type="hidden" name="call_lenght2" id="call_lenght2" value="0" />
<button class="pole_sav" onclick="javascript:SaveTO();"></button>
</p>
</diV>