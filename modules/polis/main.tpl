
<diV class="kn_kl_fon tet_men" style="padding-left:18px;">
<p><button class="btn_pero" onclick="javascript:window.location='/polis/?polis={POLIS_ID}';">Редактировать полис</button></p>
<!--<p align="center"><button class="kn_peres" onclick="javascript:window.location='system.php?menu=201';"></button></p>-->

<p><strong>Страховая компания</strong><br>
{INFO_P_ALIAS}
<p><strong>Период страхования</strong><br>
{INFO_P_STRACH_PERIOD}
<p><strong>Тип оплаты</strong><br>
{INFO_P_PAY_TYPE}
<p><strong>Вид оплаты</strong><br>
{INFO_P_PAY}
<p><strong>Дата оформления</strong><br>
{INFO_P_DATE_OFORM}
<p><strong>Дата начала действия</strong><br>
{INFO_P_DATE_START}
<p><strong>Дата окончания действия</strong><br>
{INFO_P_DATE_END}
<p><strong>Доставка</strong><br>
{INFO_P_DOST_CHECK}
<p><strong>Адрес доставки</strong><br>
{INFO_P_DOST_ADDRESS}
<p><strong>Дата доставки</strong><br>
{INFO_P_DATE_DOST}
<!--{INFO_P_GIFTS_ROWS}-->
<p><strong>Сумма к оплате</strong><br>
<span id="sum_for_cost">{INFO_P_SUMMA}</span> тг
<p><strong>Премия</strong><br>
{INFO_P_PREMIUM} тг
<input type="hidden" id="premium_for_cost" value="{INFO_P_PREMIUM2}" />
{INFO_P_DOPLATA}
<input type="hidden" name="call_lenght2" id="call_lenght2" value="0" />
<p align="center" id="FinalSaveBtn">
<button class="pole_sav" onclick="javascript:SavePolis();"></button>
</p>













</diV>