<?php

function tryToFindLang($aLanguages, $sWhere, $sDefaultLang) {

    // установить текущий язык, как язык по умолчанию
    $sLanguage = $sDefaultLang;

    // начальное значение качества
    $fBetterQuality = 0;

    // поиск по всем соответствующим параметрам
    preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?(s*;s*qs*=s*(1.0{0,3}|0.d{0,3}))?s*(,|$)/i", $sWhere, $aMatches, PREG_SET_ORDER);
    foreach ($aMatches as $aMatch) {

        // получить префикс языка
        $sPrefix = strtolower ($aMatch[1]);

        // подготовить временный язык
        $sTempLang = (empty($aMatch[3])) ? $sPrefix : $sPrefix . '-' . strtolower ($aMatch[3]);

        // получить качество языка (если есть)
        $fQuality = (empty($aMatch[5])) ? 1.0 : floatval($aMatch[5]);

        if ($sTempLang) {

            // определение предпочтительного языка
            if ($fQuality > $fBetterQuality && in_array($sTempLang, array_keys($aLanguages))) {

                // установить временный язык, как язык по умолчанию и обновить значения качества
                $sLanguage = $sTempLang;
                $fBetterQuality = $fQuality;
            } elseif (($fQuality*0.9) > $fBetterQuality && in_array($sPrefix, array_keys($aLanguages))) {

                // установить язык по умолчанию, как значение префикса и обновить значения качества
                $sLanguage = $sPrefix;
                $fBetterQuality = $fQuality * 0.9;
            }
        }
    }
    return $sLanguage;
}

$sLanguage = tryToFindLang($aLanguages, $_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en');

$_SESSION['lang'] = $sLanguage;
$_COOKIE['lang'] = $sLanguage;

?>
