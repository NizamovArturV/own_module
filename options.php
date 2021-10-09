<?php    use Bitrix\Main\Localization\Loc;    use    Bitrix\Main\HttpApplication;    use \Bitrix\Main\Loader;    use Bitrix\Main\Config\Option;    use Bitrix\DateDeliveryHelper;    Loc::loadMessages(__FILE__);    $request = HttpApplication::getInstance()->getContext()->getRequest();    $module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);    Loader::includeModule($module_id);    $options = new DateDeliveryHelper\OptionsHelper();    $arrDeliveries = $options->GetDeliveries();    $arrDeliveriesForNew = $arrDeliveries;    $arrDeliveriesForNew[0] = 'Не выбрано';    $arrWeek = $options->weeksDays;    $options->actionPost();    $rulesForTabs = $options->getRulesForTabs($arrDeliveries);    $holiday = new DateDeliveryHelper\Holidays();    $holidayTab = $holiday->optionForHolidays($arrDeliveries);    $holidays = $holiday->getListOfHolidays();    $aTabs = [        [            "DIV" => "SettingsDate",            "TAB" => 'Ближайшие даты',            "TITLE" => 'Настройки ближайших дат',            'OPTIONS' => $rulesForTabs['EDIT']        ],        [            "DIV" => "DatePresent",            "TAB" => 'Праздники',            "TITLE" => 'Настройки праздников',            'OPTIONS' => $holidayTab        ],        [            "DIV" => "DateAdd",            "TAB" => 'Добавить правило',            "TITLE" => 'Добавить правило для доставки',            'OPTIONS' => [                [                    'new[delivery_id]',                    'Тип доставки',                    '0',                    ["selectbox", $arrDeliveriesForNew]                ],                [                    'new[time_from]',                    'Временной промежуток (от)',                    '00:00',                    ["text", 5]                ],                [                    'new[time_to]',                    'Временной промежуток (до)',                    '11:30',                    ["text", 5]                ],                [                    'new[type_date]',                    'Дата доставки',                    'today',                    ["selectbox", [                        'today' => 'В этот же день',                        "days"  => 'Через (n) дней',                        "week" => 'Ближайший день недели'                    ]]                ],                [                    'new[count_days]',                    'Сегодня + (дней)',                    '0',                    ["text", 5]                ],                [                    'new[count_week]',                    'День недели (ближайший)',                    'today',                    ["selectbox", $arrWeek]                ],                [                    'new[time_delivery_1]',                    'Промежуток доставки',                    '-',                    ["text",11]                ],                [                    'new[time_delivery_2]',                    'Промежуток доставки (дополнительный)',                    '-',                    ["text",11]                ],                [                    'new[time_delivery_3]',                    'Промежуток доставки (дополнительный 2)',                    '-',                    ["text",11]                ],                [                    'new[priority]',                    'Приоритет промежутков',                    "N",                    ["selectbox", [                        'N' => 'Нет',                        "Y"  => 'Да',                    ]]                ],            ]        ],        [            "DIV" => "Logsis",            "TAB" => 'Logsis',            "TITLE" => 'Настройки для Logsis',            'OPTIONS' => [                [                    'logsis[time_day_in_day]',                    'Временнной промежуток для установки день в день',                    $options->getOption('time_day_in_day') ?? '19:00-22:00',                    ["text",11]                ],            ]        ],        [            "DIV" => "DeleteRules",            "TAB" => 'Удалить правило',            "TITLE" => 'Удаление правил доставки',        ],    ];    $tabControl = new CAdminTabControl(        "tabControl",        $aTabs    );    $tabControl->Begin();?><? $tabControl->BeginNextTab(); ?><form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post" enctype="multipart/form-data">    <?        echo(bitrix_sessid_post());?>    <?        __AdmSettingsDrawList($module_id, $aTabs[0]["OPTIONS"]);   ?>    <input type="submit" name="edit_delivery" value="Изменить"><? $tabControl->BeginNextTab(); ?><?php __AdmSettingsDrawList($module_id, $aTabs[1]["OPTIONS"]);?>    <input type="submit" name="holiday_add" value="Добавить">    <h1>Существующие праздники</h1>    <?foreach ($holidays as $arHoliday):?>        <p><?=$arHoliday['START']?> - <?=$arHoliday['FINISH']?></p>    <? endforeach;?><? $tabControl->BeginNextTab(); ?><?php __AdmSettingsDrawList($module_id, $aTabs[2]["OPTIONS"]);?>    <input type="submit" name="add" value="Добавить">    <? $tabControl->BeginNextTab(); ?>    <?php __AdmSettingsDrawList($module_id, $aTabs[3]["OPTIONS"]);?>    <input type="submit" name="logsis_edit" value="Изменить"><? $tabControl->BeginNextTab(); ?>    <table>        <? foreach ($rulesForTabs['DELETE'] as $idDelivery => $numbersRules):?>            <? foreach ($numbersRules as $numbersRule => $name):?>                <tr>                    <td>                        <input type="checkbox" name="delete_rule[<?=$idDelivery?>][]" value="<?=$numbersRule?>" id="<?=$idDelivery?>_<?=$numbersRule?>">                        <label for="<?=$idDelivery?>_<?=$numbersRule?>"><?=$name?></label>                    </td>                </tr>            <? endforeach;?>        <? endforeach;?>        <tr>            <td><input type="submit" name="delete" value="Удалить"></td>        </tr>    </table></form><?php $tabControl->End(); ?>