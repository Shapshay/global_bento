<form method="post" enctype="multipart/form-data" name="s_s" id="edtClientAutoForm">
	<input type="hidden" name="code_1C" id="code_1C" value="{EDT_1C_CODE}" />
    <input type="hidden" name="client_id" id="client_id" value="{EDT_CLIENT_ID}" />
    <input type="hidden" name="auto_send" id="auto_send" value="0" />
    <input type="hidden" name="dozvon" id="dozvon" value="0" />
    <input type="hidden" name="before_call_send" id="before_call_send" value="0" />
    <input type="hidden" name="why_call_send" id="why_call_send" value="0" />
    <input type="hidden" name="why_call_val" id="why_call_val" value="0" />
    <input type="hidden" name="rating" id="rating" value="{CALL_TARGET_RATING}"  >
    <input type="hidden" name="err_call_send" id="err_call_send" value="0" />
    <input type="hidden" name="err_type" id="err_type" value="0" />
    <input type="hidden" name="err_city" id="err_city" value="0" />
    <input type="hidden" name="call_lenght" id="call_lenght" value="0" />
    <input type="hidden" name="date_next_call" id="date_next_call_h" value="0" />
    <div class="clear"></div>
    <div class="info_block" id="block1_1" onclick="ShowBlock(1);">
        <div class="block_title">1.	Холодный звонок</div>
        <div class="block_info_text">
            <div class="info_block_item"><strong>Имя:</strong> <span id="txt_name">{EDT_NAME}</span></div>
            <div class="info_block_item"><strong>Город:</strong> <span id="txt_city">{EDT_CITY}</span></div>
            <div class="info_block_item"><strong>Есть ли машина:</strong> <span id="txt_car">{EDT_CAR}</span></div>
            <div class="info_block_item"><strong>E-mail:</strong> <span id="txt_email">{EDT_EMAIL}</span></div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="edt_block" id="block1_2">
        <div class="block_title">1.	Холодный звонок</div>
        <div class="edt_block_item"><div class="edt_label">Имя:</div><input type="text" name="name" id="name" value="{EDT_NAME}"></div>
        <div class="edt_block_item">
            <div class="edt_label">Город:</div>
            <select name="city" id="city">
                <option value="0">Неуказан</option>
                {CITYS_ROWS}
            </select>
        </div>
        <div class="edt_block_item"><div class="edt_label">Есть ли машина:</div><input type="checkbox" name="car" id="car" value="1"{EDT_CAR_CHECK}></div>
        <div class="edt_block_item"><div class="edt_label">E-mail:</div><input type="text" id="email" name="email" value="{EDT_EMAIL}"></div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="info_block" id="block2_1" onclick="ShowBlock(2);">
        <div class="block_title">2.	Точная дата</div>
        <div class="block_info_text">
            <div class="info_block_item"><strong>Точная дата:</strong> <span id="txt_date_end">{EDT_DATE_END}</span></div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="edt_block" id="block2_2">
        <div class="block_title">2.	Точная дата</div>
        <div class="edt_block_item"><div class="edt_label">Точная дата:</div>
            <input type="text" name="date_end" id="date_end" value="{EDT_DATE_END}" onchange="TDChange();"></div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="info_block" id="block3_1" onclick="ShowBlock(3);">
        <div class="block_title">3.	Расчет полиса</div>
        <div class="block_info_text">
            <div class="info_block_item"><strong>ИИН:</strong> <span id="txt_iin">{EDT_IIN}</span></div>
            <div class="info_block_item"><strong>Гос.номер:</strong> <span id="txt_gn">{EDT_GN}</span></div>
            <div class="info_block_item"><strong>Сумма:</strong> <span id="txt_premium">{EDT_PREMIUM}</span></div>
            <div class="info_block_item"><strong>Сумма со скидкой:</strong> <span id="txt_real_premium">{EDT_REAL_PREMIUM}</span></div>
            <div class="info_block_item"><strong>Доп. ИИН 1:</strong> <span id="txt_dop_iin1">{EDT_DOP_IIN1}</span></div>
            <div class="info_block_item"><strong>Доп. ИИН 2:</strong> <span id="txt_dop_iin2">{EDT_DOP_IIN2}</span></div>
            <div class="info_block_item"><strong>Доп. ИИН 3:</strong> <span id="txt_dop_iin3">{EDT_DOP_IIN3}</span></div>
            <div class="info_block_item"><strong>Доп. ИИН 4:</strong> <span id="txt_dop_iin4">{EDT_DOP_IIN4}</span></div>
            <div class="info_block_item"><strong>Доп. ИИН 5:</strong> <span id="txt_dop_iin5">{EDT_DOP_IIN5}</span></div>
            <div class="info_block_item"><strong>Доп. Гос.номер 1:</strong> <span id="txt_dop_gn1">{EDT_DOP_GN1}</span></div>
            <div class="info_block_item"><strong>Доп. Гос.номер 2:</strong> <span id="txt_dop_gn2">{EDT_DOP_GN2}</span></div>
            <div class="info_block_item"><strong>Доп. Гос.номер 3:</strong> <span id="txt_dop_gn3">{EDT_DOP_GN3}</span></div>


            <div class="info_block_item"><strong>Комментарий:</strong> <span id="txt_call_comment">{EDT_COMMENT}</span></div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="edt_block" id="block3_2">
        <div class="block_title">3.	Расчет полиса</div>
        <div class="edt_block_item"><div class="edt_label">ИИН:</div>
            <input type="text" name="iin" id="iin" value="{EDT_IIN}"></div>
        <div class="edt_block_item"><div class="edt_label">Гос.номер:</div>
            <input type="text" name="gn" id="gn" value="{EDT_GN}"></div>
        <div class="edt_block_item"><div class="edt_label">Сумма:</div>
            <input type="text" name="premium" id="premium" value="{EDT_PREMIUM}"></div>
        <div class="edt_block_item"><div class="edt_label">Сумма со скидкой:</div>
            <input type="text" name="real_premium" id="real_premium" value="{EDT_REAL_PREMIUM}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. ИИН 1:</div>
            <input type="text" name="dop_iin1" id="dop_iin1" value="{EDT_DOP_IIN1}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. ИИН 2:</div>
            <input type="text" name="dop_iin2" id="dop_iin2" value="{EDT_DOP_IIN2}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. ИИН 3:</div>
            <input type="text" name="dop_iin3" id="dop_iin3" value="{EDT_DOP_IIN3}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. ИИН 4:</div>
            <input type="text" name="dop_iin4" id="dop_iin4" value="{EDT_DOP_IIN4}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. ИИН 5:</div>
            <input type="text" name="dop_iin5" id="dop_iin5" value="{EDT_DOP_IIN5}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. Гос.номер 1:</div>
            <input type="text" name="dop_gn1" id="dop_gn1" value="{EDT_DOP_GN1}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. Гос.номер 2:</div>
            <input type="text" name="dop_gn2" id="dop_gn2" value="{EDT_DOP_GN2}"></div>
        <div class="edt_block_item"><div class="edt_label">Доп. Гос.номер 3:</div>
            <input type="text" name="dop_gn3" id="dop_gn3" value="{EDT_DOP_GN3}"></div>


        <div class="edt_block_item"><div class="edt_label">Комментарий:</div>
            <input type="text" name="call_comment" id="call_comment" value="{EDT_COMMENT}"></div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="info_block" id="block4_1" onclick="ShowBlock(4);">
        <div class="block_title">4.	4 ВП</div>
        <div class="block_info_text">
            <div class="info_block_item"><strong>Страховая компания:</strong> <span id="txt_strah">{EDT_4VP_STRAH}</span></div>
            <div class="info_block_item"><strong>Была ли доставка:</strong> <span id="txt_vp4_dost">{EDT_4VP_DOST}</span></div>
            <div class="info_block_item"><strong>Юрист:</strong> <span id="txt_vp4_yur">{EDT_4VP_YUR}</span></div>
            <div class="info_block_item"><strong>Эвакуатор:</strong> <span id="txt_vp4_ev">{EDT_4VP_EV}</span></div>
            <div class="info_block_item"><strong>Предоставлялся ли Коргау:</strong> <span id="txt_vp4_korgau">{EDT_4VP_KORGAU}</span></div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="edt_block" id="block4_2">
        <div class="block_title">4.	4 ВП</div>
        <div class="edt_block_item">
            <div class="edt_label">Страховая компания:</div>
            <select name="strah" id="strah">
                <option value="0">Неуказана</option>
                {STRAHS_ROWS}
            </select>
        </div>
        <div class="edt_block_item"><div class="edt_label">Была ли доставка:</div><input type="checkbox" name="vp4_dost" id="vp4_dost" value="1"{EDT_4VP_DOST_CHECK}></div>
        <div class="edt_block_item"><div class="edt_label">Юрист:</div><input type="checkbox" name="vp4_yur" id="vp4_yur" value="1"{EDT_4VP_YUR_CHECK}></div>
        <div class="edt_block_item"><div class="edt_label">Эвакуатор:</div><input type="checkbox" name="vp4_ev" id="vp4_ev" value="1"{EDT_4VP_EV_CHECK}></div>
        <div class="edt_block_item"><div class="edt_label">Предоставлялся ли Коргау:</div><input type="checkbox" name="vp4_korgau" id="vp4_korgau" value="1"{EDT_4VP_KORGAU_CHECK}></div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="edt_block" id="block5_2">
        <div class="block_title">5.	Оформление полиса</div>
        <div class="clear"></div>
        {NO_PRODAZH_HIDE1}
        <p align="center"><button type="button" class="auto_btn_next2" name="next_block" onclick="addPolis();">Перейти к оформлению полиса</button></p>
        {NO_PRODAZH_HIDE2}
        {PRODAZH_HIDE1}
        <p align="center"><strong>У Вас нет права страховать !</strong></p>
        {PRODAZH_HIDE2}
    </div>
    <div class="clear"></div>
    <p>
        <button type="button" class="btn_cour" name="next_client" onclick="NextClient();">Следующий клиент</button>
        <button type="button" class="auto_btn_next" id="next_block" name="next_block" onclick="NextBlock();">Далее</button>
    </p>
</form>

<!--modal windows-->
<div id="DivCallResult" class="DivPause">
    <div id="close_response"><a href="javascript:void();" onclick="closeDivCallResult();"><img src="images/close.png" /></a></div>
    <div id="CallResultContent">
        <p><button type="button" class="btn_cour" name="next_client" onclick="NextCallShow();">Дата следующего контакта</button></p>
        <p><button type="button" class="btn_cour_err" name="next_client" onclick="ErrorShow();">Ошибка</button></p>
    </div>
</div>

<div id="DivNextCall" class="DivPause">
    <div id="close_response"><a href="javascript:void();" onclick="closeDivNextCall();"><img src="images/close.png" /></a></div>
    <div id="CallResultContent">
        <p><strong>Дата следующего звонка:</strong><br>
        <input type="text" name="date_next_call" id="date_next_call" value="{EDT_DATE_NEXT_CALL}" onchange="DateNextCheck();"></p>
        <div id="why_div" style="display: none;">
        <p><strong>Причина перезвона:</strong><br>
            <select id="why_call" name="why_call" onchange="WhyCallChange();">
            <option value="0">Укажите причину</option>
            <option value="Уточнит ТД">Уточнит ТД</option>
            <option value="Нет машины">Нет машины</option>
            <option value="Клиент не в городе">Клиент не в городе</option>
            <option value="Назвали точную дату">Назвали точную дату</option>
            <option value="Отказ">Отказ</option>
            </select>
        </p>
        </div>
        <p><button type="button" class="btn_cour" id="why_send_btn" name="next_client" onclick="WhyCallSend();" style="display: none;">Отправить</button></p>
    </div>
</div>

<div id="DivErrors" class="DivPause">
    <div id="close_response"><a href="javascript:void();" onclick="closeDivErrors();"><img src="images/close.png" /></a></div>
    <div id="CallResultContent">
        <p><strong>Причина ошибки:</strong><br>
            <select id="errs" name="errs" onchange="ErrorsChange();">
                <option value="0">Укажите причину ошибки</option>
                <option value="Другой город">Другой город</option>
                <option value="Нет машины">Нет машины</option>
                <option value="Номер не существует">Номер не существует</option>
            </select>
        </p>
        <div id="errs_div" style="display: none;">
            <p><strong>Укажите город:</strong><br>
                <select id="citys" name="citys" onchange="CitysChange();">
                    <option value="0">Укажите город</option>
                    <option value="Алматы">Алматы</option>
                    <option value="Астана">Астана</option>
                    <option value="Актау">Актау</option>
                    <option value="Актобе">Актобе</option>
                    <option value="Атырау">Атырау</option>
                    <option value="Караганда">Караганда</option>
                    <option value="Кокшетау">Кокшетау</option>
                    <option value="Кызылорда">Кызылорда</option>
                    <option value="Павлодар">Павлодар</option>
                    <option value="Петропавловск">Петропавловск</option>
                    <option value="Рудный">Рудный</option>
                    <option value="Семей">Семей</option>
                    <option value="Талдыкорган">Талдыкорган</option>
                    <option value="Тараз">Тараз</option>
                    <option value="Темиртау">Темиртау</option>
                    <option value="Туркестан">Туркестан</option>
                    <option value="Уральск">Уральск</option>
                    <option value="Усть-Каменогорск">Усть-Каменогорск</option>
                    <option value="Шымкент">Шымкент</option>
                    <option value="Экибастуз">Экибастуз</option>
                    <option value="Другой">Другой</option>
                </select>
            </p>
        </div>
        <p><button type="button" class="btn_cour" id="errs_send_btn" name="next_client" onclick="WhyCallSend();" style="display: none;">Отправить</button></p>
    </div>
</div>


