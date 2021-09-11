<?php
/********************************************************************************
 *                        .::ALGOL-TEAM PRODUCTIONS::.                           *
 *    .::Author © 2021 | algol.team.uz@gmail.com | github.com/algol-team::.      *
 *********************************************************************************
 *  Description: This is class for PHP.                                          *
 *  Thanks to specialist: All PHP masters.                                       *
 ********************************************************************************/

namespace AlgolTeam;

use Exception;
use MysqliDb;
use Telegram;

// CONST GLOBAL
define("CH_AND", "&");
define("CH_COMMA", ",");
define("CH_EQUAL", "=");
define("CH_FREE", "");
define("CH_PLUS", "+");
define("CH_MINUS", "-");
define("CH_NET", "#");
define("CH_NULL", "0");
define("CH_NUMBER", "№");
define("CH_POINT", ".");
define("CH_POINT_TWO_VER", ":");
define("CH_POINT_COMMA", ";");
define("CH_POINT_THREE", "...");
define("CH_SPACE", " ");
define("CH_SPEC", "|");
define("CH_BOTTOM_LINE", "_");
define("CH_BRACE_BEGIN", "(");
define("CH_BRACE_END", ")");
define("CH_BRACE_FIGURE_BEGIN", "{");
define("CH_BRACE_FIGURE_END", "}");
define("CH_BRACE_SQR_BEGIN", "[");
define("CH_BRACE_SQR_END", "]");
define("CH_TAG_BEGIN", "<");
define("CH_TAG_END", ">");
define("CH_PERCENT", "%");
define("CH_MAIL", "@");
define("CH_FLAG", "~");
define("CH_STAR", "*");
define("CH_MONEY", "$");
define("CH_INTERJECTION", "!");
define("CH_QUESTION", "?");
define("CH_ID", "ID");
define("CH_OK", "OK");
define("CH_NEW_LINE", "\n");
define("CH_PATH", "\\");
define("CH_ANTI_PATH", "/");

define("CH_TRIM", "CH_TRIM");

// Const Get Type Check
define("GTC_Number", "GTC_Number");
define("GTC_DateTime", "GTC_DateTime");
define("GTC_TimeOnly", "GTC_TimeOnly");
define("GTC_MultiArray", "GTC_MultiArray");

/**
 * ClassDefaultOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class DefaultOf {

    /**
     * Returns the re-check the value
     * <hr>
     * <b>Example:</b>
     * * (null, "hi") - "hi"
     *
     * @param mixed $AValue
     * @param mixed $ADefault error correction value
     * @param null $ATrue
     * @return mixed
     * @link umid_soft@mail.ru
     */
    public static function ValueCheck($AValue, $ADefault, $ATrue = null) {
        if (isset($AValue)) {
            if (is_null($ATrue)) return $AValue; else return $ATrue;
        } else return $ADefault;
    }

    /**
     * Returns the string value to its original type value
     * <hr>
     * <b>Example:</b>
     * * ("false") - false
     * * ("0012") - 12
     * * ("1.34") - 1.34
     * * (["false", "0012", ["1.34"]]) - [false, 12, [1.34]]
     *
     * @param $AValue
     * @param int $ADecimal
     * @param string $AThousand
     * @return array|bool|float|int|string
     * @link umid_soft@mail.ru
     */
    public static function ValueFromString($AValue, $ADecimal = 2, $AThousand = CH_FREE) {
        $FResult = $AValue;
        if (is_array($FResult)) {
            foreach ($FResult as $FKey => $FValue) {
                if (is_array($FValue)) $FResult[$FKey] = self::ValueFromString($FValue, $ADecimal, $AThousand); else $FResult[$FKey] = self::ValueFromStringExecute1($FValue, $ADecimal, $AThousand);
            }
        } else $FResult = self::ValueFromStringExecute1($FResult, $ADecimal, $AThousand);
        return $FResult;
    }

    /**
     * @param $AValue
     * @param int $ADecimal
     * @param string $AThousand
     * @return bool|float|int|string
     */
    private static function ValueFromStringExecute1($AValue, $ADecimal = 2, $AThousand = CH_FREE) {
        if (is_string($AValue)) {
            $FValue = StrOf::Replace($AValue, CH_COMMA, CH_POINT);
            if (self::TypeCheck($AValue)) return intval($AValue);
            elseif (self::TypeCheck($AValue, FILTER_VALIDATE_FLOAT)) return (float)number_format(floatval($AValue), ArrayOf::First($ADecimal), ArrayOf::Length($ADecimal) > 1 ? ArrayOf::Value($ADecimal, 2) : CH_POINT, $AThousand);
            elseif (self::TypeCheck($FValue, FILTER_VALIDATE_FLOAT)) return (float)number_format(floatval($FValue), ArrayOf::First($ADecimal), ArrayOf::Length($ADecimal) > 1 ? ArrayOf::Value($ADecimal, 2) : CH_POINT, $AThousand);
            elseif (StrOf::Same($AValue, "false")) return false;
            elseif (StrOf::Same($AValue, "true")) return true;
            else return $AValue;
        } elseif (self::TypeCheck($AValue, FILTER_VALIDATE_FLOAT)) return (float)number_format($AValue, ArrayOf::First($ADecimal), ArrayOf::Length($ADecimal) > 1 ? ArrayOf::Value($ADecimal, 2) : CH_POINT, $AThousand); else return $AValue;
    }

    /**
     * Returns the boolean value interval accepted
     * <hr>
     * <b>Example:</b>
     * * (2, 1, 3) - true
     * * (1.1, 0.5, 1.5) - true
     * * ("hi", ["ok", "hi"], null) - true
     * * (2, [1, 2, 5], 3) - true
     * * (3, [1, 2, 5], 3) - false
     * * (1, 2, [1, 2, 5]) - false
     * * ("a", 1, 5) - false
     * * ("2", 1, null) - true
     * * (6, null, 5) - false
     *
     * @param $AValue
     * @param $AMin
     * @param $AMax
     * @return bool
     * @link umid_soft@mail.ru
     */
    public static function IntervalCheck($AValue, $AMin, $AMax) {
        $FResult = true;
        $FValue = ArrayOf::First($AValue);
        $FMin = ArrayOf::First($AMin);
        $FMax = ArrayOf::First($AMax);
        if (!is_null($AMin) and !self::TypeCheck($AMin, GTC_Number)) {
            $FResult = StrOf::Found($AMin, $FValue, 1, SF_SameText);
            $FMin = null;
        }
        if ($FResult and !is_null($AMax) and !self::TypeCheck($AMax, GTC_Number)) {
            $FResult = StrOf::Found($AMax, $FValue, 1, SF_SameText);
            $FMax = null;
        }
        if ($FResult and (self::TypeCheck($FMin, GTC_Number) or self::TypeCheck($FMax, GTC_Number))) {
            if (self::TypeCheck($FValue, GTC_Number)) {
                if (is_null($FMin)) $FResult = ($FValue <= $FMax);
                elseif (is_null($FMax)) $FResult = ($FMin <= $FValue);
                else $FResult = (($FMin <= $FMax) and ($FMin <= $FValue) and ($FValue <= $FMax));
            } else $FResult = false;
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param int $AType
     * @return bool
     */
    public static function TypeCheck($AValue, $AType = FILTER_VALIDATE_INT) {
        if ($AType == GTC_MultiArray) {
            if (is_array($AValue)) {
                foreach ($AValue as $FValue) if (is_array($FValue)) return true;
            }
            return false;
        } elseif ($AType == GTC_DateTime) {
            if (ArrayOf::Length($AValue) > 1) {
                $FFormats = ArrayOf::Of(GAO_Cut, $AValue, 2, ArrayOf::Length($AValue) - 1);
                $FValue = trim(ArrayOf::First($AValue));
                if (is_array($FFormats)) {
                    foreach ($FFormats as $FFormat) if (date_parse_from_format($FFormat, $FValue)["error_count"] == 0) return true;
                    return false;
                } else return date_parse_from_format($FFormats, $FValue)["error_count"] == 0;
            } else return date_parse(trim(ArrayOf::First($AValue)))["error_count"] == 0;
        } elseif ($AType == GTC_TimeOnly) return self::TypeCheck([$AValue, "H:i:s", "H:i"], GTC_DateTime); else {
            if ($AType == GTC_Number) $FType = FILTER_VALIDATE_INT|FILTER_VALIDATE_FLOAT; else $FType = $AType;
            if (filter_var($AValue, $FType) === false) return false; else return true;
        }
    }

}

// Const String Found
define("SF_SameText", "SF_SameText");
define("SF_FirstText", "SF_FirstText");
define("SF_GetCount", "SF_GetCount");
define("SF_GetValue", "SF_GetValue");
define("SF_GetKey", "SF_GetKey");
define("SF_GetKeySame", "SF_GetKeySame");
define("SF_OnlyKey", "SF_OnlyKey");
define("SF_OnlyKeySame", "SF_OnlyKeySame");
define("SF_WithKey", "SF_WithKey");
define("SF_WithKeySame", "SF_WithKeySame");

// Const String Replace
define("SR_ArrayKeys", "SR_ArrayKeys");

// Const Format From User Data
define("FFUD_FullName", "FFUD_FullName");
define("FFUD_Login", "FFUD_Login");
define("FFUD_Password", "FFUD_Password");

/**
 * ClassStrOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class StrOf {

    /**
     * @param $AValue
     * @param false $ATrim
     * @return false|int
     */
    public static function Length($AValue, $ATrim = false) {
        try {
            if (is_null($AValue)) return 0;
            elseif (is_array($AValue)) return ArrayOf::Length($AValue, true);
            elseif (is_object($AValue)) return ArrayOf::Length((array)$AValue, true);
            elseif ($ATrim) return mb_strlen(trim($AValue), "UTF-8"); else return mb_strlen($AValue, "UTF-8");
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * @param $AValue
     * @param string $ADefault
     * @return string
     */
    public static function From($AValue, $ADefault = CH_FREE) {
        return strval(DefaultOf::ValueCheck(ArrayOf::First($AValue), $ADefault));
    }

    /**
     * @param $AValue
     * @param $ASubValue
     * @param int $AStart
     * @param bool $ARepeat
     * @param bool $AWord
     * @return false|int
     */
    public static function Pos($AValue, $ASubValue, $AStart = 1, $ARepeat = false, $AWord = false) {
        $FResult = 0;
        if ((self::Length($AValue) > 0) and (self::Length($ASubValue) > 0) and DefaultOf::IntervalCheck($AStart, 1, self::Length($AValue))) {
            $FResult = self::PosExecute1($AValue, $ASubValue, $AStart - 1, $AWord);
            if (($FResult == 0) and $ARepeat and ($AStart > 1)) $FResult = self::PosExecute1($AValue, $ASubValue, 0, $AWord);
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $ASubValue
     * @param int $AStart
     * @param bool $AWord
     * @return false|int
     */
    private static function PosExecute1($AValue, $ASubValue, $AStart = 0, $AWord = false) {
        $FResult = mb_stripos($AValue, $ASubValue, $AStart, "UTF-8");
        if ($FResult === false) $FResult = 0; else {
            $FResult += 1;
            if ($AWord) {
                $FStart = $FResult;
                $FFinish = $FResult + self::Length($ASubValue) - 1;
                $FPattern = "/^[a-zA-Z\p{Cyrillic}]$/u";
                if ((($FStart > 1) and preg_match($FPattern, $AValue[$FStart - 2])) or (($FFinish < self::Length($AValue)) and preg_match($FPattern, $AValue[$FFinish]))) $FResult = 0;
            }
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $ASubValue
     * @param int $AStart
     * @param bool $ARepeat
     * @return false|int
     */
    public static function PosWord($AValue, $ASubValue, $AStart = 1, $ARepeat = false) {
        return self::Pos($AValue, $ASubValue, $AStart, $ARepeat, true);
    }

    /**
     * @param $AValue
     * @param $ASubValue
     * @param int $AStart
     * @param null $AParam
     * @param bool $AFullSearch
     * @param bool $AWord
     * @return bool|int
     */
    public static function Found($AValue, $ASubValue, $AStart = 1, $AParam = null, $AFullSearch = false, $AWord = false) {
        $FResult = 0;
        $FText = null;
        if ((self::Length($AValue) > 0) and (self::Length($ASubValue) > 0)) {
            $FSubValue = $ASubValue;
            if (ArrayOf::Length($FSubValue) > 0) {
                $FSubValue = array_diff(array_unique($FSubValue), [CH_FREE]);
                if ($AFullSearch) {
                    foreach ($FSubValue as $FValue) {
                        $FSubResult = 0;
                        self::FoundExecute1($AValue, $FValue, $AStart, $AParam, $AWord, $FSubResult, $FText);
                        if ($FSubResult == 0) break; else $FResult += $FSubResult;
                    }
                    if (($FResult < ArrayOf::Length($FSubValue)) and ($AParam <> SF_GetCount)) $FResult = 0;
                } else self::FoundExecute1($AValue, $FSubValue, $AStart, $AParam, $AWord, $FResult, $FText);
            } else self::FoundExecute1($AValue, $FSubValue, $AStart, $AParam, $AWord, $FResult, $FText);
        }
        if ($AParam == SF_GetCount) return $FResult;
        elseif (in_array($AParam, [SF_GetValue, SF_GetKey, SF_GetKeySame])) return $FText;
        else return $FResult > 0;
    }

    /**
     * @param $ASource
     * @param $ASearch
     * @param $APos
     * @param $AParam
     * @param $AWord
     * @param $AResult
     * @param $AText
     */
    private static function FoundExecute1($ASource, $ASearch, $APos, $AParam, $AWord, &$AResult, &$AText) {
        $FSource = $ASource;
        if (is_array($FSource)) {
            if (in_array($AParam, [SF_OnlyKey, SF_OnlyKeySame])) $FSource = array_keys($FSource);
            elseif (in_array($AParam, [SF_WithKey, SF_WithKeySame])) $FSource = array_merge(array_keys($FSource), array_values($FSource));
            foreach ($FSource as $FKey => $FValue) {
                if (is_array($FValue)) self::FoundExecute1($FValue, $ASearch, $APos, $AParam, $AWord, $AResult, $AText); else self::FoundExecute2($FValue, $ASearch, $APos, $AParam, $AWord, $AResult);
                if (($AParam <> SF_GetCount) and ($AResult > 0)) {
                    if ($AParam == SF_GetValue) $AText = $FValue;
                    elseif (in_array($AParam, [SF_GetKey, SF_GetKeySame])) $AText = $FKey;
                    break;
                }
            }
        } else {
            self::FoundExecute2($FSource, $ASearch, $APos, $AParam, $AWord, $AResult);
            if (($AParam == SF_GetValue) and ($AResult > 0)) $AText = $FSource;
        }
    }

    /**
     * @param $ASource
     * @param $ASearch
     * @param $APos
     * @param $AParam
     * @param $AWord
     * @param $AResult
     */
    private static function FoundExecute2($ASource, $ASearch, $APos, $AParam, $AWord, &$AResult) {
        if (is_array($ASearch)) {
            foreach ($ASearch as $FValue) {
                if ($AParam == SF_GetCount) self::FoundExecute3($ASource, $FValue, $APos, $AWord, $AResult); else {
                    if (in_array($AParam, [SF_SameText, SF_OnlyKeySame, SF_WithKeySame, SF_GetKeySame])) $AResult = self::Same($ASource, $FValue) ? 1 : 0;
                    elseif ($AParam == SF_FirstText) $AResult = (self::Pos($ASource, $FValue) == 1) ? 1 : 0; else $AResult = (self::Pos($ASource, $FValue, $APos, false, $AWord) > 0) ? 1 : 0;
                    if ($AResult > 0) break;
                }
            }
        } else {
            if ($AParam == SF_GetCount) self::FoundExecute3($ASource, $ASearch, $APos, $AWord, $AResult); else {
                if (in_array($AParam, [SF_SameText, SF_OnlyKeySame, SF_WithKeySame, SF_GetKeySame])) $AResult = self::Same($ASource, $ASearch) ? 1 : 0;
                elseif ($AParam == SF_FirstText) $AResult = (self::Pos($ASource, $ASearch) == 1) ? 1 : 0; else $AResult = (self::Pos($ASource, $ASearch, $APos, false, $AWord) > 0) ? 1 : 0;
            }
        }
    }

    /**
     * @param $ASource
     * @param $ASearch
     * @param $APos
     * @param $AWord
     * @param $AResult
     */
    private static function FoundExecute3($ASource, $ASearch, $APos, $AWord, &$AResult) {
        if ((self::Length($ASource) > 0) and (self::Length($ASearch) > 0)) {
            $FSource = $ASource;
            $FLenSearch = self::Length($ASearch);
            $FPos = self::Pos($FSource, $ASearch, $APos, false, $AWord);
            while ($FPos > 0) {
                $AResult += 1;
                $FSource = self::Copy($FSource, $FPos + $FLenSearch, self::Length($FSource) - $FPos - $FLenSearch + 1);
                $FPos = self::Pos($FSource, $ASearch, 1, false, $AWord);
            }
        }
    }

    /**
     * @param $AValue1
     * @param $AValue2
     * @param int $APercent
     * @return bool
     */
    public static function Same($AValue1, $AValue2, int $APercent = 100) {
        similar_text(self::CharCase($AValue1, MB_CASE_LOWER), self::CharCase($AValue2, MB_CASE_LOWER), $FPercent);
        return ($FPercent >= $APercent);
    }

    /**
     * @param $AValue
     * @param $ANumber
     * @param string $AInterval
     * @return mixed|null
     */
    public static function Cut($AValue, $ANumber, string $AInterval = CH_SPEC) {
        if (ArrayOf::FromString($AValue, $AInterval, $FResult) > 0) $FResult = ArrayOf::Value($FResult, $ANumber); else $FResult = null;
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AStart
     * @param $ALength
     * @param false $ARight
     * @param null $AContinueFormat
     * @return string
     */
    public static function Copy($AValue, $AStart, $ALength, $ARight = false, $AContinueFormat = null) {
        $FLength = self::Length($AValue);
        if (($FLength > 0) and DefaultOf::IntervalCheck($AStart, 1, $FLength) and ($ALength > 0)) {
            if ($ARight) $FStart = $AStart * (-1); else $FStart = $AStart - 1;
            if (($FLength > $ALength) and (self::Length($AContinueFormat) > 0)) {
                $FLength = $ALength - self::Length($AContinueFormat);
                $FContinueFormat = $AContinueFormat;
            } else {
                $FLength = $ALength;
                $FContinueFormat = CH_FREE;
            }
            return mb_substr($AValue, $FStart, $FLength, "UTF-8") . $FContinueFormat;
        } else return CH_FREE;
    }

    /**
     * @param $AValue
     * @param $ASearch
     * @param $AReplace
     * @param null $AParam
     * @return array|string|null
     */
    public static function Replace($AValue, $ASearch, $AReplace, $AParam = null) {
        if (($AParam == SR_ArrayKeys) and is_array($AValue)) {
            $FResult = self::ReplaceExecute3($AValue,  $ASearch, $AReplace);
        } elseif (is_string($AValue) and is_null($AReplace)) {
            if (is_array($ASearch)) $FResult = vsprintf($AValue, $ASearch); else $FResult = sprintf($AValue, $ASearch);
        } else {
            $FResult = $AValue;
            if (is_array($FResult)) {
                foreach ($FResult as $FKey => $FValue) {
                    if (is_array($FValue)) {
                        $FReplaceValue = self::Replace($FValue, $ASearch, $AReplace);
                        if (is_null($FReplaceValue)) unset($FResult[$FKey]); else $FResult[$FKey] = $FReplaceValue;
                    } else {
                        $FReplaceValue = self::ReplaceExecute1($FValue, $ASearch, $AReplace);
                        if (is_null($FReplaceValue)) unset($FResult[$FKey]); else $FResult[$FKey] = $FReplaceValue;
                    }
                }
            } else $FResult = self::ReplaceExecute1($AValue, $ASearch, $AReplace);
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $ASearch
     * @param $AReplace
     * @return string|null
     */
    private static function ReplaceExecute1($AValue, $ASearch, $AReplace) {
        $FResult = self::From($AValue);
        if (is_array($ASearch)) {
            foreach ($ASearch as $FKey => $FValue) {
                if (is_array($AReplace)) {
                    if (array_key_exists($FKey, $AReplace)) self::ReplaceExecute2($FValue, $AReplace[$FKey], $FResult);
                } else self::ReplaceExecute2($FValue, $AReplace, $FResult);
                if (is_null($FResult)) break;
            }
        } else self::ReplaceExecute2($ASearch, $AReplace, $FResult);
        return $FResult;
    }

    /**
     * @param $ASearch
     * @param $AReplace
     * @param $AResult
     */
    private static function ReplaceExecute2($ASearch, $AReplace, &$AResult) {
        $FReplace = ArrayOf::First($AReplace);
        if (self::Length($ASearch) > 0) {
            if (self::Same($ASearch, $AResult)) $AResult = $FReplace;
            elseif ($ASearch === CH_NEW_LINE) $AResult = preg_replace("/[\n\r]/", $FReplace, $AResult);
            elseif ($ASearch === CH_TRIM) $AResult = trim($AResult); else {
                $FLenSearch = self::Length($ASearch);
                $FLenReplace = self::Length($FReplace);
                $FPos = self::Pos($AResult, $ASearch);
                while ($FPos > 0) {
                    $AResult = self::Copy($AResult, 1, $FPos - 1) . $FReplace . self::Copy($AResult, $FPos + $FLenSearch, self::Length($AResult) - $FPos - $FLenSearch + 1);
                    $FPos = self::Pos($AResult, $ASearch, $FPos + $FLenReplace);
                }
            }
        }
        if (is_null($FReplace) and (self::Length($AResult) == 0)) $AResult = null;
    }

    /**
     * @param $AValue
     * @param $ASearch
     * @param $AReplace
     * @return array
     */
    private static function ReplaceExecute3($AValue, $ASearch, $AReplace) {
        $FResult = $AValue;
        if (is_array($ASearch) and is_array($AReplace)) {
            foreach ($ASearch as $FKey => $FValue) {
                if (!is_array($FValue) and array_key_exists($FValue, $FResult) and array_key_exists($FKey, $AReplace) and ($FValue <> $AReplace[$FKey])) {
                    $FResult[$AReplace[$FKey]] = $FResult[$FValue];
                    unset($FResult[$FValue]);
                }
            }
        } else {
            if (is_null($ASearch)) {
                if (is_array($AReplace)) {
                    foreach ($AReplace as $FKey => $FValue) {
                        if (!is_array($FValue) and ($FKey <> $FValue) and array_key_exists($FKey, $FResult)) {
                            $FResult[$FValue] = $FResult[$FKey];
                            unset($FResult[$FKey]);
                        }
                    }
                } elseif (DefaultOf::TypeCheck($AReplace)) $FResult = array_combine(range($AReplace, $AReplace + ArrayOf::Length($FResult) - 1), array_values($FResult));
            } elseif (!is_array($ASearch) and !is_array($AReplace)) {
                $FKey = self::From($ASearch);
                $FValue = self::From($AReplace);
                if ((self::Length($FKey) > 0) and (self::Length($FValue) > 0) and ($FKey <> $FValue) and array_key_exists($FKey, $FResult)) {
                    $FResult[$FValue] = $FResult[$FKey];
                    unset($FResult[$FKey]);
                }
            }
        }

        foreach ($FResult as $FKey => $FValue) {
            if (is_array($FValue)) $FResult[$FKey] = self::ReplaceExecute3($FValue, $ASearch, $AReplace);
        }

        return $FResult;
    }

    /**
     * @param $ASource
     * @param $AAppend
     * @param string $ASeparator
     * @param false $AIfExs
     * @param false $AInvert
     * @return array|mixed|string|null
     */
    public static function Add($ASource, $AAppend, $ASeparator = ", ", $AIfExs = false, $AInvert = false) {
        if (is_array($AAppend)) {
            $FResult = $ASource;
            foreach ($AAppend as $FValue) $FResult = self::Add($FResult, $FValue, $ASeparator, $AIfExs, $AInvert);
            return $FResult;
        } elseif (self::Length($AAppend, true) == 0) return $ASource;
        elseif (self::Length($ASource, true) == 0) return $AAppend;
        elseif (!$AIfExs or (self::Pos($ASource, $AAppend) == 0)) {
            if ($AInvert) return ($AAppend . $ASeparator . $ASource); else return ($ASource . $ASeparator . $AAppend);
        } else return $ASource;
    }

    /**
     * @param $AValue
     * @param int $AParam
     * @return string
     */
    public static function CharCase($AValue, $AParam = MB_CASE_TITLE) {
        if (is_null($AParam)) return self::From($AValue); else return mb_convert_case($AValue, $AParam, "UTF-8");
    }

    /**
     * @param $AValue
     * @param string $AParam
     * @param null $AResult
     * @return bool
     */
    public static function FormatFromUserData($AValue, $AParam = FFUD_FullName, &$AResult = null) {
        $AResult = null;
        $FResult = false;
        switch ($AParam) {
            case FFUD_FullName:
                if ((ArrayOf::FromString($AValue, CH_SPACE, $FSubResult) == 2) or (ArrayOf::FromString($AValue, CH_BOTTOM_LINE, $FSubResult) == 2)) {
                    $FPatternLatin = "/^[a-zA-Z'`]{3,15}$/";
                    $FPatternCyril = "/^[\p{Cyrillic}]{3,15}$/u";
                    $FFirstName = trim(ArrayOf::Value($FSubResult));
                    $FLastName = trim(ArrayOf::Value($FSubResult, 2));
                    if ((preg_match($FPatternLatin, $FFirstName) and preg_match($FPatternLatin, $FLastName)) or (preg_match($FPatternCyril, $FFirstName) and preg_match($FPatternCyril, $FLastName))) {
                        $FFirstName = self::CharCase($FFirstName);
                        $FLastName = self::CharCase($FLastName);
                        $AResult = $FFirstName . CH_SPACE . $FLastName;
                        $FResult = true;
                    }
                }
                break;
            case FFUD_Password:
                $FPatternPassword = "/^[a-zA-Z\p{Cyrillic}0-9]{2,20}$/u";
                if (preg_match($FPatternPassword, $AValue)) {
                    $AResult = $AValue;
                    $FResult = true;
                }
                break;
        }
        return $FResult;
    }

    /**
     * @param $AFileName
     * @param $AValue
     * @param bool $ANewLine
     * @return bool
     */
    public static function ToFile($AFileName, $AValue, $ANewLine = false) {
        $FResult = false;
        if ((self::Length($AFileName) > 0) and (self::Length($AValue) > 0)) {
            try {
                $FFile = fopen($AFileName, 'a+');
                if ($FFile) {
                    if ($ANewLine) fwrite($FFile, CH_NEW_LINE);
                    fwrite($FFile, $AValue);
                    fclose($FFile);
                    $FResult = true;
                }
            }
            catch (Exception $Err) {
                echo "Message: " .$Err->getMessage();
            }
        }
        return $FResult;
    }

}

/**
 * ClassValueOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class ValueOf {

    /**
     * @param $AValue
     * @param int $ASecond
     * @param int $AMinute
     * @param int $AHour
     * @param int $ADay
     * @param int $AWeek
     * @param int $AMonth
     * @param int $AYear
     * @param string $AFormat
     * @return string
     */
    public static function DateTimeModify($AValue, $ASecond = 0, $AMinute = 0, $AHour = 0, $ADay = 0, $AWeek = 0, $AMonth = 0, $AYear = 0, $AFormat = "d.m.Y H:i:s") {
        $FValue = date_create($AValue);
        if ($ASecond <> 0) $FValue->modify(sprintf("%d second", $ASecond));
        if ($AMinute <> 0) $FValue->modify(sprintf("%d second", $ASecond * $AMinute));
        if ($AHour <> 0) $FValue->modify(sprintf("%d hour", $AHour));
        if ($ADay <> 0) $FValue->modify(sprintf("%d day", $ADay));
        if ($AWeek <> 0) $FValue->modify(sprintf("%d week", $AWeek));
        if ($AMonth <> 0) $FValue->modify(sprintf("%d month", $AMonth));
        if ($AYear <> 0) $FValue->modify(sprintf("%d year", $AYear));
        return $FValue->format($AFormat);
    }

    /**
     * @param $AStartTime
     * @param $AFinishTime
     * @param string $AFormat
     * @param null $ADefault
     * @return mixed|null
     */
    public static function DateTimePeriod($AStartTime, $AFinishTime, $AFormat = "[Seconds]", $ADefault = null) {
        $FResult = $ADefault;
        $FStartTime = $AStartTime;
        $FFinishTime = $AFinishTime;
        if (DefaultOf::TypeCheck($FStartTime, GTC_DateTime) and DefaultOf::TypeCheck($FFinishTime, GTC_DateTime)) {
            if (DefaultOf::TypeCheck($FStartTime, GTC_TimeOnly) and !DefaultOf::TypeCheck($FFinishTime, GTC_TimeOnly)) $FFinishTime = self::DateTimeConvertFormat($FFinishTime, "H:i:s");
            elseif (!DefaultOf::TypeCheck($FStartTime, GTC_TimeOnly) and DefaultOf::TypeCheck($FFinishTime, GTC_TimeOnly)) $FStartTime = self::DateTimeConvertFormat($FStartTime, "H:i:s");
            if (DefaultOf::TypeCheck($FStartTime, GTC_TimeOnly) and DefaultOf::TypeCheck($FFinishTime, GTC_TimeOnly) and (strtotime($FStartTime) > strtotime($FFinishTime))) {
                $FStartTime = self::DateTimeConvertFormat($FStartTime, "01.01.2000 H:i:s");
                $FFinishTime = self::DateTimeConvertFormat($FFinishTime, "02.01.2000 H:i:s");
            }
            if (strtotime($FStartTime) < strtotime($FFinishTime)) {
                $FStartTime = date_create($FStartTime);
                $FInterval = $FStartTime->diff(date_create($FFinishTime));
                if (is_array($AFormat)) {
                    $FFormat = null;
                    if (!isset($AFormat["Year"])) $AFormat["Year"] = "year";
                    if (!isset($AFormat["Month"])) $AFormat["Month"] = "month";
                    if (!isset($AFormat["Day"])) $AFormat["Day"] = "day";
                    if (!isset($AFormat["Hour"])) $AFormat["Hour"] = "hour";
                    if (!isset($AFormat["Minute"])) $AFormat["Minute"] = "minute";
                    if (!isset($AFormat["Second"])) $AFormat["Second"] = "second";
                    if ($FInterval->y > 0) {
                        $FFormat = "[Year]" . CH_SPACE . $AFormat["Year"];
                        if ($FInterval->m > 0) $FFormat = StrOf::Add($FFormat, "[Month]" . CH_SPACE . $AFormat["Month"]);
                        if ($FInterval->d > 0) $FFormat = StrOf::Add($FFormat, "[Day]" . CH_SPACE . $AFormat["Day"]);
                    } elseif ($FInterval->m > 0) {
                        $FFormat = "[Month]" . CH_SPACE . $AFormat["Month"];
                        if ($FInterval->d > 0) $FFormat = StrOf::Add($FFormat, "[Day]" . CH_SPACE . $AFormat["Day"]);
                        if ($FInterval->h > 0) $FFormat = StrOf::Add($FFormat, "[Hour]" . CH_SPACE . $AFormat["Hour"]);
                    } elseif ($FInterval->d > 0) {
                        $FFormat = "[Day]" . CH_SPACE . $AFormat["Day"];
                        if ($FInterval->h > 0) $FFormat = StrOf::Add($FFormat, "[Hour]" . CH_SPACE . $AFormat["Hour"]);
                        if ($FInterval->i > 0) $FFormat = StrOf::Add($FFormat, "[Minute]" . CH_SPACE . $AFormat["Minute"]);
                    } elseif ($FInterval->h > 0) {
                        $FFormat = "[Hour]" . CH_SPACE . $AFormat["Hour"];
                        if ($FInterval->i > 0) $FFormat = StrOf::Add($FFormat, "[Minute]" . CH_SPACE . $AFormat["Minute"]);
                    } elseif ($FInterval->i > 0) {
                        $FFormat = "[Minute]" . CH_SPACE . $AFormat["Minute"];
                        if ($FInterval->s > 0) $FFormat = StrOf::Add($FFormat, "[Second]" . CH_SPACE . $AFormat["Second"]);
                    } elseif ($FInterval->s > 0) $FFormat = "[Second]" . CH_SPACE . $AFormat["Second"];
                    if (isset($FFormat)) self::DateTimePeriodExecute1($FInterval, $FFormat, $FResult);
                } else self::DateTimePeriodExecute1($FInterval, $AFormat, $FResult);
            }
        }
        return $FResult;
    }

    /**
     * @param $AInterval
     * @param $AFormat
     * @param $AResult
     */
    private static function DateTimePeriodExecute1($AInterval, $AFormat, &$AResult) {
        $AResult = StrOf::Replace($AFormat,
            ["[Years]", "[Year]",
                "[Months]", "[Month]",
                "[Days]", "[Day]",
                "[Hours]", "[Hour]",
                "[Minutes]", "[Minute]",
                "[Seconds]", "[Second]"],
            [$AInterval->y, $AInterval->y,
                $AInterval->m, $AInterval->m,
                $AInterval->days, $AInterval->d,
                $AInterval->h + $AInterval->days * 24, $AInterval->h,
                $AInterval->i + $AInterval->h * 60 + $AInterval->days * 1440, $AInterval->i,
                $AInterval->s + $AInterval->i * 60 + $AInterval->h * 3600 + $AInterval->days * 86400, $AInterval->s]);
    }

    /**
     * @param $AValue
     * @param $AStartTime
     * @param $AFinishTime
     * @param null $AWeekDay
     * @return bool|int
     */
    public static function DateTimeIntervalCheck($AValue, $AStartTime, $AFinishTime, $AWeekDay = null) {
        if (is_null($AStartTime) and is_null($AFinishTime)) return true; else {
            $FTimeStyle = (DefaultOf::TypeCheck($AStartTime, GTC_TimeOnly) or DefaultOf::TypeCheck($AFinishTime, GTC_TimeOnly));
            if ($FTimeStyle) $FValue = self::DateTimeConvertFormat($AValue, "H:i:s"); else $FValue = $AValue;
            if (is_null($AStartTime)) $FResult = (strtotime($FValue) <= strtotime($AFinishTime));
            elseif (is_null($AFinishTime)) $FResult = (strtotime($AStartTime) <= strtotime($FValue));
            elseif ($FTimeStyle and (strtotime($AStartTime) > strtotime($AFinishTime))) $FResult = (DefaultOf::IntervalCheck(strtotime($FValue), strtotime($AStartTime), strtotime("23:59:59")) or DefaultOf::IntervalCheck(strtotime($FValue), strtotime("00:00:00"), strtotime($AFinishTime)));
            else $FResult = DefaultOf::IntervalCheck(strtotime($FValue), strtotime($AStartTime), strtotime($AFinishTime));
            if ($FResult and !is_null($AWeekDay)) $FResult = StrOf::Pos($AWeekDay, self::DateTimeConvertFormat($AValue, "N")) > 0;
            return $FResult;
        }
    }

    /**
     * @param $AValue
     * @param string $AFormat
     * @param null $ADefault
     * @return false|string|null
     */
    public static function DateTimeConvertFormat($AValue, $AFormat = "d.m.Y H:i:s", $ADefault = null) {
        if (DefaultOf::TypeCheck($AValue, GTC_DateTime)) return date($AFormat, strtotime($AValue)); else return $ADefault;
    }

    /**
     * @param $AMin
     * @param $AMax
     * @return int
     */
    public static function Random($AMin, $AMax): int {
        if ($AMin <= $AMax) return mt_rand($AMin, $AMax); else return $AMin;
    }

    /**
     * @param $AMin
     * @param $AMax
     * @param int $ADefault
     * @return float|int
     */
    public static function Percent($AMin, $AMax, int $ADefault = 100) {
        if ($AMax <> 0) return round(($AMin * 100) / $AMax); else return $ADefault;
    }

    /**
     * @param $AValue1
     * @param $AValue2
     */
    public static function Swap(&$AValue1, &$AValue2) {
        $FTemp = $AValue1;
        $AValue1 = $AValue2;
        $AValue2 = $FTemp;
    }

    /**
     * @param $ALatFrom
     * @param $ALonFrom
     * @param $ALatTo
     * @param $ALonTo
     * @param string $AUnit
     * @param int $ADecimal
     * @return array|bool|float|int|string
     */
    public static function Distance($ALatFrom, $ALonFrom, $ALatTo, $ALonTo, $AUnit = "km", $ADecimal = 1) {
        $FResult = 0;
        if (($ALatFrom <> $ALatTo) or ($ALonFrom <> $ALonTo)) {
            // Get param and calculate
            $FTheta = $ALonFrom - $ALonTo;
            $FDist = sin(deg2rad($ALatFrom)) * sin(deg2rad($ALatTo)) + cos(deg2rad($ALatFrom)) * cos(deg2rad($ALatTo)) * cos(deg2rad($FTheta));
            $FDist = acos($FDist);
            $FDist = rad2deg($FDist);
            $FMiles = $FDist * 60 * 1.1515;
            // Get result
            if (StrOf::Same($AUnit, "km")) $FResult = ($FMiles * 1.609344); // kilometer
            elseif (StrOf::Same($AUnit, "m")) $FResult = ($FMiles  * 1609.344); // meter
            elseif (StrOf::Same($AUnit, "mi")) $FResult = $FMiles; // miles
            elseif (StrOf::Same($AUnit, "nmi")) $FResult = $FMiles / 1.150779448; // nautical miles
            elseif (StrOf::Same($AUnit, "in")) $FResult = ($FMiles * 63360); // inches
            elseif (StrOf::Same($AUnit, "cm")) $FResult = ($FMiles * 160934.4); // centimeter
            elseif (StrOf::Same($AUnit, "yd")) $FResult = ($FMiles * 1760); // yard
            elseif (StrOf::Same($AUnit, "ft")) $FResult = ($FMiles * 5280); // feet
            else $FResult = $FMiles; // miles
        }
        if ($ADecimal <> 0) $FResult = DefaultOf::ValueFromString($FResult, $ADecimal);
        return $FResult;
    }

}

// Const Get Array Of
define("GAO_Combine", "GAO_Combine");
define("GAO_Merge", "GAO_Merge");
define("GAO_Column", "GAO_Column");
define("GAO_Cut", "GAO_Cut");
define("GAO_GetKeyAll", "GAO_GetKeyAll");
define("GAO_Chunk", "GAO_Chunk");

/**
 * ClassArrayOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class ArrayOf {

    /**
     * @param $AValue
     * @param bool $ASubLength
     * @return int
     */
    public static function Length($AValue, $ASubLength = false) {
        $FResult = 0;
        if (is_array($AValue)) {
            if ($ASubLength) self::LengthExecute1($AValue, $FResult); else $FResult = count($AValue);
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AResult
     */
    private static function LengthExecute1($AValue, &$AResult) {
        foreach ($AValue as $FValue) {
            if (is_array($FValue)) self::LengthExecute1($FValue, $AResult);
            elseif (!is_null($FValue)) $AResult += 1;
        }
    }

    /**
     * @param $AValue
     * @param int $ANumber
     * @param bool $ASubValue
     * @return mixed|null
     */
    public static function Value($AValue, $ANumber = 1, $ASubValue = false) {
        $FResult = null;
        if (is_array($AValue)) {
            $FCount = self::Length($AValue);
            if (($FCount > 0) and DefaultOf::IntervalCheck($ANumber, 1, $FCount)) {
                if (array_key_exists($ANumber - 1, $AValue)) $FResult = $AValue[$ANumber - 1]; else {
                    $FIndex = 0;
                    foreach ($AValue as $FValue) {
                        $FIndex += 1;
                        if ($ANumber == $FIndex) {
                            $FResult = $FValue;
                            break;
                        }
                    }
                }
                if ($ASubValue and is_array($FResult)) $FResult = self::Value($FResult, $ANumber, $ASubValue);
            }
        } elseif ($ANumber == 1) $FResult = $AValue;
        return $FResult;
    }

    /**
     * @param $AValue
     * @return mixed|null
     */
    public static function First($AValue) {
        return self::Value($AValue, 1, true);
    }

    /**
     * @param $AFileName
     * @param $AResult
     * @param string $AKey
     * @param null $AInterval
     * @return int
     */
    public static function FromFile($AFileName, &$AResult, $AKey = CH_EQUAL, $AInterval = null) {
        $AResult = [];
        if (file_exists($AFileName)) {
            $FFile = fopen($AFileName, "r");
            if ($FFile) {
                if (is_null($AInterval)) {
                    while (!feof($FFile)) {
                        self::FromFileExecute1(fgets($FFile), $AKey, $AResult);
                    }
                } else {
                    $FValue = null;
                    while (!feof($FFile)) {
                        $FChar = fgetc($FFile);
                        if (StrOf::Found($AInterval, $FChar, 1, SF_SameText)) {
                            self::FromFileExecute1($FValue, $AKey, $AResult);
                            $FValue = null;
                        } else $FValue .= $FChar;
                    }
                    self::FromFileExecute1($FValue, $AKey, $AResult);
                }
                fclose($FFile);
            }
        }
        return self::Length($AResult);
    }

    /**
     * @param $AText
     * @param $AKey
     * @param $AResult
     */
    private static function FromFileExecute1($AText, $AKey, &$AResult) {
        $FText = trim(StrOf::From($AText));
        if (StrOf::Length($FText) > 0) {
            if (!is_null($AKey) and (self::FromString($FText, $AKey, $FResult, 2) > 1)) $AResult[self::Value($FResult)] = self::Value($FResult, 2); else array_push($AResult, $FText);
        }
    }

    /**
     * Returns the array value from key and value
     * <hr>
     * <b>Example:</b>
     * * Combine: ([1, 2, 3], ["one", "two", [1, 2, 3]]) - [1 => "one", 2 => "two", 3 => [1, 2, 3]]
     * * Combine: ("text", ["hi"], [2, 3]) - [0 => 2, 1 => 3, "text" => [0 => "hi"]]
     * * Combine: ([1, "two" => 2, 3], ["two" => 5]) - [2 => 5]
     *
     * @param $AParam
     * @param array $AValues
     * @return array|mixed
     */
    public static function Of($AParam, ...$AValues) {
        $FResult = [];
        $FCountArg = func_num_args() - 1;
        switch ($AParam) {
            case GAO_Combine: // Array combine
                if ($FCountArg > 1) {
                    // Get param
                    if ($FCountArg > 2) {
                        $FResult = func_get_arg(1);
                        $FNames = func_get_arg(2);
                        $FValues = func_get_arg(3);
                    } else {
                        $FNames = func_get_arg(1);
                        $FValues = func_get_arg(2);
                    }
                    // Execute
                    if (self::Length($FNames) > 0) {
                        if (!is_array($FValues)) $FValues = [$FValues];
                        foreach ($FNames as $FKey => $FValue) {
                            if (array_key_exists($FKey, $FValues)) $FResult[self::First($FValue)] = $FValues[$FKey];
                        }
                    } else $FResult[self::First($FNames)] = $FValues;
                }
                break;
            case GAO_Merge:
                foreach ($AValues as $FValue) {
                    if (StrOf::Length($FValue) > 0) {
                        if (is_array($FValue)) $FResult = array_merge($FResult, $FValue); else array_push($FResult, $FValue);
                    }
                }
                break;
            case GAO_Column:
                if ($FCountArg > 1) {
                    // Get param
                    $FValues = func_get_arg(1);
                    $FNames = func_get_arg(2);
                    if (is_array($FValues) and !is_null($FNames)) {
                        $FResult = array_column($FValues, $FNames);
                    }
                }
                break;
            case GAO_Cut:
                if ($FCountArg > 1) {
                    // Get param
                    $FParam1 = func_get_arg(1);
                    $FParam2 = func_get_arg(2);
                    if ($FCountArg > 2) $FParam3 = func_get_arg(3); else $FParam3 = null;
                    if ($FCountArg > 3) $FParam4 = func_get_arg(4); else $FParam4 = false;
                    // Execute
                    if (is_array($FParam1) and DefaultOf::TypeCheck($FParam2)) {
                        if ($FParam2 >= 0) $FParam2 -= 1;
                        $FResult = array_slice($FParam1, $FParam2, $FParam3, $FParam4);
                        if ($FParam3 == 1) $FResult = self::Value($FResult);
                    }
                }
                break;
            case GAO_GetKeyAll:
                foreach ($AValues as $FValue) {
                    if (self::Length($FValue) > 0) {
                        foreach ($FValue as $FSubKey => $FSubValue) {
                            if (DefaultOf::TypeCheck($FSubKey)) $FParam1 = $FSubValue; else $FParam1 = $FSubKey;
                            if (StrOf::Length($FParam1) > 0) {
                                if (is_array($FParam1)) $FResult = array_merge($FResult, $FParam1); else array_push($FResult, $FParam1);
                            }
                        }
                    } elseif (StrOf::Length($FValue) > 0) array_push($FResult, $FValue);
                }
                break;
            case GAO_Chunk:
                if ($FCountArg > 1) {
                    // Get param
                    $FParam1 = func_get_arg(1);
                    $FParam2 = func_get_arg(2);
                    if ($FCountArg > 2) $FParam3 = func_get_arg(3); else $FParam3 = false;
                    // Execute
                    if (is_array($FParam1) and ($FParam2 > 0)) $FResult = array_chunk($FParam1, $FParam2);
                    if ($FParam3 and (self::Length($FResult) > 0)) {
                        $FLast = $FResult[self::Length($FResult) - 1];
                        if (self::Length($FLast) <> $FParam2) {
                            unset($FResult[self::Length($FResult) - 1]);
                            $FResult = array_merge($FResult, $FLast);
                        }
                    }
                }
                break;
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param string $AInterval
     * @param null $AResult
     * @param null $ALimit
     * @param null $AKeys
     * @return int
     */
    public static function FromString($AValue, $AInterval = CH_SPEC, &$AResult = null, $ALimit = null, $AKeys = null) {
        $AResult = [];
        $FResult = 0;
        $FSubInterval = null;
        if (isset($AValue)) {
            if (is_array($AInterval)) {
                foreach ($AInterval as $FKey => $FValue) {
                    if (DefaultOf::TypeCheck($FKey)) {
                        $FInterval = $FValue;
                        $FSubInterval = null;
                    } else {
                        $FInterval = $FKey;
                        $FSubInterval = $FValue;
                    }
                    if (StrOf::Pos($AValue, $FInterval) > 0) $FResult = self::FromString($AValue, $FInterval, $AResult, $ALimit, $AKeys);
                    if ($FResult > 0) break;
                }
            } elseif (StrOf::Length($AValue) > 0) {
                $FValue = StrOf::From($AValue);
                if (is_null($AInterval)) {
                    $FLen = StrOf::Length($FValue);
                    for ($InX = 1; $InX <= $FLen; $InX++) {
                        $AResult[$InX] = StrOf::Copy($FValue, $InX, 1);
                    }
                } else {
                    if (is_null($ALimit)) $AResult = explode($AInterval, $FValue); else $AResult = explode($AInterval, $FValue, $ALimit);
                }
                if (!is_null($AKeys)) $AResult = StrOf::Replace($AResult, null, $AKeys, SR_ArrayKeys);
                $FResult = self::Length($AResult);
            }
        }
        if (($FResult > 0) and !is_null($FSubInterval)) {
            foreach ($AResult as $FKey => $FValue) {
                if (self::FromString($FValue, $FSubInterval, $ASubResult, $ALimit, $AKeys) > 0) $AResult[$FKey] = $ASubResult;
            }
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AInterval
     * @param null $ALimit
     * @param null $AKeys
     * @return array
     */
    public static function FromStringWithArray($AValue, $AInterval = CH_SPEC, $ALimit = null, $AKeys = null) {
        $FResult = [];
        self::FromString($AValue, $AInterval, $FResult, $ALimit, $AKeys);
        return $FResult;
    }

    /**
     * @param $AValue
     * @param false $AParse
     * @return array|null
     */
    public static function FromJSON($AValue, $AParse = false) {
        if (!StrOf::Found($AValue, [CH_BRACE_FIGURE_BEGIN, CH_BRACE_FIGURE_END], 1, null, true)) $FResult = [];
        elseif ($AParse === false) {
            if (is_array($AValue)) {
                $FResult = [];
                foreach ($AValue as $FKey => $FValue) {
                    $FResult[$FKey] = self::FromJSON($FValue);
                }
            } else $FResult = json_decode(StrOf::Replace($AValue, CH_PATH, CH_PATH . CH_PATH), JSON_OBJECT_AS_ARRAY);
        } else {
            $FResult = [];
            $FParseKeys = self::Of(GAO_GetKeyAll, $AParse);
            if (is_array($AValue)) {
                foreach ($AValue as $FKey => $FValue) {
                    if (StrOf::Found($FParseKeys, $FKey, 1, SF_SameText)) {
                        if (isset($AParse[$FKey])) $FResult = array_merge($FResult, self::FromJSON($FValue, $AParse[$FKey])); else $FResult = array_merge($FResult, self::FromJSON($FValue, null));
                    } elseif (is_array($FValue)) $FResult[$FKey] = self::FromJSON($FValue, $AParse); else $FResult[$FKey] = $FValue;
                }
            } else {
                $FParseJSON = self::FromJSON($AValue);
                if ($FParseJSON) {
                    if (self::Length($FParseKeys) == 0) $FResult = $FParseJSON; else {
                        foreach ($FParseJSON as $FKey => $FValue) {
                            if (StrOf::Found($FParseKeys, $FKey, 1, SF_SameText)) {
                                if (isset($AParse[$FKey])) $FResult[self::First($AParse[$FKey])] = $FValue; else $FResult[$FKey] = $FValue;
                            }
                        }
                    }
                }
            }
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param bool $ASubJSON
     * @return mixed
     */
    public static function ToJSON($AValue, $ASubJSON = false) {
        $FResult = $AValue;
        if ($ASubJSON) {
            if (self::Length($FResult) > 0) {
                foreach($FResult as $FKey => $FValue) {
                    if (is_array($FValue)) {
                        $FEncode = json_encode($FValue, JSON_UNESCAPED_UNICODE);
                        if ($FEncode) $FResult[$FKey] = StrOf::Replace($FEncode, [CH_PATH . CH_PATH, CH_PATH . CH_ANTI_PATH], [CH_PATH, CH_ANTI_PATH]); else $FResult[$FKey] = null;
                    }
                }
            }
        } else {
            $FEncode = json_encode($FResult, JSON_UNESCAPED_UNICODE);
            if ($FEncode) $FResult = StrOf::Replace($FEncode, [CH_PATH . CH_PATH, CH_PATH . CH_ANTI_PATH], [CH_PATH, CH_ANTI_PATH]); else $FResult = null;
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AInterval
     * @param bool $ASubArray
     * @return array|mixed|string|null
     */
    public static function ToString($AValue, $AInterval, $ASubArray = true) {
        if (self::Length($AValue) > 0) {
            $FResult = null;
            foreach ($AValue as $FValue) {
                if (is_array($FValue)) {
                    if ($ASubArray) $FSubResult = self::ToString($FValue, $AInterval); else $FSubResult = null;
                } else $FSubResult = $FValue;
                if ($FSubResult === $AInterval) $FResult .= $AInterval; else $FResult = StrOf::Add($FResult, $FSubResult, $AInterval);
            }
        } else $FResult = StrOf::From($AValue);
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AResult
     * @return array|int
     */
    public static function FromStringWithKey($AValue, &$AResult) {
        $AResult = [];
        If ((self::FromString($AValue, CH_NET, $FSubResult) > 0) and (self::FromString(self::Value($FSubResult), CH_SPEC, $FKeys) > 0) and (self::FromString(self::Value($FSubResult, 2), CH_SPEC, $FValues) > 0) and (self::Length($FKeys) == self::Length($FValues))) return $AResult = array_combine($FKeys, $FValues);
        return self::Length($AResult);
    }

    /**
     * @param $AValue
     * @return string|null
     */
    public static function ToStringWithKey($AValue) {
        if (self::Length($AValue) > 0) return implode(CH_SPEC, array_keys($AValue)) . CH_NET . implode(CH_SPEC, array_values($AValue)); else return null;
    }

    /**
     * @param $AValue
     * @param $AResult
     * @param null $AMin
     * @param null $AMax
     * @param int $AType
     * @param int $AStep
     * @param bool $ASort
     * @return int
     */
    public static function FromFormatNumber($AValue, &$AResult, $AMin = null, $AMax = null, $AType = FILTER_VALIDATE_INT, $AStep = 1, $ASort = true) {
        $AResult = [];
        if (self::FromString($AValue, CH_COMMA, $FResult) > 0) {
            foreach ($FResult as $FValue) {
                $FValue = trim(self::First($FValue));
                if (DefaultOf::TypeCheck($FValue, $AType)) {
                    $FValue = DefaultOf::ValueFromString($FValue);
                    if (DefaultOf::IntervalCheck($FValue, $AMin, $AMax)) array_push($AResult, $FValue);
                } elseif (self::FromString($FValue, CH_MINUS, $FSubResult) == 2) {
                    $FValue1 = self::Value($FSubResult);
                    $FValue2 = self::Value($FSubResult, 2);
                    if (DefaultOf::TypeCheck($FValue1, $AType) and DefaultOf::TypeCheck($FValue2, $AType) and ($FValue1 <= $FValue2)) {
                        foreach (range($FValue1, $FValue2, $AStep) as $FSubValue) {
                            if (DefaultOf::IntervalCheck($FSubValue, $AMin, $AMax)) array_push($AResult, $FSubValue);
                        }
                    }
                }
            }
            if ($ASort) {
                $AResult = array_unique($AResult);
                sort($AResult);
            }
        }
        return self::Length($AResult);
    }

    /**
     * @param $AValue
     * @param $AKeys
     * @param $AResult
     * @param null $AMax
     * @return int
     */
    public static function FromFormatKeys($AValue, $AKeys, &$AResult, $AMax = null) {
        $AResult = [];
        if ((StrOf::Length($AValue) > 0) and (self::Length($AKeys) > 0) and StrOf::Found($AValue, $AKeys)) {
            $FText = $AValue;
            if (StrOf::Found($FText, [1, 2, 3, 4, 5, 6, 7, 8, 9])) {
                $FKey = null;
                $FTextLen = StrOf::Length($FText);
                for ($InX = 0; $InX < $FTextLen; $InX++) {
                    if (StrOf::Found($FText[$InX], $AKeys)) {
                        if (DefaultOf::TypeCheck($FKey) and (is_null($AMax) or DefaultOf::IntervalCheck($FKey, 1, $AMax))) $AResult[$FKey] = $FText[$InX];
                        $FKey = null;
                    } elseif (StrOf::Found($FText[$InX], [1, 2, 3, 4, 5, 6, 7, 8, 9, 0])) $FKey = $FKey .$FText[$InX];
                }
                ksort($AResult);
            } else {
                $FTextLen = floor(StrOf::Length($FText) / 2) - 1;
                $FCharList = count_chars($FText, 1);
                arsort($FCharList);
                foreach ($FCharList as $FKey => $FValue) {
                    if ($FValue >= $FTextLen) {
                        if (!StrOf::Found(chr($FKey), $AKeys)) $FText = StrOf::Replace($FText, chr($FKey), CH_FREE);
                    } else break;
                }
                $FTextLen = StrOf::Length($FText);
                if (!is_null($AMax)) $FTextLen = min($FTextLen, $AMax);
                for ($InX = 0; $InX < $FTextLen; $InX++) {
                    if (StrOf::Found($FText[$InX], $AKeys)) $AResult[$InX + 1] = $FText[$InX];
                }
            }
        }
        return self::Length($AResult);
    }

    /**
     * @param $ASource
     * @param $APath
     * @param $AResult
     * @param string $ASeparator
     * @param string $AInterval
     * @return int
     */
    public static function FromFormatPath($ASource, $APath, &$AResult, $ASeparator = CH_PATH, $AInterval = CH_POINT_COMMA) {
        $AResult = [];
        if (ArrayOf::Length($ASource) > 0) $FResult = $ASource;
        elseif (self::FromString($ASource, $AInterval, $FResult) == 0) $FResult = null;
        if (ArrayOf::Length($FResult) > 0) {
            $FIndex = self::FromString($APath, $ASeparator) + 1;
            foreach ($FResult as $FValue) {
                if (((StrOf::Length($APath) == 0) or (StrOf::Pos($FValue, $APath . $ASeparator) == 1)) and (self::FromString($FValue, $ASeparator, $FSubResult) >= $FIndex)) array_push($AResult, self::Value($FSubResult, $FIndex));
            }
            if (self::Length($AResult) > 0) $AResult = array_diff(array_unique($AResult), [CH_FREE]);
        }
        return self::Length($AResult);
    }

    /**
     * @param $AValues
     * @param $AKeys
     * @return array
     */
    public static function Combine($AValues, $AKeys) {
        $FResult = [];
        if ((self::Length($AValues) > 0) and (self::Length($AKeys) > 0)) {
            foreach ($AValues as $FKey => $FValue) {
                if (DefaultOf::TypeCheck($FKey) and is_array($FValue)) $FResult[$FKey] = self::Combine($FValue, $AKeys); else {
                    foreach ($AKeys as $FSubKey => $FSubValue) {
                        if (is_array($FSubValue)) {
                            if (StrOf::Found($FSubValue, $FKey, 1, SF_WithKeySame)) {
                                if (isset($FSubValue[$FKey])) $FResult[$FSubKey][$FSubValue[$FKey]] = $FValue; else $FResult[$FSubKey][$FKey] = $FValue;
                            } else $FResult[$FKey] = $FValue;
                        }
                    }
                }
            }
        }
        return $FResult;
    }

}

/**
 * ClassDateTimeOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class DateTimeOf {

    /**
     * @param string $AFormat
     * @param string $ATimeZone
     * @return false|string
     */
    public static function LocalDateTime($AFormat = "d.m.Y H:i:s", $ATimeZone = "Asia/Tashkent") {
        date_default_timezone_set($ATimeZone);
        return date($AFormat);
    }

    public static function TimeAgo($ADate, $AFormat = "[Time] [Time_Text] ago", $ADefault = "long ago", $ATimeText = ["second", "minute", "hour", "day", "month", "year"]) {
        if (isset($ADate)) {
            $FLength = array("60", "60", "24", "30", "12", "10");
            $FTimestamp = strtotime($ADate);
            $FCurrentTime = time();
            if($FCurrentTime >= $FTimestamp) {
                $FDiff     = time()- $FTimestamp;
                for($FIndex = 0; $FDiff >= $FLength[$FIndex] && $FIndex < ArrayOf::Length($FLength) - 1; $FIndex++) {
                    $FDiff = $FDiff / $FLength[$FIndex];
                }
                $FDiff = round($FDiff);
                return StrOf::Replace($AFormat, ["[Time]", "[Time_Text]"], [$FDiff, $ATimeText[$FIndex]]);
            }
        }
        return $ADefault;
    }

}

// Const Get File Info
define("GFI_Curl", "GFI_Curl");

/**
 * ClassSystemOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class SystemOf {

    /**
     * @param $AFileName
     * @param int $AOptions
     * @return array|mixed|string|string[]|null
     */
    public static function FileInfo($AFileName, $AOptions = PATHINFO_FILENAME) {
        if ($AOptions == GFI_Curl) return curl_file_create($AFileName); else return pathinfo($AFileName, $AOptions);
    }

    public static function Values($ADefault = 'UNKNOWN') {
        $FResult = [];
        $FValue = null;
        // Get client ip adress
        $FItems = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        foreach($FItems as $FItem) {
            if (!empty($_SERVER[$FItem]) && filter_var($_SERVER[$FItem], FILTER_VALIDATE_IP)) {
                $FValue['IPv4 Address'] = $_SERVER[$FItem];
                break;
            }
        }
        // Get config data
        foreach (ArrayOf::FromStringWithArray(shell_exec('ipconfig/all'), 'Description') as $FItem) {
            if (StrOf::Found($FItem, ['Physical Address', 'IPv4 Address'], 1, null, true)) {
                foreach (ArrayOf::FromStringWithArray($FItem, [CH_NEW_LINE => ':']) as $FItem2) {
                    if (ArrayOf::Length($FItem2) > 0) {
                        $FTitle = StrOf::Replace($FItem2[0], [CH_POINT, CH_TRIM], CH_FREE);
                        if ($FTitle === CH_FREE) {
                            $FValue['Description'] = $FItem2[1];
                        } elseif (StrOf::Same($FTitle, 'Physical Address')) {
                            $FValue['Physical Address'] = $FItem2[1];
                        } elseif (StrOf::Same($FTitle, 'IPv4 Address') and !isset($FValue['IPv4 Address'])) {
                            $FValue['IPv4 Address'] = StrOf::Cut($FItem2[1], 1, CH_BRACE_BEGIN);
                        }
                    }
                }
                break;
            }
        }
        if (isset($FValue)) {
            $FValue['Host name'] = gethostname();
            $FResult['Network'] = $FValue;
        }
        // Get system data
        $FItems = ['HDD Serial' => 'DISKDRIVE', 'Bios serial' => 'bios'];
        foreach ($FItems as $FKey => $FItem) {
            $FValue = shell_exec('wmic ' . $FItem . ' GET SerialNumber 2>&1');
            if (isset($FValue)) $FResult['System'][$FKey] = StrOf::Replace($FValue, 'SerialNumber', CH_FREE);
        }
        // Clear trimming
        if (ArrayOf::Length($FResult) > 0) return StrOf::Replace($FResult, [CH_NEW_LINE, CH_TRIM], CH_FREE); else return $ADefault;
    }
}

// Const Language
define("LNG_Execute", "LNGExecute");
define("LNG_Execute2", "LNGExecute2");
define("LNG_Execute3", "LNGExecute3");
define("LNG_Execute4", "LNGExecute4");
define("LNG_Execute5", "LNGExecute5");
define("LNG_Skip", "LNGSkip");

/**
 * ClassLanguage
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class LanguageOf {

    private $FFileName;
    private $FLogFile;
    private $FData;
    private $FLog;

    /**
     * ClassLanguage constructor.
     * @param $AFileName
     * @param null $ALogFile
     */
    public function __construct($AFileName, $ALogFile = null) {
        $this->FFileName = $AFileName;
        $this->FLogFile = $ALogFile;
        $this->CreateData();
        $this->CreateLog();
    }

    /**
     * @return array|null
     */
    private function CreateData() {
        $this->FData = [];
        if (ArrayOf::FromFile($this->FFileName, $FResult) > 0) {
            $this->FData = $FResult;
        }
        return $this->FData;
    }

    /**
     * @return array|null
     */
    private function CreateLog() {
        $this->FLog = [];
        if (ArrayOf::FromFile($this->FLogFile, $FResult, null) > 0) {
            $this->FLog = $FResult;
        }
        return $this->FLog;
    }

    /**
     * @param $AText
     * @return bool
     */
    private function WriteLog($AText) {
        $FResult = false;
        $FText = trim(StrOf::From($AText));
        if (!StrOf::Found($this->FLog, $FText, 1, SF_SameText) and StrOf::ToFile($this->FLogFile, $FText, ArrayOf::Length($this->FLog) > 0)) {
            array_push($this->FLog, $FText);
            $FResult = true;
        }
        return $FResult;
    }

    /**
     * @param $AText
     * @param array $AValues
     * @return string
     */
    private function GetValue($AText, $AValues = []) {
        if (ArrayOf::Length($AValues) > 0) {
            try {
                $FResult = vsprintf($AText, $AValues);
            }
            catch (Exception $Err) {
                $FResult = $AText;
                echo "Message: " .$Err->getMessage();
            }
        } elseif (!is_array($AValues) and !is_null($AValues)) {
            try {
                $FResult = sprintf($AText, $AValues);
            }
            catch (Exception $Err) {
                $FResult = $AText;
                echo "Message: " .$Err->getMessage();
            }
        } else $FResult = $AText;
        return StrOf::Replace($FResult, CH_SPEC, CH_NEW_LINE);
    }

    /**
     * @param $AText
     * @param array $AValues
     * @return string
     */
    private function Translate($AText, $AValues = []) {
        if (!is_null($this->FLogFile)) $this->WriteLog($AText);
        if (isset($this->FData[$AText])) $FResult = $this->GetValue($this->FData[$AText], $AValues); else $FResult = $this->GetValue($AText, $AValues);
        return $FResult;
    }

    /**
     * @param $AText
     * @param array $AValues
     * @param string $AInterval
     * @param null $AIntervalReplace
     * @return array|string|null
     */
    public function Execute($AText, $AValues = [], $AInterval = CH_SPEC, $AIntervalReplace = null) {
        if (is_null($AText)) $FResult = null;
        elseif ($AText === CH_NEW_LINE) $FResult = $AText;
        elseif (is_string($AText)) {
            $FIndex = 0;
            $FValuesCount = ArrayOf::Length($AValues);
            if (ArrayOf::FromString($AText, $AInterval, $FResult) > 1) {
                foreach ($FResult as $FKey => $FValue) {
                    if ($FValuesCount > 0) {
                        $FStrValuesCount = StrOf::Found($FValue, ["%s", "%d"], 1, SF_GetCount);
                        if (($FValuesCount == 0) or ($FStrValuesCount == $FValuesCount)) $FResult[$FKey] = $this->Translate($FValue, $AValues); else {
                            $FResult[$FKey] = $this->Translate($FValue, array_slice($AValues, $FIndex, $FStrValuesCount));
                            $FIndex += $FStrValuesCount;
                        }
                    } else $FResult[$FKey] = $this->Translate($FValue, $AValues);
                }
                $FResult = implode($AInterval, $FResult);
                if (!is_null($AIntervalReplace)) $FResult = StrOf::Replace($FResult, $AInterval, $AIntervalReplace);
            } else $FResult = $this->Translate($AText, $AValues);
        } elseif (is_array($AText)) {
            $FResult = $AText;
            foreach ($FResult as $FKey => $FValue) {
                if (StrOf::Same($FKey, LNG_Execute, 95)) $FResult[$FKey] = $this->Execute(ArrayOf::Value($FValue), DefaultOf::ValueCheck(ArrayOf::Value($FValue, 2), $AValues), DefaultOf::ValueCheck(ArrayOf::Value($FValue, 3), $AInterval), DefaultOf::ValueCheck(ArrayOf::Value($FValue, 4), $AIntervalReplace));
                elseif ($FKey !== LNG_Skip) $FResult[$FKey] = $this->Execute($FValue, $AValues, $AInterval, $AIntervalReplace);
            }
        } else $FResult = $this->Translate($AText, $AValues);
        return $FResult;
    }

    /**
     * @param $AText
     * @param array $AValues
     * @param string $AInterval
     * @param string $AFormat
     * @return string|null
     */
    public function Format($AText, $AValues = [], $AInterval = CH_SPEC, $AFormat = CH_NEW_LINE) {
        return ArrayOf::ToString($this->Execute($AText, $AValues, $AInterval, $AFormat), $AFormat);
    }
}

/**
 * ClassMySQLDB
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class MysqlDbOf extends MysqliDb {

    /**
     * ClassMySQLDB constructor.
     * @param null $AHost
     * @param null $AUserName
     * @param null $APassword
     * @param null $ADatabase
     * @param int $APort
     */
    public function __construct($AHost = null, $AUserName = null, $APassword = null, $ADatabase = null, $APort = 3306) {
        parent::__construct($AHost, $AUserName, $APassword, $ADatabase, $APort);
    }

    /**
     * @param array $ATableNames
     * @return bool|null
     */
    public function ConnectOf($ATableNames = []) {
        $FResult = false;
        try {
            $FResult = (($this->connect() == null) and ((ArrayOf::Length($ATableNames) == 0) or ($this->tableExists($ATableNames))));
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $AValues
     * @param $AWhere
     * @param string $ACond
     * @param string $ADefaultProp
     * @return bool|ClassMySQLDB
     */
    public function joinOf($AValues, $AWhere, $ACond = "AND", $ADefaultProp = "ID") {
        $FResult = false;
        try {
            if (ArrayOf::Length($AValues) > 0) {
                foreach ($AValues as $FValue) {
                    if (ArrayOf::FromString($FValue, CH_SPEC, $FSubResult) == 3) {
                        $FResult = $this->join(ArrayOf::Value($FSubResult), ArrayOf::Value($FSubResult, 2), ArrayOf::Value($FSubResult, 3));
                    }
                }
            }
            if ($FResult and (ArrayOf::Length($AWhere) > 0)) {
                foreach ($AWhere as $WhereKey => $WhereValue) {
                    if (!DefaultOf::TypeCheck($WhereKey)) {
                        if (ArrayOf::Length($WhereValue) > 0) {
                            foreach ($WhereValue as $FKey => $FValue) {
                                if (is_array($FValue)) {
                                    foreach ($FValue as $FSubKey => $FSubValue) {
                                        if ((StrOf::Length($FSubKey) > 0) and (StrOf::Length($FSubValue) > 0)) $FResult = $this->joinWhere($WhereKey, $FKey, $FSubValue, $FSubKey, $ACond);
                                    }
                                } elseif (is_null($FValue)) {
                                    $FResult = $this->joinWhere($WhereKey, $FKey, $FValue, "IS", $ACond);
                                } elseif (!DefaultOf::TypeCheck($FKey)) $FResult = $this->joinWhere($WhereKey, $FKey, $FValue, CH_EQUAL, $ACond);
                                elseif (StrOf::Length($FValue) > 0) $FResult = $this->joinWhere($WhereKey, $FValue);
                            }
                        } elseif (DefaultOf::TypeCheck($WhereValue) or ($ADefaultProp <> "ID")) $FResult = $this->joinWhere($WhereKey, $ADefaultProp, $WhereValue, CH_EQUAL, $ACond);
                        elseif (StrOf::Length($WhereValue) > 0) $FResult = $this->joinWhere($WhereKey, $WhereValue);
                    }
                }
            }
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $AValues
     * @param string $ADefaultProp
     * @return mixed
     */
    private function whereSubParse($AValues, $ADefaultProp = "ID") {
        $FResult = $AValues;
        if (ArrayOf::Length($FResult) > 0) {
            foreach ($FResult as $FKey => $FValue) {
                if (StrOf::Found(["AND", "OR"], $FKey, 1, SF_SameText) and (ArrayOf::Length($FValue) > 0)) {
                    $FSubQuery = $this->subQuery();
                    $this->whereSub($FSubQuery, $FValue, $FKey, $ADefaultProp);
                    $FSubQuery->_buildCondition(CH_FREE, $FSubQuery->_where);
                    $FResult[$FKey] = CH_BRACE_BEGIN . StrOf::Replace(trim($FSubQuery->replacePlaceHolders($FSubQuery->_query, $FSubQuery->_bindParams)), CH_SPACE . CH_SPACE, CH_SPACE) . CH_BRACE_END;
                    $FSubQuery->reset();
                    unset($FSubQuery);
                }
            }
        }
        return $FResult;
    }

    /**
     * @param $ASubQuery
     * @param $AValues
     * @param string $ACond
     * @param string $ADefaultProp
     * @return void|null
     */
    private function whereSub($ASubQuery, $AValues, $ACond = "AND", $ADefaultProp = "ID") {
        if (isset($ASubQuery) and (StrOf::Length($AValues) > 0)) {
            if (is_array($AValues)) {
                foreach ($AValues as $FKey => $FValue) {
                    if (is_array($FValue)) {
                        foreach ($FValue as $FSubKey => $FSubValue) {
                            if ((StrOf::Length($FSubKey) > 0) and (StrOf::Length($FSubValue) > 0)) $ASubQuery->where($FKey, $FSubValue, $FSubKey, $ACond);
                        }
                    } elseif (is_null($FValue)) {
                        $ASubQuery->where($FKey, $FValue, "IS", $ACond);
                    } elseif (!DefaultOf::TypeCheck($FKey)) {
                        if (StrOf::Found(["AND", "OR"], $FKey, 1, SF_SameText)) $ASubQuery->where($FValue); else $ASubQuery->where($FKey, $FValue, CH_EQUAL, $ACond);
                    } elseif (StrOf::Length($FValue) > 0) $ASubQuery->where($FValue);
                }
            } elseif (StrOf::Found(["AND", "OR"], $ADefaultProp, 1, SF_SameText)) $ASubQuery->where($AValues);
            elseif (DefaultOf::TypeCheck($AValues) or ($ADefaultProp <> "ID")) $ASubQuery->where($ADefaultProp, $AValues, CH_EQUAL, $ACond);
            elseif (StrOf::Length($AValues) > 0) $ASubQuery->where($AValues);
        }
        return $ASubQuery;
    }

    /**
     * @param $AValues
     * @param string $ACond
     * @param string $ADefaultProp
     * @return bool|ClassMySQLDB
     */
    public function whereOf($AValues, $ACond = "AND", $ADefaultProp = "ID") {
        $FResult = false;
        if (is_array($AValues)) {
            $FValues = $this->whereSubParse($AValues, $ADefaultProp);
            foreach ($FValues as $FKey => $FValue) {
                if (is_array($FValue)) {
                    foreach ($FValue as $FSubKey => $FSubValue) {
                        if ((StrOf::Length($FSubKey) > 0) and (StrOf::Length($FSubValue) > 0)) $FResult = $this->where($FKey, $FSubValue, $FSubKey, $ACond);
                    }
                } elseif (is_null($FValue)) {
                    $FResult = $this->where($FKey, $FValue, "IS", $ACond);
                } elseif (!DefaultOf::TypeCheck($FKey)) {
                    if (StrOf::Found(["AND", "OR"], $FKey, 1, SF_SameText)) $FResult = $this->where($FValue); else $FResult = $this->where($FKey, $FValue, CH_EQUAL, $ACond);
                } elseif (StrOf::Length($FValue) > 0) $FResult = $this->where($FValue);
            }
        } elseif (StrOf::Found(["AND", "OR"], $ADefaultProp, 1, SF_SameText)) $FResult = $this->where($AValues);
        elseif (DefaultOf::TypeCheck($AValues) or ($ADefaultProp <> "ID")) $FResult = $this->where($ADefaultProp, $AValues, CH_EQUAL, $ACond);
        elseif (StrOf::Length($AValues) > 0) $FResult = $this->where($AValues);
        return $FResult;
    }

    /**
     * @param $AValues
     * @return bool|ClassMySQLDB
     */
    public function orderByOf($AValues) {
        $FResult = false;
        try {
            if (StrOf::Length($AValues) > 0) {
                if (is_array($AValues)) {
                    foreach ($AValues as $FKey => $FValue) {
                        if (StrOf::Found(["ASC", "DESC"], $FValue, 1, SF_SameText)) $FResult = $this->orderBy($FKey, $FValue); else $FResult = $this->orderBy($FValue);
                    }
                } else $FResult = $this->orderBy($AValues);
            }
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $AValues
     * @return ClassMySQLDB|false
     */
    public function groupByOf($AValues) {
        $FResult = false;
        try {
            if (StrOf::Length($AValues) > 0) {
                $FResult = $this->groupBy($AValues);
            }
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $ATableName
     * @param string $AColumns
     * @param null $ANumRows
     * @param null $AFormat
     * @param bool $AFormatClearSubArray
     * @param null $AValueFromString
     * @param null $AJSONParseField
     * @return array|ClassMySQLDB|string|null
     */
    public function getOf($ATableName, $AColumns = "*", $ANumRows = null, $AFormat = null, $AFormatClearSubArray = true, $AValueFromString = null, $AJSONParseField = null) {
        $FResult = null;
        try {
            // Get result
            if ($ANumRows === 1) $FResult = $this->getOne($ATableName, $AColumns); else $FResult = $this->get($ATableName, $ANumRows, $AColumns);
            if ($FResult) {
                // Get JSON parsed
                if (StrOf::Length($AJSONParseField) > 0) $FResult = ArrayOf::FromJSON($FResult, $AJSONParseField);
                // Get format
                if (($ANumRows <> 1) and !is_null($AFormat)) {
                    foreach ($FResult as $FKey => $FValue) {
                        if (is_array($FValue)) {
                            $FResult[$FKey] = StrOf::Replace($AFormat, array_keys($FValue), array_values($FValue));
                            if (DefaultOf::TypeCheck($FKey)) $FResult[$FKey] = StrOf::Replace($FResult[$FKey], CH_NUMBER, $FKey + 1);
                            if (($AValueFromString === true) or (ArrayOf::Length($AValueFromString) > 0)) $FResult[$FKey] = DefaultOf::ValueFromString($FResult[$FKey], DefaultOf::ValueCheck($AValueFromString[0], 2), DefaultOf::ValueCheck($AValueFromString[1], CH_FREE));
                            if (!$AFormatClearSubArray) $FResult[$FKey] = [$FResult[$FKey]];
                        }
                    }
                }
            }
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $ATableName
     * @param $AValues
     * @param $AResult
     * @param string $AColumns
     * @param null $ANumRows
     * @param null $AFormat
     * @param bool $AFormatClearSubArray
     * @param null $AValueFromString
     * @param null $AOrder
     * @param null $AGroup
     * @param null $AJoin
     * @param null $AJoinWhere
     * @param null $AJSONParseField
     * @param string $ACond
     * @return bool|null
     */
    public function Filter($ATableName, $AValues, &$AResult, $AColumns = "*", $ANumRows = null, $AFormat = null, $AFormatClearSubArray = true, $AValueFromString = null, $AOrder = null, $AGroup = null, $AJoin = null, $AJoinWhere = null, $AJSONParseField = null, $ACond = "AND") {
        $AResult = null;
        if ((is_null($AJoin) or $this->joinOf($AJoin, $AJoinWhere, $ACond)) and (is_null($AValues) or $this->whereOf($AValues, $ACond)) and (is_null($AOrder) or $this->orderByOf($AOrder)) and (is_null($AGroup) or $this->groupByOf($AGroup))) $AResult = $this->getOf($ATableName, $AColumns, $ANumRows, $AFormat, $AFormatClearSubArray, $AValueFromString, $AJSONParseField);
        return (bool) $AResult;
    }

    /**
     * @param $ATableName
     * @param $AValues
     * @param $AResult
     * @param string $AColumns
     * @param null $AOrder
     * @param null $AGroup
     * @param null $AJoin
     * @param null $AJoinWhere
     * @param null $AJSONParseField
     * @param string $ACond
     * @return bool|null
     */
    public function FilterOne($ATableName, $AValues, &$AResult, $AColumns = "*", $AOrder = null, $AGroup = null, $AJoin = null, $AJoinWhere = null, $AJSONParseField = null, $ACond = "AND") {
        return $this->Filter($ATableName, $AValues, $AResult, $AColumns, 1, null, true, null, $AOrder, $AGroup, $AJoin, $AJoinWhere, $AJSONParseField, $ACond);
    }

    /**
     * @param $ATableName
     * @param $AValues
     * @param $AResult
     * @param string $AColumns
     * @param null $ANumRows
     * @param string $AFilterName
     * @param string $ACond
     * @param string $ADefaultProp
     * @return bool|null
     */
    public function FilterSub($ATableName, $AValues, &$AResult, $AColumns = "*", $ANumRows = null, $AFilterName = CH_FREE, $ACond = "AND", $ADefaultProp = "ID") {
        $AResult = null;
        try {
            if (StrOf::Length($ATableName) > 0) {
                // Create result
                $AResult = $this->subQuery($AFilterName);
                // Where result
                $this->whereSub($AResult, $AValues, $ACond, $ADefaultProp);
                // Get result
                if ($ANumRows === 1) $AResult->getOne($ATableName, $AColumns); else $AResult->get($ATableName, $ANumRows, $AColumns);
            }
        } catch (Exception $e) {
        }
        return (bool) $AResult;
    }

    /**
     * @param $ATableName
     * @param $AValues
     * @param $AResult
     * @param string $AColumns
     * @param string $AFilterName
     * @param string $ACond
     * @param string $ADefaultProp
     * @return bool|null
     */
    public function FilterOneSub($ATableName, $AValues, &$AResult, $AColumns = "*", $AFilterName = CH_FREE, $ACond = "AND", $ADefaultProp = "ID") {
        return $this->FilterSub($ATableName, $AValues, $AResult, $AColumns, 1, $AFilterName, $ACond, $ADefaultProp);
    }

    /**
     * @param $ATableName
     * @param $AValues
     * @param $AResult
     * @param false $AMultiData
     * @param null $ADuplicate
     * @param null $AOtherData
     * @param null $ACombineKey
     * @return bool|null
     */
    public function Append($ATableName, $AValues, &$AResult, $AMultiData = false, $ADuplicate = null, $AOtherData = null, $ACombineKey = null) {
        $AResult = 0;
        $FResult = false;
        try {
            $FValues = $AValues;
            if (ArrayOf::Length($FValues) > 0) {
                if ($AMultiData === false) {
                    if (!is_null($ADuplicate)) $this->onDuplicate($ADuplicate);
                    if (!is_null($AOtherData)) $FValues = ArrayOf::Of(GAO_Merge, $FValues, $AOtherData);
                    if (!is_null($ACombineKey)) $FValues = ArrayOf::Combine($FValues, $ACombineKey);
                    $FID = $this->insert($ATableName, ArrayOf::ToJSON($FValues, true));
                    if ((bool)$FID) {
                        $AResult = (int)$FID;
                        $FResult = true;
                    }
                } else {
                    if (!is_null($ADuplicate)) $this->onDuplicate($ADuplicate);
                    if (is_array($AMultiData)) $FID = $this->AppendExecute1($ATableName, $FValues, $AMultiData, $ADuplicate, $AOtherData, $ACombineKey); else $FID = $this->AppendExecute1($ATableName, $FValues, null, $ADuplicate, $AOtherData, $ACombineKey);
                    if ($FID) {
                        $AResult = $this->getInsertId();
                        $FResult = true;
                    }
                }
            }
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $ATableName
     * @param $AMultiInsertData
     * @param $ADataKeys
     * @param $ADuplicate
     * @param $AOtherData
     * @param $ACombineKey
     * @return array|bool
     */
    private function AppendExecute1($ATableName, $AMultiInsertData, $ADataKeys, $ADuplicate, $AOtherData, $ACombineKey) {
        $FResult = [];
        try {
            $FAutoCommit = (isset($this->_transaction_in_progress) ? !$this->_transaction_in_progress : true);
            if($FAutoCommit) $this->startTransaction();
            foreach ($AMultiInsertData as $FValue) {
                if ((ArrayOf::Length($ADataKeys) > 0) and (ArrayOf::Length($ADataKeys) == ArrayOf::Length($FValue))) $FValue = array_combine($ADataKeys, $FValue);
                if (!$this->Append($ATableName, $FValue, $FID, false, $ADuplicate, $AOtherData, $ACombineKey)) {
                    if ($FAutoCommit) $this->rollback();
                    return false;
                }
                $FResult[] = $FID;
            }
            if ($FAutoCommit) $this->commit();
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $ATableName
     * @param $AID
     * @param $AValues
     * @param null $ANumRows
     * @return bool|null
     */
    public function Edit($ATableName, $AID, $AValues, $ANumRows = null) {
        $FResult = false;
        if ((ArrayOf::Length($AValues) > 0) and $this->whereOf($AID)) {
            try {
                if ($this->update($ATableName, ArrayOf::ToJSON($AValues, true), $ANumRows)) $FResult = true;
            } catch (Exception $e) {
            }
        }
        return $FResult;
    }

    /**
     * @param $ATableName
     * @param $AValues
     * @return bool|null
     */
    public function Deleted($ATableName, $AValues) {
        $FResult = false;
        if ($this->whereOf($AValues)) {
            try {
                $FResult = $this->delete($ATableName);
            } catch (Exception $e) {
            }
        }
        return $FResult;
    }
}

/**
 * ClassTelegram
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class TelegramOf extends Telegram {

    const GROUP_POST = "group";
    const POLL_ANSWER = "poll_answer";

    private $valid;

    /**
     * ClassTelegram constructor.
     * @param string $AToken
     */
    public function __construct($AToken = "") {
        parent::__construct($AToken, false);
        $this->valid = false;
    }

    /**
     * @param array $content
     * @return mixed
     */
    public function editMessageMedia(array $content) {
        return $this->endpoint("editMessageMedia", $content);
    }

    /**
     * @param array $content
     * @return mixed
     */
    public function sendPoll(array $content) {
        return $this->endpoint('sendPoll', $content);
    }

    /**
     * @param $type
     * @param $media
     * @param $caption
     * @param $parse_mode
     * @return false|string
     */
    public function buildMedia($type, $media, $caption, $parse_mode) {
        $replyMarkup = ["type" => $type, "media" => $media, "caption" => $caption, "parse_mode"=> $parse_mode];
        return json_encode($replyMarkup, true);
    }

    /**
     * @param $AButtons
     * @param bool $AInline
     * @return false|string|null
     */
    private function GetBuildButtons($AButtons, $AInline = false) {
        $FButtons = null;
        if ($AInline) {
            if (ArrayOf::Length($AButtons) > 0) $FButtons = $this->buildInlineKeyBoard($this->GetBuildButtonsExecute1($AButtons, $AInline));
        } elseif (ArrayOf::Length($AButtons) > 0) $FButtons = $this->buildKeyBoard($this->GetBuildButtonsExecute1($AButtons), false, true); else $FButtons = $this->buildKeyBoardHide();
        return $FButtons;
    }

    /**
     * @param $AButtons
     * @param bool $AInline
     * @return array
     */
    private function GetBuildButtonsExecute1($AButtons, $AInline = false) {
        $FButton = [];
        foreach ($AButtons as $FKey => $FValue) {
            if (is_array($FValue)) {
                foreach ($FValue as $FSubKey => $FSubValue) $FValue[$FSubKey] = $this->GetBuildButtonsExecute2($FSubValue, $AInline);
                array_push($FButton, $FValue);
            } else array_push($FButton, [$this->GetBuildButtonsExecute2($FValue, $AInline)]);
        }
        return $FButton;
    }

    /**
     * @param $AValue
     * @param bool $AInline
     * @return array
     */
    private function GetBuildButtonsExecute2($AValue, $AInline = false) {
        $FValue = StrOf::From($AValue);
        if ((ArrayOf::FromString($FValue, CH_EQUAL, $FResult) == 2) or (ArrayOf::FromString($FValue, CH_SPEC, $FResult) == 2)) {
            $FNameStr = ArrayOf::Value($FResult);
            $FValueStr = ArrayOf::Value($FResult, 2);
            if ($AInline) $FResult = $this->buildInlineKeyboardButton($FNameStr, CH_FREE, $FValueStr); else {
                if (StrOf::Same($FValueStr, "[PHONE]")) $FResult = $this->buildKeyboardButton($FNameStr, true);
                elseif (StrOf::Same($FValueStr, "[LOCATION]")) $FResult = $this->buildKeyboardButton($FNameStr, false, true); else $FResult = $this->buildKeyboardButton($FValue);
            }
        } elseif ($AInline) $FResult = $this->buildInlineKeyboardButton($FValue, CH_FREE, $FValue); else $FResult = $this->buildKeyboardButton($FValue);
        return $FResult;
    }

    /**
     * @return string
     */
    public function getUpdateTypeOf() {
        $FResult = $this->getUpdateType();
        if (($FResult === self::MESSAGE) and ($this->getData()["message"]["chat"]["type"] === self::GROUP_POST)) $FResult = self::GROUP_POST;
        elseif (!$FResult) {
            if (isset($this->getData()["poll_answer"])) $FResult = self::POLL_ANSWER;
        }
        return $FResult;
    }

    /**
     * @param $AToken
     * @return bool
     */
    public function setToken($AToken) {
        $this->bot_token = $AToken;
        return true;
    }

    /**
     * @return mixed
     */
    public function ChatIDOf() {
        if ($this->getUpdateTypeOf() === self::POLL_ANSWER) $FResult = $this->getData()["poll_answer"]["user"]["id"]; else $FResult = $this->ChatID();
        return $FResult;
    }

    /**
     * @return mixed
     */
    public function PollIDOf() {
        if ($this->getUpdateTypeOf() === self::POLL_ANSWER) $FResult = $this->getData()["poll_answer"]["poll_id"]; else $FResult = null;
        return $FResult;
    }

    /**
     * @return mixed
     */
    public function CaptionOf() {
        if ($this->getUpdateTypeOf() === self::CALLBACK_QUERY) $FResult = $this->getData()["callback_query"]["message"]["caption"]; else $FResult = $this->Caption();
        return $FResult;
    }

    /**
     * @param array $ATypes
     * @return bool
     */
    public function Active($ATypes = []) {
        $this->valid = (!empty($this->getData()) and ((ArrayOf::Length($ATypes) == 0) or in_array($this->getUpdateTypeOf(), $ATypes)));
        return $this->valid;
    }

    /**
     * @return bool
     */
    public function Valid() {
        return ($this->valid and !empty($this->bot_token));
    }

    /**
     * @param null $ACharCase
     * @return string|null
     */
    public function TextOf($ACharCase = null) {
        if ($this->getUpdateTypeOf() === self::POLL_ANSWER) {
            $FResult = $this->getData()["poll_answer"]["option_ids"];
            if (ArrayOf::Length($FResult) > 0) $FResult = range("A", "Z")[ArrayOf::First($FResult)]; else $FResult = null;
        } else $FResult = $this->Text();
        return StrOf::CharCase($FResult, $ACharCase);
    }

    /**
     * @param false $APhoneValid
     * @return bool|null
     */
    public function ContactOf($APhoneValid = false) {
        $FResult = $this->getData()["message"]["contact"] ?? null;
        if ($FResult and $APhoneValid) $FResult = StrOf::Copy($this->PhoneOf(), 1, 1) == 9;
        return $FResult;
    }

    /**
     * @return mixed
     */
    public function PhoneOf() {
        return StrOf::Copy($this->getData()["message"]["contact"]["phone_number"], 9, 9, true);
    }

    /**
     * @param string $ADefault
     * @return string
     */
    public function FullName($ADefault = "none") {
        if ($this->getUpdateTypeOf() === self::POLL_ANSWER) {
            $FData = $this->getData()["poll_answer"]["user"];
            $FResult = trim(trim($FData["first_name"]) . CH_SPACE . trim($FData["last_name"]));
            if (StrOf::Length($FResult) == 0) $FResult = trim($FData["username"]);
        } else {
            $FResult = trim(trim($this->FirstName()) . CH_SPACE . trim($this->LastName()));
            if (StrOf::Length($FResult) == 0) $FResult = trim($this->Username());
        }
        if (StrOf::Length($FResult) == 0) $FResult = trim($ADefault);
        return $FResult;
    }

    /**
     * @param string $AAction
     * @param null $AChatID
     * @return mixed|null
     */
    public function sendChatActionOf($AAction = "typing", $AChatID = null) {
        $FResult = $this->sendChatAction(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "action" => $AAction]);
        if (DefaultOf::ValueCheck($FResult["ok"], false)) return $FResult; return null;
    }

    /**
     * @param $AText
     * @param null $AButtons
     * @param bool $AInline
     * @param null $AChatID
     * @param null $AGetMessageID
     * @param bool $ADeleteOk
     * @return mixed|null
     */
    public function sendMessageOf($AText, $AButtons = null, $AInline = false, $AChatID = null, &$AGetMessageID = null, $ADeleteOk = false) {
        $FResult = null;
        if (StrOf::Length($AText) > 0) {
            if (is_null($AButtons)) $FResult = $this->sendMessage(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "text" => $AText, "parse_mode" => "html"]); else $FResult = $this->sendMessage(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, $AInline), "text" => $AText, "parse_mode" => "html"]);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) {
            if ($ADeleteOk and DefaultOf::TypeCheck($AGetMessageID)) $this->deleteMessageOf($AGetMessageID);
            $AGetMessageID = $FResult["result"]["message_id"];
            return $FResult;
        } else {
            $AGetMessageID = null;
            return null;
        }
    }

    /**
     * @param $APhoto
     * @param string $ACaption
     * @param null $AButtons
     * @param bool $AInline
     * @param null $AChatID
     * @param null $AGetMessageID
     * @return mixed|null
     */
    public function sendPhotoOf($APhoto, $ACaption = "", $AButtons = null, $AInline = false, $AChatID = null, &$AGetMessageID = null) {
        $FResult = null;
        if (StrOf::Length($APhoto) > 0) {
            if (is_null($AButtons)) $FResult = $this->sendPhoto(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "photo" => $APhoto, "caption" => $ACaption, "parse_mode" => "html"]); else $FResult = $this->sendPhoto(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, $AInline), "photo" => $APhoto, "caption" => $ACaption, "parse_mode" => "html"]);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) {
            $AGetMessageID = $FResult["result"]["message_id"];
            return $FResult;
        } else {
            $AGetMessageID = null;
            return $FResult;// null;
        }
    }

    /**
     * @param $ADocument
     * @param string $ACaption
     * @param null $AButtons
     * @param false $AInline
     * @param null $AChatID
     * @param null $AGetMessageID
     * @return mixed|null
     */
    public function sendDocumentOf($ADocument, $ACaption = CH_FREE, $AButtons = null, $AInline = false, $AChatID = null, &$AGetMessageID = null) {
        $FResult = null;
        if ((StrOf::Length($ADocument) > 0) and (StrOf::Pos($ADocument, CH_SPEC) == 0)) {
            if (file_exists($ADocument)) $FDocument = SystemOf::FileInfo($ADocument, GFI_Curl); else $FDocument = $ADocument;
            if (is_null($AButtons)) $FResult = $this->sendDocument(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "document" => $FDocument, "caption" => $ACaption, "parse_mode" => "html"]); else $FResult = $this->sendDocument(["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, $AInline), "document" => $FDocument, "caption" => $ACaption, "parse_mode" => "html"]);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) {
            $AGetMessageID = $FResult["result"]["message_id"];
            return $FResult;
        } else {
            $AGetMessageID = null;
            return null;
        }
    }

    /**
     * @param $AQuestion
     * @param $AOptions
     * @param int $ACorrect
     * @param int $APeriod
     * @param null $AButtons
     * @param null $AChatID
     * @param null $AGetPollID
     * @return mixed|null
     */
    public function sendPollOf($AQuestion, $AOptions, $ACorrect = 0, $APeriod = 0, $AButtons = null, $AChatID = null, &$AGetPollID = null) {
        $FResult = null;
        if ((StrOf::Length($AQuestion) > 0) and DefaultOf::IntervalCheck(ArrayOf::Length($AOptions), 2, 10)) {
            foreach ($AOptions as $FKey => $FValue) $AOptions[$FKey] = StrOf::Copy($FValue, 1, 100, false, CH_POINT_THREE);
            $FContent = ["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "question" => StrOf::Copy($AQuestion, 1, 300, false, CH_POINT_THREE), "options" => json_encode($AOptions), "is_anonymous" => false];
            if (isset($ACorrect) and ($ACorrect > 0)) $FContent["correct_option_id"] = $ACorrect;
            if (isset($APeriod) and ($APeriod > 0)) $FContent["open_period"] = $APeriod;
            if (!is_null($AButtons)) $FContent["reply_markup"] = $this->GetBuildButtons($AButtons);
            $FResult = $this->sendPoll($FContent);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) {
            $AGetPollID = $FResult["result"]["poll"]["id"];
            return $FResult;
        } else {
            $AGetPollID = null;
            return null;
        }
    }

    /**
     * @param $APhoto
     * @param string $ACaption
     * @param null $AButtons
     * @param false $AReplyMessage
     * @param null $AChatID
     * @param null $AGetMessageID
     * @param false $ADeleteOk
     * @return mixed|null
     */
    public function sendAnimationOf($APhoto, $ACaption = CH_FREE, $AButtons = null, $AReplyMessage = false, $AChatID = null, &$AGetMessageID = null, $ADeleteOk = false) {
        $FResult = null;
        if (StrOf::Length($APhoto) > 0) {
            $FContent = ["chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "animation" => $APhoto];
            if (StrOf::Length($ACaption) > 0) {
                $FContent["caption"] = $ACaption;
                $FContent["parse_mode"] = "html";
            }
            if (!is_null($AButtons)) $FContent["reply_markup"] = $this->GetBuildButtons($AButtons, true);
            if ($AReplyMessage) $FContent["reply_to_message_id"] = $this->MessageID();
            $FResult = $this->sendAnimation($FContent);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) {
            if ($ADeleteOk and DefaultOf::TypeCheck($AGetMessageID)) $this->deleteMessageOf($AGetMessageID);
            $AGetMessageID = $FResult["result"]["message_id"];
            return $FResult;
        } else {
            $AGetMessageID = null;
            return null;
        }
    }

    /**
     * @param $AChatID
     * @param $AFromChatID
     * @param $AMessageID
     * @return mixed
     */
    public function sendForward($AChatID, $AFromChatID, $AMessageID) {
        return $this->endpoint('forwardMessage', ['chat_id' => $AChatID, 'from_chat_id' => $AFromChatID, 'message_id' => $AMessageID]);
    }


    /**
     * @param $AMessageID
     * @param $AText
     * @param null $AButtons
     * @param null $AChatID
     * @return mixed|null
     */
    public function editMessageOf($AMessageID, $AText, $AButtons = null, $AChatID = null) {
        $FResult = null;
        if (StrOf::Length($AText) > 0) {
            if (is_null($AButtons)) $FResult = $this->editMessageText(["message_id" => DefaultOf::ValueCheck($AMessageID, $this->MessageID()), "chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "text" => $AText, "parse_mode" => "html"]); else $FResult = $this->editMessageText(["message_id" => DefaultOf::ValueCheck($AMessageID, $this->MessageID()), "chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, true), "text" => $AText, "parse_mode" => "html"]);
        }

        if (DefaultOf::ValueCheck($FResult["ok"], false)) return $FResult; return null;
    }

    /**
     * @param $AMessageID
     * @param $ACaption
     * @param null $AButtons
     * @param null $AChatID
     * @return mixed|null
     */
    public function editMessageCaptionOf($AMessageID, $ACaption, $AButtons = null, $AChatID = null) {
        $FResult = null;
        if (StrOf::Length($ACaption) > 0) {
            if (is_null($AButtons)) $FResult = $this->editMessageCaption(["message_id" => DefaultOf::ValueCheck($AMessageID, $this->MessageID()), "chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "caption" => $ACaption, "parse_mode" => "html"]); else $FResult = $this->editMessageCaption(["message_id" => DefaultOf::ValueCheck($AMessageID, $this->MessageID()), "chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, true), "caption" => $ACaption, "parse_mode" => "html"]);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) return $FResult; return null;
    }

    /**
     * @param $AMessageID
     * @param $AMedia
     * @param string $ACaption
     * @param null $AButtons
     * @param null $AChatID
     * @param string $AMediaType
     * @return mixed|null
     */
    public function editMessageMediaOf($AMessageID, $AMedia, $ACaption = "", $AButtons = null, $AChatID = null, $AMediaType = "photo") {
        $FResult = null;
        if (StrOf::Length($AMedia) > 0) {
            if (is_null($AButtons)) $FResult = $this->editMessageMedia(["message_id" => DefaultOf::ValueCheck($AMessageID, $this->MessageID()), "chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "media" => $this->buildMedia($AMediaType, $AMedia, $ACaption, "html")]); else $FResult = $this->editMessageMedia(["message_id" => DefaultOf::ValueCheck($AMessageID, $this->MessageID()), "chat_id" => DefaultOf::ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, true), "media" => $this->buildMedia($AMediaType, $AMedia, $ACaption, "html")]);
        }
        if (DefaultOf::ValueCheck($FResult["ok"], false)) return $FResult; return null;
    }

    /**
     * @param null $AMessageID
     * @param null $AChatID
     * @param null $AExclude
     * @param array $ATypes
     */
    public function deleteMessageOf($AMessageID = null, $AChatID = null, $AExclude = null, $ATypes = []) {
        if (is_array($AMessageID)) {
            foreach ($AMessageID as $FValue) $this->deleteMessageOfExecute1($AChatID, $FValue, $AExclude, $ATypes);
        } else $this->deleteMessageOfExecute1($AChatID, DefaultOf::ValueCheck($AMessageID, $this->MessageID()), $AExclude, $ATypes);
    }

    /**
     * @param $AChatID
     * @param $AMessageID
     * @param null $AExclude
     * @param array $ATypes
     * @return mixed|null
     */
    private function deleteMessageOfExecute1($AChatID, $AMessageID, $AExclude = null, $ATypes = []) {
        $FChatID = ArrayOf::First($AChatID);
        $FMessageID = ArrayOf::First($AMessageID);
        if (DefaultOf::TypeCheck($FMessageID) and !StrOf::Found($AExclude, $FMessageID, 1, SF_SameText) and ((ArrayOf::Length($ATypes) == 0) or in_array($this->getUpdateType(), $ATypes))) return $this->deleteMessage(["chat_id" => DefaultOf::ValueCheck($FChatID, $this->ChatID()), "message_id" => $FMessageID]); else return null;
    }

    /**
     * @param string $AType
     * @return mixed
     */
    public function getFileID($AType = "document") {
        return $this->getData()["message"][$AType]["file_id"];
    }

    /**
     * @param string $AType
     * @return mixed
     */
    public function getFileName($AType = "document") {
        return $this->getData()["message"][$AType]["file_name"];
    }

    public function downloadFileOf(&$AResult, $ACompareName = null) {
        $FResult = false;
        $AResult = CH_FREE;
        if ($this->getUpdateTypeOf() == self::DOCUMENT) {
            $FFileID = $this->getFileID();
            $FFileName = $this->getFileName();
            $FData = $this->getFile($FFileID);
            if (file_exists($FFileName)) unlink($FFileName);
            if ($FData["ok"] and (is_null($ACompareName) or (StrOf::Pos($FFileName, $ACompareName) > 0))) {
                $this->downloadFile($FData["result"]["file_path"], $FFileName);
                if (file_exists($FFileName)) {
                    $FResult = true;
                    $AResult = $FFileName;
                }
            }
        }
        return $FResult;
    }

    /**
     * @param $AChatID
     * @param null $AUserID
     * @return array|false|mixed
     */
    public function getChatMemberOf($AChatID, $AUserID = null) {
        if (ArrayOf::Length($AChatID) > 1) {
            $FResult = [];
            foreach ($AChatID as $FValue) $FResult = ArrayOf::Of(GAO_Merge, $FResult, $this->getChatMemberOf($FValue, $AUserID));
            if (ArrayOf::Length($FResult) == 0) $FResult = false;
            elseif (ArrayOf::Length($FResult) == 1) $FResult = ArrayOf::First($FResult);
        } else {
            $FChatID = trim(ArrayOf::First($AChatID));
            if (StrOf::Length($FChatID) > 0) {
                $FResult = $this->getChatMember(["chat_id" => $FChatID, "user_id" => DefaultOf::ValueCheck($AUserID, $this->UserID())]);
                if (DefaultOf::ValueCheck($FResult["ok"], false)) $FResult = $FResult["result"]["status"]; else $FResult = false;
            } else $FResult = false;
        }
        return $FResult;
    }

    /**
     * @return mixed
     */
    public function getMeOf() {
        $FResult = $this->getMe();
        return $FResult["ok"];
    }

}

/**
 * ClassFTP
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class FtpOf {

    private $FHost;
    private $FPort;
    private $FPassword;
    private $FStream;
    private $FTimeout;
    private $FUserName;

    /**
     * ClassFTP constructor.
     * @param $AHost
     * @param $AUserName
     * @param $APassword
     * @param int $APort
     * @param int $ATimeout
     */
    public function  __construct($AHost, $AUserName, $APassword, $APort = 21, $ATimeout = 90) {
        $this->FHost = $AHost;
        $this->FUserName = $AUserName;
        $this->FPassword = $APassword;
        $this->FPort = (int)$APort;
        $this->FTimeout = (int)$ATimeout;
    }

    public function  __destruct() {
        $this->close();
    }

    /**
     * FTP connection
     * @param bool $APassive
     * @return bool
     */
    public function Connect($APassive) {
        $this->FStream = ftp_connect($this->FHost, $this->FPort, $this->FTimeout);
        if ($this->FStream and ftp_login($this->FStream, $this->FUserName, $this->FPassword)) {
            ftp_pasv($this->FStream, $APassive);
            return true;
        } else return false;
    }

    /**
     * FTP disconnection
     * @return $this
     */
    public function Close() {
        if ($this->FStream) {
            ftp_close($this->FStream);
            $this->FStream = false;
        }
        return $this;
    }

    /**
     * @param bool $APassive
     * @return bool
     */
    public function Reconnect($APassive) {
        sleep(1);
        $this->Close();
        sleep(1);
        return $this->Connect($APassive);
    }

    /**
     * FTP system type
     * @return false|string
     */
    public function GetSystemType() {
        return ftp_systype($this->FStream);
    }

    /**
     * Create directory
     * @param $ADirectory
     * @return bool
     */
    public function DirectoryCreate($ADirectory) {
        return ftp_mkdir($this->FStream, $ADirectory);
    }

    /**
     * Get current directory
     * @return string
     */
    public function DirectoryPath() {
        return ftp_pwd($this->FStream);
    }

    /**
     * Change current directory
     * @param $ADirectory
     * @return bool
     */
    public function DirectoryChange($ADirectory) {
        return ftp_chdir($this->FStream, $ADirectory);
    }

    /**
     * Remove directory
     * @param $ADirectory
     * @return bool
     */
    public function DirectoryRemove($ADirectory) {
        return ftp_rmdir($this->FStream, $ADirectory);
    }

    /**
     * Get all files list
     * @param $ADirectory
     * @return array|false
     */
    public function FileList($ADirectory) {
        return ftp_nlist($this->FStream, $ADirectory);
    }

    /**
     * Download file
     * @param $ARemoteFile
     * @param $ALocalFile
     * @param int $AMode
     * @return bool
     */
    public function FileDownload($ARemoteFile, $ALocalFile, $AMode = FTP_BINARY) {
        return ftp_get($this->FStream, $ALocalFile, $ARemoteFile, $AMode);
    }

    /**
     * Upload file
     * @param $ALocalFile
     * @param $ARemoteFile
     * @param int $AMode
     * @return bool
     */
    public function FileUpload($ALocalFile, $ARemoteFile, $AMode = FTP_BINARY) {
        return ftp_put($this->FStream, $ARemoteFile, $ALocalFile, $AMode);
    }

    /**
     * Rename file
     * @param $AOldName
     * @param $ANewName
     * @return bool
     */
    public function FileRename($AOldName, $ANewName) {
        return ftp_rename($this->FStream, $AOldName, $ANewName);
    }

    /**
     * Delete file
     * @param $ARemoteFile
     * @return bool
     */
    public function FileDelete($ARemoteFile) {
        return ftp_delete($this->FStream, $ARemoteFile);
    }

}