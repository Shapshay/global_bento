<h3 style="font-size:22px;font-family:'Stylo Bold';">Номер полиса</h3>
<p style="font-size:30px;">{VIEW_P_POLIS_NUM}
<p style="font-size:30px;">{VIEW_P_STATUS}<br>
<form method="post" id="printAdrFrm">
<input type="hidden" name="printAdr" value="1" />
<input type="hidden" name="policeNum" value="{VIEW_P_POLIS_NUM}" />
<p><button type="button" class="btn_pero" onclick="javascript:SendForm('printAdrFrm');">Адресная листовка</button></p>
</form>
<form method="post" id="printEvFrm">
<input type="hidden" name="printEv" value="1" />
<input type="hidden" name="policeNum" value="{VIEW_P_POLIS_NUM}" />
<p><button type="button" class="btn_pero" onclick="javascript:SendForm('printEvFrm');">Эвакуатор</button></p>
</form>
<form method="post" id="printPolisFrm">
<input type="hidden" name="printPolis" value="1" />
<p><button type="button" class="btn_pero" onclick="javascript:SendForm('printPolisFrm');">Полис распечатан</button></p>
</form>

<form method="post">
    <input type="hidden" name="pc_err" value="{VIEW_P_POLIS_NUM}">
    <button type="submit" class="btn_cour_err">На ошибку</button>
</form>
<hr align="left" width="600" noshade color="#983736" size="1">
<p>
<div class="block"> 

<h3 style="font-size:22px;font-family:'Stylo Bold';">Информация</h3>
<hr align="left" width="600" nodashed style="height:1px;border-top:0px;border-style:dashed; border-color:#983736;">
<p><strong>Оператор</strong><br>
{VIEW_P_OPER} 
<p><strong>Клиент</strong><br>
{VIEW_P_CLIENT} 
<p><strong>Телефоны клиента</strong><br>
{VIEW_P_PHONES} 

<p><strong>Страховая компания</strong><br>
{VIEW_P_ALIAS}
<p><strong>Период страхования</strong><br>
{VIEW_P_STRACH_PERIOD}
<p><strong>Тип оплаты</strong><br>
{VIEW_P_PAY_TYPE}
<p><strong>Вид оплаты</strong><br>
{VIEW_P_PAY}
<p><strong>Дата оформления</strong><br>
{VIEW_P_DATE_OFORM}
<p><strong>Дата начала действия</strong><br>
{VIEW_P_DATE_START}
<p><strong>Дата окончания действия</strong><br>
{VIEW_P_DATE_END}
<p><strong>Доставка</strong><br>
{VIEW_P_DOST_CHECK}
<p><strong>Адрес доставки</strong><br>
{VIEW_P_DOST_ADDRESS}
<p><strong>Дата доставки</strong><br>
{VIEW_P_DATE_DOST}
<p><strong>Сумма</strong><br>
{VIEW_P_SUMMA} тг
<p><strong>Премия</strong><br>
{VIEW_P_PREMIUM} тг
{VIEW_P_DOPLATA}
<hr align="left" width="600" nodashed style="height:1px;border-top:0px;border-style:dashed; border-color:#983736;">
<h3 style="font-size:22px;font-family:'Stylo Bold';">Подарки</h3>
<hr align="left" width="80%" nodashed style="height:1px;border-top:0px;border-style:dashed; border-color:#cccccc;">
<p><strong>Подарки</strong><br>
{VIEW_P_GIFTS} 
<p><strong>Сумма</strong><br>
{VIEW_P_GIFTS_NUM} 

</div> 