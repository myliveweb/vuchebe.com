<?php
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyLink", "GetUserTypeDescription"));

class CIBlockNewPropertyLink
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "PAGE_LINK",
            "DESCRIPTION"          => "Показать на сайте",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyLink", "GetPropertyFieldHtml"),
            "ConvertToDB" 	   	   => array("CIBlockNewPropertyLink", "ConvertToDB"),
            "ConvertFromDB"		   => array("CIBlockNewPropertyLink", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        return '<input type="button" value="Перейти" onclick="window.open(\'http://vuchebe.com' .  $value['VALUE'] . '\', \'_blank\');">';
    }

	public static function ConvertToDB($arProperty, $arValue)
	{
	    return $arValue;
	}

	public static function ConvertFromDB($arProperty, $arValue)
	{
	    return $arValue;
	}

	public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
	{
	    $ret = '';
	    return $ret;
	}
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyObg", "GetUserTypeDescription"));

class CIBlockNewPropertyObg
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "OBG",
            "DESCRIPTION"          => "Общежитие",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyObg", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyObg", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyObg", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Адрес',
            'Координаты Яндекс',
            'Метро',
            'Телефон',
            'Контактное лицо',
            'Ссылка на страницу',
            'Ссылка',
            'Текст',
            'Комментарий',
            'Запасная строка',
            'Дополнительная строка',
            'Внутренний комментарий'); // 12
        $arrOut = explode('#', $value['VALUE']);
        for($n = 0; $n < 12; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '###########' && $arValue["VALUE"][0]) {
            if (!$arValue["VALUE"][11])
                $arValue["VALUE"][11] = uniqid('', true);

            $arValueOut = implode('#', $arValue["VALUE"]);

            return $arValueOut;
        }
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyDopAdress", "GetUserTypeDescription"));

class CIBlockNewPropertyDopAdress
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "DOP_A",
            "DESCRIPTION"          => "Корпуса",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyDopAdress", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyDopAdress", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyDopAdress", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название корпуса',
            'Адрес',
            'Телефон',
            'Ссылка на страницу',
            'Координаты Яндекс',
            'Метро',
            'ucheba.ru',
            'Текст',
            'Дата создания',
            'Дополнительная строка',
            'Уникальный ключ'); // 11
        $arrOut = explode('#', $value['VALUE']);
        if(sizeof($arrOut) < 11) {
            $arrOut[5] = $arrOut[4];
            $arrOut[4] = '';

            $arrOut[4] = $arrOut[3];
            $arrOut[3] = '';
        }
        for($n = 0; $n < 11; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '##########' && $arValue["VALUE"][0]) {
            if(!$arValue["VALUE"][8])
                $arValue["VALUE"][8] = date('d.m.Y H:i');

            if(!$arValue["VALUE"][10])
                $arValue["VALUE"][10] = uniqid('', true);

            $arValueOut = implode('#', $arValue["VALUE"]);

            return $arValueOut;
        }
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyFillials", "GetUserTypeDescription"));

class CIBlockNewPropertyFillials
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "FILLIALS",
            "DESCRIPTION"          => "Филиалы",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyFillials", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyFillials", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyFillials", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название филиала',
            'ID филиала',
            'Адрес',
            'Координаты Яндекс',
            'Метро',
            'Телефон',
            'Ссылка на страницу',
            'ucheba.ru',
            'Текст',
            'Запасная строка',
            'Дополнительная строка',
            'Внутренний комментарий'); // 12
        $arrOut = explode('#', $value['VALUE']);
        if(sizeof($arrOut) < 12) {
            $arrOut[7] = $arrOut[2];
            $arrOut[2] = '';
        }
        for($n = 0; $n < 12; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '###########' && $arValue["VALUE"][0]) {
            if (!$arValue["VALUE"][11])
                $arValue["VALUE"][11] = uniqid('', true);

            $arValueOut = implode('#', $arValue["VALUE"]);

            return $arValueOut;
        }
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyMoreUnits", "GetUserTypeDescription"));

class CIBlockNewPropertyMoreUnits
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "MORE_UNITS",
            "DESCRIPTION"          => "Подразделения",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyMoreUnits", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyMoreUnits", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyMoreUnits", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название подразделения',
            'ID вуза',
            'ID колледжа',
            'ID школы',
            'Адрес',
            'Координаты Яндекс',
            'Метро',
            'Телефон',
            'Ссылка на страницу',
            'Email',
            'Текст',
            'Облако тегов',
            'Тег',
            'ucheba.ru',
            'Запасная строка',
            'Дополнительная строка',
            'Внутренний комментарий'); // 17
        $arrOut = explode('#', $value['VALUE']);
        if(sizeof($arrOut) < 17) {
            $arrOut[13] = $arrOut[5];
            $arrOut[5] = '';
        }
        for($n = 0; $n < 17; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '################')
            return $arValueOut;
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyFakultets", "GetUserTypeDescription"));

class CIBlockNewPropertyFakultets
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "FAKULTETS",
            "DESCRIPTION"          => "Факультеты и институты",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyFakultets", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyFakultets", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyFakultets", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название факультета или института',
            'Адрес',
            'Координаты Яндекс',
            'Метро',
            'Телефон',
            'Email',
            'Ссылка на страницу',
            'Бюджетные места',
            'ucheba.ru',
            'Текст',
            'Комментарий',
            'Специальность',
            'Облако тегов',
            'Тег',
            'Запасная строка',
            'Дополнительная строка',
            'Внутренний комментарий'); // 17
        $arrOut = explode('#', $value['VALUE']);
        if(sizeof($arrOut) < 17) {
            $arrOut[8] = $arrOut[4];
            $arrOut[4] = '';
        }
        for($n = 0; $n < 17; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<div><input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '################')
            return $arValueOut;
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertySections", "GetUserTypeDescription"));

class CIBlockNewPropertySections
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "SECTIONS_VUZ_PROP",
            "DESCRIPTION"          => "Секции",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertySections", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertySections", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertySections", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название',
            'Телефон',
            'Контактное лицо',
            'Ссылка на страницу',
            'Комментарий',
            'Облако тегов',
            'Тег',
            'Запасная строка',
            'Дополнительная строка',
            'Внутренний комментарий'); // 10
        $arrOut = explode('#', $value['VALUE']);
        for($n = 0; $n < 10; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '#########')
            return $arValueOut;
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyOpendoor", "GetUserTypeDescription"));

class CIBlockNewPropertyOpendoor
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "OPENDOOR_VUZ",
            "DESCRIPTION"          => "Дни открытых дверей",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyOpendoor", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyOpendoor", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyOpendoor", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название',
            'Дата',
            'Время',
            'Адрес',
            'Координаты Яндекс',
            'Телефон',
            'Ссылка на страницу',
            'Комментарий',
            'Текст',
            'ucheba.ru',
            'Дата создания',
            'Дополнительная строка',
            'Уникальный ключ'); // 13
        $arrOut = explode('#', $value['VALUE']);
        if(sizeof($arrOut) < 13) {
            $arrOut[9] = $arrOut[5];
            $arrOut[5] = '';
        }
        for($n = 0; $n < 13; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '############' && $arValue["VALUE"][0]) {
            if(!$arValue["VALUE"][10])
                $arValue["VALUE"][10] = date('d.m.Y H:i');

            if(!$arValue["VALUE"][12])
                $arValue["VALUE"][12] = uniqid('', true);

            $arValueOut = implode('#', $arValue["VALUE"]);

            return $arValueOut;
        }
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyAddEvents", "GetUserTypeDescription"));

class CIBlockNewPropertyAddEvents
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "ADD_EVENTS_VUZ",
            "DESCRIPTION"          => "События",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyAddEvents", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyAddEvents", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyAddEvents", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название',
            'Дата',
            'Время',
            'Адрес',
            'Координаты Яндекс',
            'Телефон',
            'Контактное лицо',
            'Ссылка на страницу',
            'Комментарий',
            'Текст',
            'Облако тегов',
            'Тег',
            'Дата создания',
            'Дополнительная строка',
            'Уникальный ключ'); // 15
        $arrOut = explode('#', $value['VALUE']);
        for($n = 0; $n < 15; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '##############' && $arValue["VALUE"][0]) {
            if(!$arValue["VALUE"][12])
                $arValue["VALUE"][12] = date('d.m.Y H:i');

            if(!$arValue["VALUE"][14])
                $arValue["VALUE"][14] = uniqid('', true);

            $arValueOut = implode('#', $arValue["VALUE"]);

            return $arValueOut;
        }
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyPrograms", "GetUserTypeDescription"));

class CIBlockNewPropertyPrograms
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "PROGRAMS",
            "DESCRIPTION"          => "Программы обучения",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyPrograms", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyPrograms", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyPrograms", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Название программы обучения',
            'Обучение на базе (9 кл., 11 кл., Ученая степень)',
            'Очная',
            'Очно-заочная',
            'Заочная',
            'Группа выходного дня',
            'Дистанционная',
            'Бакалавр, Магистр, Специалитет, Аспирант, Доктор наук',
            'ucheba.ru',
            'Начало обучения (очная)',
            'Начало обучения (очно-заочная)',
            'Начало обучения (заочная)',
            'Начало обучения (группа выходного дня)',
            'Начало обучения (дистанционная)',
            'Срок обучения (очная)',
            'Срок обучения (очно-заочная)',
            'Срок обучения (заочная)',
            'Срок обучения (группа выходного дня)',
            'Срок обучения (дистанционная)',
            'Стоимость (очная)',
            'Стоимость (очно-заочная)',
            'Стоимость (заочная)',
            'Стоимость (группа выходного дня)',
            'Стоимость (дистанционная)',
            'Вступительные экзамены (очная)',
            'Дополнительно (очная)',
            'Вступительные экзамены (очно-заочная)',
            'Дополнительно (очно-заочная)',
            'Вступительные экзамены (заочная)',
            'Дополнительно (заочная)',
            'Вступительные экзамены (группа выходного дня)',
            'Дополнительно (группа выходного дня)',
            'Вступительные экзамены (дистанционная)',
            'Дополнительно (дистанционная)',
            'Проходной балл (очная)',
            'Проходной балл (очно-заочная)',
            'Проходной балл (заочная)',
            'Проходной балл (группа выходного дня)',
            'Проходной балл (дистанционная)',
            'Бюджетные места',
            'Комментарий',
            'Текст',
            'Факультет или подразделения',
            'Облако тегов',
            'Тег',
            'Код специальности',
            'Ссылка',
            'Внутренний комментарий'); //48
        $arrOut = explode('#', $value['VALUE']);
        if(sizeof($arrOut) < 48) {
            $arrOut[19] = $arrOut[6];
            $arrOut[6] = '';

            $arrOut[14] = $arrOut[5];
            $arrOut[5] = '';

            $arrOut[9] = $arrOut[4];
            $arrOut[4] = '';

            $arrOut[8] = $arrOut[3];
            $arrOut[3] = '';

            $arrOut[7] = $arrOut[2];
            $arrOut[2] = '';
        }
        for($n = 0; $n < 48; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '###############################################')
            return $arValueOut;
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyAddRing", "GetUserTypeDescription"));

class CIBlockNewPropertyAddRing
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "ADD_RING",
            "DESCRIPTION"          => "Расписание звонков",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyAddRing", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyAddRing", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyAddRing", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Смена или суббота',
            '1 занятие (время)',
            '2 занятие (время)',
            '3 занятие (время)',
            '4 занятие (время)',
            '5 занятие (время)',
            '6 занятие (время)',
            '7 занятие (время)',
            '8 занятие (время)',
            '9 занятие (время)',
            '10 занятие (время)',
            '11 занятие (время)',
            '12 занятие (время)'); // 13
        $arrOut = explode('#', $value['VALUE']);
        for($n = 0; $n < 13; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '############')
            return $arValueOut;
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewPropertyYandex", "GetUserTypeDescription"));

class CIBlockNewPropertyYandex
{
    public function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "S",
            "USER_TYPE"            => "YANDEX",
            "DESCRIPTION"          => "Адреса",
            "GetPropertyFieldHtml" => array("CIBlockNewPropertyYandex", "GetPropertyFieldHtml"),
            "ConvertToDB"          => array("CIBlockNewPropertyYandex", "ConvertToDB"),
            "ConvertFromDB"        => array("CIBlockNewPropertyYandex", "ConvertFromDB"),
        );
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $out = '<div style="margin-bottom: 10px;">';
        $placeholder = array('Адрес',
            'Долгота',
            'Широта',
            'Страна',
            'Oбласть',
            'Район',
            'Город',
            'Комментарий',
            'Дополнительная строка',
            'Внутренний комментарий'); // 10
        $arrOut = explode('#', $value['VALUE']);
        for($n = 0; $n < 10; $n++) {
            $input = '';
            if($arrOut[$n])
                $input = $arrOut[$n];
            $out .= '<input class="ti" style="width: 100%; margin-bottom: 1px;" type="text" name="'.$strHTMLControlName["VALUE"].'[' . $n . ']" value="' .  $input . '" placeholder="' . $placeholder[$n] . '"><br>';
        }
        $out .= '<input style="margin-top: 3px;" class="clear-block" type="button" value="Очистить">';
        $out .= '</div>';
        return $out;
    }

    public static function ConvertToDB($arProperty, $arValue)
    {
        $arValueOut = implode('#', $arValue["VALUE"]);
        if($arValueOut != '#########')
            return $arValueOut;
        else
            return false;
    }

    public static function ConvertFromDB($arProperty, $arValue)
    {
        return $arValue;
    }

    public function GetPropertyHtml($arProperty, $value, $strHTMLControlName)
    {
        $ret = '';
        return $ret;
    }
}
?>