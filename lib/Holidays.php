<?php    namespace Bitrix\DateDeliveryHelper;    class Holidays    {        public function __construct()        {            \Bitrix\Main\Loader::includeModule('iblock');        }        public function getIblockID()        {            $result = false;            $res = \CIBlock::GetList(                [],                [                    'TYPE' => 'system',                    "CODE" => 'holidays'                ],                true            );            while ($ar_res = $res->Fetch()) {                $result = $ar_res['ID'];            }            return $result;        }        public function addHoliday($dateStart, $dateFinish){            $result = 0;            $iblockID = $this->getIblockID();            if ($iblockID) {                $el = new \CIBlockElement;                $arLoadArray = Array(                    "IBLOCK_ID"      => $iblockID,                    "NAME"           => $dateStart . '-' . $dateFinish,                    "ACTIVE"         => "Y",                );                if($elementID = $el->Add($arLoadArray)) {                    \CIBlockElement::SetPropertyValuesEx($elementID, false, array('DATE_START' => $dateStart));                    \CIBlockElement::SetPropertyValuesEx($elementID, false, array('DATE_FINISH' => $dateFinish));                    $result = $elementID;                }            }            return $result;        }        public function optionForHolidays($arDeliveries){            $aTab[] = 'Добавить праздник';            $aTab[] = [                'holiday[date-start]',                'Дата начала выходного',                '01.01.2021',                ["text",10]            ];            $aTab[] = [                'holiday[date-finish]',                'Дата конца выходного',                '01.01.2021',                ["text",10]            ];//            $arDeliveries['all'] = 'Все';            $aTab[] = [                'holiday[delivery]',                'Тип доставки',                '0',                ["selectbox", $arDeliveries]            ];            return $aTab;        }        public function getListOfHolidays(){            $result = [];            $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_*");            $arFilter = Array("IBLOCK_ID"=>$this->getIblockID());            $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);            while($ob = $res->GetNextElement()){                $arFields = $ob->GetFields();                $arProps = $ob->GetProperties();                $result[$arFields['ID']] = ['START' => $arProps['DATE_START']['VALUE'], 'FINISH' => $arProps['DATE_FINISH']['VALUE']];            }            return $result;        }    }