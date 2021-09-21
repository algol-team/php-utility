<?php
/********************************************************************************
 *                        .::ALGOL-TEAM PRODUCTIONS::.                           *
 *    .::Author © 2021 | algol.team.uz@gmail.com | github.com/algol-team::.      *
 *********************************************************************************
 *  Description: This is class for PHP.                                          *
 *  Thanks to specialist: All PHP masters.                                       *
 ********************************************************************************/

// CONST GLOBAL

use Embed\ExtractorFactory;
use Embed\Http\Crawler;
use Embed\Embed;

const CH_AND = "&";
const CH_COMMA = ",";
const CH_EQUAL = "=";
const CH_FREE = "";
const CH_PLUS = "+";
const CH_MINUS = "-";
const CH_NET = "#";
const CH_NULL = "0";
const CH_NUMBER = "№";
const CH_POINT = ".";
const CH_POINT_TWO_VER = ":";
const CH_POINT_COMMA = ";";
const CH_POINT_THREE = "...";
const CH_SPACE = " ";
const CH_SPEC = "|";
const CH_BOTTOM_LINE = "_";
const CH_BRACE_BEGIN = "(";
const CH_BRACE_END = ")";
const CH_BRACE_FIGURE_BEGIN = "{";
const CH_BRACE_FIGURE_END = "}";
const CH_BRACE_SQR_BEGIN = "[";
const CH_BRACE_SQR_END = "]";
const CH_TAG_BEGIN = "<";
const CH_TAG_END = ">";
const CH_PERCENT = "%";
const CH_MAIL = "@";
const CH_FLAG = "~";
const CH_STAR = "*";
const CH_MONEY = "$";
const CH_INTERJECTION = "!";
const CH_QUESTION = "?";
const CH_ID = "ID";
const CH_OK = "OK";
const CH_NEW_LINE = "\n";
const CH_PATH = "\\";
const CH_ANTI_PATH = "/";

const CH_TRIM = "CH_TRIM";

/**
 * ALGOL
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class ALGOL {

    public static $params = [];

    /**
     * @return DefaultOf
     */
    public static function DefaultOf() {
        return new DefaultOf();
    }

    /**
     * @return StrOf
     */
    public static function StrOf() {
        return new StrOf();
    }

    /**
     * @return ValueOf
     */
    public static function ValueOf() {
        return new ValueOf();
    }

    /**
     * @return ArrayOf
     */
    public static function ArrayOf() {
        return new ArrayOf();
    }

    /**
     * @return DateTimeOf
     */
    public static function DateTimeOf() {
        return new DateTimeOf();
    }

    /**
     * @return SystemOf
     */
    public static function SystemOf() {
        return new SystemOf();
    }

    /**
     * @param $AFileName
     * @param null $ALogFile
     * @return LanguageOf
     */
    public static function LanguageOf($AFileName, $ALogFile = null) {
        return new LanguageOf($AFileName, $ALogFile);
    }

    /**
     * @param null $AHost
     * @param null $AUserName
     * @param null $APassword
     * @param null $ADatabase
     * @param int $APort
     * @return MysqlDbOf
     */
    public static function MysqlDbOf($AHost = null, $AUserName = null, $APassword = null, $ADatabase = null, $APort = 3306) {
        return new MysqlDbOf($AHost, $AUserName, $APassword, $ADatabase, $APort);
    }

    /**
     * @param string $AToken
     * @return TelegramOf
     */
    public static function TelegramOf($AToken = "") {
        return new TelegramOf($AToken);
    }

    /**
     * @param $AHost
     * @param $AUserName
     * @param $APassword
     * @param int $APort
     * @param int $ATimeout
     * @return FtpOf
     */
    public static function FtpOf($AHost, $AUserName, $APassword, $APort = 21, $ATimeout = 90) {
        return new FtpOf($AHost, $AUserName, $APassword, $APort, $ATimeout);
    }

    public static function EmbedOf($ACrawler = null, $AExtractorFactory = null) {
        return new EmbedOf($ACrawler, $AExtractorFactory);
    }

}

// Const Get Type Check
const DIC_Number = "DIC_Number";
const DIC_DateTime = "DIC_DateTime";
const DIC_TimeOnly = "DIC_TimeOnly";
const DIC_MultiArray = "DIC_MultiArray";

/**
 * DefaultOf
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
    public function ValueCheck($AValue, $ADefault, $ATrue = null) {
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
    public function ValueFromString($AValue, $ADecimal = 2, $AThousand = CH_FREE) {
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
    private function ValueFromStringExecute1($AValue, $ADecimal = 2, $AThousand = CH_FREE) {
        if (is_string($AValue)) {
            $FValue = (new StrOf)->Replace($AValue, CH_COMMA, CH_POINT);
            if (self::TypeCheck($AValue)) return intval($AValue);
            elseif (self::TypeCheck($AValue, FILTER_VALIDATE_FLOAT)) return (float)number_format(floatval($AValue), (new ArrayOf)->First($ADecimal), (new ArrayOf)->Length($ADecimal) > 1 ? (new ArrayOf)->Value($ADecimal, 2) : CH_POINT, $AThousand);
            elseif (self::TypeCheck($FValue, FILTER_VALIDATE_FLOAT)) return (float)number_format(floatval($FValue), (new ArrayOf)->First($ADecimal), (new ArrayOf)->Length($ADecimal) > 1 ? (new ArrayOf)->Value($ADecimal, 2) : CH_POINT, $AThousand);
            elseif ((new StrOf)->Same($AValue, "false")) return false;
            elseif ((new StrOf)->Same($AValue, "true")) return true;
            else return $AValue;
        } elseif (self::TypeCheck($AValue, FILTER_VALIDATE_FLOAT)) return (float)number_format($AValue, (new ArrayOf)->First($ADecimal), (new ArrayOf)->Length($ADecimal) > 1 ? (new ArrayOf)->Value($ADecimal, 2) : CH_POINT, $AThousand); else return $AValue;
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
    public function IntervalCheck($AValue, $AMin, $AMax) {
        $FResult = true;
        $FValue = (new ArrayOf)->First($AValue);
        $FMin = (new ArrayOf)->First($AMin);
        $FMax = (new ArrayOf)->First($AMax);
        if (!is_null($AMin) and !self::TypeCheck($AMin, DIC_Number)) {
            $FResult = (new StrOf)->Found($AMin, $FValue, 1, SF_SameText);
            $FMin = null;
        }
        if ($FResult and !is_null($AMax) and !self::TypeCheck($AMax, DIC_Number)) {
            $FResult = (new StrOf)->Found($AMax, $FValue, 1, SF_SameText);
            $FMax = null;
        }
        if ($FResult and (self::TypeCheck($FMin, DIC_Number) or self::TypeCheck($FMax, DIC_Number))) {
            if (self::TypeCheck($FValue, DIC_Number)) {
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
    public function TypeCheck($AValue, $AType = FILTER_VALIDATE_INT) {
        if ($AType == DIC_MultiArray) {
            if (is_array($AValue)) {
                foreach ($AValue as $FValue) if (is_array($FValue)) return true;
            }
            return false;
        } elseif ($AType == DIC_DateTime) {
            if ((new ArrayOf)->Length($AValue) > 1) {
                $FFormats = (new ArrayOf)->Of(AO_Cut, $AValue, 2, (new ArrayOf)->Length($AValue) - 1);
                $FValue = trim((new ArrayOf)->First($AValue));
                if (is_array($FFormats)) {
                    foreach ($FFormats as $FFormat) if (date_parse_from_format($FFormat, $FValue)["error_count"] == 0) return true;
                    return false;
                } else return date_parse_from_format($FFormats, $FValue)["error_count"] == 0;
            } else return date_parse(trim((new ArrayOf)->First($AValue)))["error_count"] == 0;
        } elseif ($AType == DIC_TimeOnly) return self::TypeCheck([$AValue, "H:i:s", "H:i"], DIC_DateTime); else {
            if ($AType == DIC_Number) $FType = FILTER_VALIDATE_INT|FILTER_VALIDATE_FLOAT; else $FType = $AType;
            if (filter_var($AValue, $FType) === false) return false; else return true;
        }
    }

    /**
     * @param $AValue
     * @param string $ATag
     * @return bool
     */
    public function PrintFormat($AValue, $ATag = 'pre') {
        $FResult = isset($AValue);
        if ($FResult) {
            echo CH_TAG_BEGIN . $ATag . CH_TAG_END;
            print_r($AValue);
            echo CH_TAG_BEGIN . CH_ANTI_PATH . $ATag . CH_TAG_END;
        }
        return $FResult;
    }

}

// Const String Found
const SF_SameText = "SF_SameText";
const SF_FirstText = "SF_FirstText";
const SF_GetCount = "SF_GetCount";
const SF_GetValue = "SF_GetValue";
const SF_GetKey = "SF_GetKey";
const SF_GetKeySame = "SF_GetKeySame";
const SF_OnlyKey = "SF_OnlyKey";
const SF_OnlyKeySame = "SF_OnlyKeySame";
const SF_WithKey = "SF_WithKey";
const SF_WithKeySame = "SF_WithKeySame";

// Const String Replace
const SR_ArrayKeys = "SR_ArrayKeys";

// Const Format From User Data
const SFFUD_FullName = "SFFUD_FullName";
const SFFUD_Login = "SFFUD_Login";
const SFFUD_Password = "SFFUD_Password";

/**
 * StrOf
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
    public function Length($AValue, $ATrim = false) {
        try {
            if (is_null($AValue)) return 0;
            elseif (is_array($AValue)) return (new ArrayOf)->Length($AValue, true);
            elseif (is_object($AValue)) return (new ArrayOf)->Length((array)$AValue, true);
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
    public function From($AValue, $ADefault = CH_FREE) {
        return strval((new DefaultOf)->ValueCheck((new ArrayOf)->First($AValue), $ADefault));
    }

    /**
     * @param $AValue
     * @param $ASubValue
     * @param int $AStart
     * @param bool $ARepeat
     * @param bool $AWord
     * @return false|int
     */
    public function Pos($AValue, $ASubValue, $AStart = 1, $ARepeat = false, $AWord = false) {
        $FResult = 0;
        if ((self::Length($AValue) > 0) and (self::Length($ASubValue) > 0) and (new DefaultOf)->IntervalCheck($AStart, 1, self::Length($AValue))) {
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
    private function PosExecute1($AValue, $ASubValue, $AStart = 0, $AWord = false) {
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
    public function PosWord($AValue, $ASubValue, $AStart = 1, $ARepeat = false) {
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
    public function Found($AValue, $ASubValue, $AStart = 1, $AParam = null, $AFullSearch = false, $AWord = false) {
        $FResult = 0;
        $FText = null;
        if ((self::Length($AValue) > 0) and (self::Length($ASubValue) > 0)) {
            $FSubValue = $ASubValue;
            if ((new ArrayOf)->Length($FSubValue) > 0) {
                $FSubValue = array_diff(array_unique($FSubValue), [CH_FREE]);
                if ($AFullSearch) {
                    foreach ($FSubValue as $FValue) {
                        $FSubResult = 0;
                        self::FoundExecute1($AValue, $FValue, $AStart, $AParam, $AWord, $FSubResult, $FText);
                        if ($FSubResult == 0) break; else $FResult += $FSubResult;
                    }
                    if (($FResult < (new ArrayOf)->Length($FSubValue)) and ($AParam <> SF_GetCount)) $FResult = 0;
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
    private function FoundExecute1($ASource, $ASearch, $APos, $AParam, $AWord, &$AResult, &$AText) {
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
    private function FoundExecute2($ASource, $ASearch, $APos, $AParam, $AWord, &$AResult) {
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
    private function FoundExecute3($ASource, $ASearch, $APos, $AWord, &$AResult) {
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
    public function Same($AValue1, $AValue2, $APercent = 100) {
        similar_text(self::CharCase($AValue1, MB_CASE_LOWER), self::CharCase($AValue2, MB_CASE_LOWER), $FPercent);
        return ($FPercent >= $APercent);
    }

    /**
     * @param $AValue
     * @param $ANumber
     * @param string $AInterval
     * @return mixed|null
     */
    public function Cut($AValue, $ANumber, $AInterval = CH_SPEC) {
        if ((new ArrayOf)->FromString($AValue, $AInterval, $FResult) > 0) $FResult = (new ArrayOf)->Value($FResult, $ANumber); else $FResult = null;
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
    public function Copy($AValue, $AStart, $ALength, $ARight = false, $AContinueFormat = null) {
        $FLength = self::Length($AValue);
        if (($FLength > 0) and (new DefaultOf)->IntervalCheck($AStart, 1, $FLength) and ($ALength > 0)) {
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
    public function Replace($AValue, $ASearch, $AReplace, $AParam = null) {
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
    private function ReplaceExecute1($AValue, $ASearch, $AReplace) {
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
    private function ReplaceExecute2($ASearch, $AReplace, &$AResult) {
        $FReplace = (new ArrayOf)->First($AReplace);
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
    private function ReplaceExecute3($AValue, $ASearch, $AReplace) {
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
                } elseif ((new DefaultOf)->TypeCheck($AReplace)) $FResult = array_combine(range($AReplace, $AReplace + (new ArrayOf)->Length($FResult) - 1), array_values($FResult));
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
    public function Add($ASource, $AAppend, $ASeparator = ", ", $AIfExs = false, $AInvert = false) {
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
    public function CharCase($AValue, $AParam = MB_CASE_TITLE) {
        if (is_null($AParam)) return self::From($AValue); else return mb_convert_case($AValue, $AParam, "UTF-8");
    }

    /**
     * @param $AValue
     * @param string $AParam
     * @param null $AResult
     * @return bool
     */
    public function FormatFromUserData($AValue, $AParam = SFFUD_FullName, &$AResult = null) {
        $AResult = null;
        $FResult = false;
        switch ($AParam) {
            case SFFUD_FullName:
                if (((new ArrayOf)->FromString($AValue, CH_SPACE, $FSubResult) == 2) or ((new ArrayOf)->FromString($AValue, CH_BOTTOM_LINE, $FSubResult) == 2)) {
                    $FPatternLatin = "/^[a-zA-Z'`]{3,15}$/";
                    $FPatternCyril = "/^[\p{Cyrillic}]{3,15}$/u";
                    $FFirstName = trim((new ArrayOf)->Value($FSubResult));
                    $FLastName = trim((new ArrayOf)->Value($FSubResult, 2));
                    if ((preg_match($FPatternLatin, $FFirstName) and preg_match($FPatternLatin, $FLastName)) or (preg_match($FPatternCyril, $FFirstName) and preg_match($FPatternCyril, $FLastName))) {
                        $FFirstName = self::CharCase($FFirstName);
                        $FLastName = self::CharCase($FLastName);
                        $AResult = $FFirstName . CH_SPACE . $FLastName;
                        $FResult = true;
                    }
                }
                break;
            case SFFUD_Password:
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
    public function ToFile($AFileName, $AValue, $ANewLine = false) {
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
 * ValueOf
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
    public function DateTimeModify($AValue, $ASecond = 0, $AMinute = 0, $AHour = 0, $ADay = 0, $AWeek = 0, $AMonth = 0, $AYear = 0, $AFormat = "d.m.Y H:i:s") {
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
    public function DateTimePeriod($AStartTime, $AFinishTime, $AFormat = "[Seconds]", $ADefault = null) {
        $FResult = $ADefault;
        $FStartTime = $AStartTime;
        $FFinishTime = $AFinishTime;
        if ((new DefaultOf)->TypeCheck($FStartTime, DIC_DateTime) and (new DefaultOf)->TypeCheck($FFinishTime, DIC_DateTime)) {
            if ((new DefaultOf)->TypeCheck($FStartTime, DIC_TimeOnly) and !(new DefaultOf)->TypeCheck($FFinishTime, DIC_TimeOnly)) $FFinishTime = self::DateTimeConvertFormat($FFinishTime, "H:i:s");
            elseif (!(new DefaultOf)->TypeCheck($FStartTime, DIC_TimeOnly) and (new DefaultOf)->TypeCheck($FFinishTime, DIC_TimeOnly)) $FStartTime = self::DateTimeConvertFormat($FStartTime, "H:i:s");
            if ((new DefaultOf)->TypeCheck($FStartTime, DIC_TimeOnly) and (new DefaultOf)->TypeCheck($FFinishTime, DIC_TimeOnly) and (strtotime($FStartTime) > strtotime($FFinishTime))) {
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
                        if ($FInterval->m > 0) $FFormat = (new StrOf)->Add($FFormat, "[Month]" . CH_SPACE . $AFormat["Month"]);
                        if ($FInterval->d > 0) $FFormat = (new StrOf)->Add($FFormat, "[Day]" . CH_SPACE . $AFormat["Day"]);
                    } elseif ($FInterval->m > 0) {
                        $FFormat = "[Month]" . CH_SPACE . $AFormat["Month"];
                        if ($FInterval->d > 0) $FFormat = (new StrOf)->Add($FFormat, "[Day]" . CH_SPACE . $AFormat["Day"]);
                        if ($FInterval->h > 0) $FFormat = (new StrOf)->Add($FFormat, "[Hour]" . CH_SPACE . $AFormat["Hour"]);
                    } elseif ($FInterval->d > 0) {
                        $FFormat = "[Day]" . CH_SPACE . $AFormat["Day"];
                        if ($FInterval->h > 0) $FFormat = (new StrOf)->Add($FFormat, "[Hour]" . CH_SPACE . $AFormat["Hour"]);
                        if ($FInterval->i > 0) $FFormat = (new StrOf)->Add($FFormat, "[Minute]" . CH_SPACE . $AFormat["Minute"]);
                    } elseif ($FInterval->h > 0) {
                        $FFormat = "[Hour]" . CH_SPACE . $AFormat["Hour"];
                        if ($FInterval->i > 0) $FFormat = (new StrOf)->Add($FFormat, "[Minute]" . CH_SPACE . $AFormat["Minute"]);
                    } elseif ($FInterval->i > 0) {
                        $FFormat = "[Minute]" . CH_SPACE . $AFormat["Minute"];
                        if ($FInterval->s > 0) $FFormat = (new StrOf)->Add($FFormat, "[Second]" . CH_SPACE . $AFormat["Second"]);
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
    private function DateTimePeriodExecute1($AInterval, $AFormat, &$AResult) {
        $AResult = (new StrOf)->Replace($AFormat,
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
    public function DateTimeIntervalCheck($AValue, $AStartTime, $AFinishTime, $AWeekDay = null) {
        if (is_null($AStartTime) and is_null($AFinishTime)) return true; else {
            $FTimeStyle = ((new DefaultOf)->TypeCheck($AStartTime, DIC_TimeOnly) or (new DefaultOf)->TypeCheck($AFinishTime, DIC_TimeOnly));
            if ($FTimeStyle) $FValue = self::DateTimeConvertFormat($AValue, "H:i:s"); else $FValue = $AValue;
            if (is_null($AStartTime)) $FResult = (strtotime($FValue) <= strtotime($AFinishTime));
            elseif (is_null($AFinishTime)) $FResult = (strtotime($AStartTime) <= strtotime($FValue));
            elseif ($FTimeStyle and (strtotime($AStartTime) > strtotime($AFinishTime))) $FResult = ((new DefaultOf)->IntervalCheck(strtotime($FValue), strtotime($AStartTime), strtotime("23:59:59")) or (new DefaultOf)->IntervalCheck(strtotime($FValue), strtotime("00:00:00"), strtotime($AFinishTime)));
            else $FResult = (new DefaultOf)->IntervalCheck(strtotime($FValue), strtotime($AStartTime), strtotime($AFinishTime));
            if ($FResult and !is_null($AWeekDay)) $FResult = (new StrOf)->Pos($AWeekDay, self::DateTimeConvertFormat($AValue, "N")) > 0;
            return $FResult;
        }
    }

    /**
     * @param $AValue
     * @param string $AFormat
     * @param null $ADefault
     * @return false|string|null
     */
    public function DateTimeConvertFormat($AValue, $AFormat = "d.m.Y H:i:s", $ADefault = null) {
        if ((new DefaultOf)->TypeCheck($AValue, DIC_DateTime)) return date($AFormat, strtotime($AValue)); else return $ADefault;
    }

    /**
     * @param $AMin
     * @param $AMax
     * @return int
     */
    public function Random($AMin, $AMax) {
        if ($AMin <= $AMax) return mt_rand($AMin, $AMax); else return $AMin;
    }

    /**
     * @param $AMin
     * @param $AMax
     * @param int $ADefault
     * @return float|int
     */
    public function Percent($AMin, $AMax, $ADefault = 100) {
        if ($AMax <> 0) return round(($AMin * 100) / $AMax); else return $ADefault;
    }

    /**
     * @param $AValue1
     * @param $AValue2
     */
    public function Swap(&$AValue1, &$AValue2) {
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
    public function Distance($ALatFrom, $ALonFrom, $ALatTo, $ALonTo, $AUnit = "km", $ADecimal = 1) {
        $FResult = 0;
        if (($ALatFrom <> $ALatTo) or ($ALonFrom <> $ALonTo)) {
            // Get param and calculate
            $FTheta = $ALonFrom - $ALonTo;
            $FDist = sin(deg2rad($ALatFrom)) * sin(deg2rad($ALatTo)) + cos(deg2rad($ALatFrom)) * cos(deg2rad($ALatTo)) * cos(deg2rad($FTheta));
            $FDist = acos($FDist);
            $FDist = rad2deg($FDist);
            $FMiles = $FDist * 60 * 1.1515;
            // Get result
            if ((new StrOf)->Same($AUnit, "km")) $FResult = ($FMiles * 1.609344); // kilometer
            elseif ((new StrOf)->Same($AUnit, "m")) $FResult = ($FMiles  * 1609.344); // meter
            elseif ((new StrOf)->Same($AUnit, "mi")) $FResult = $FMiles; // miles
            elseif ((new StrOf)->Same($AUnit, "nmi")) $FResult = $FMiles / 1.150779448; // nautical miles
            elseif ((new StrOf)->Same($AUnit, "in")) $FResult = ($FMiles * 63360); // inches
            elseif ((new StrOf)->Same($AUnit, "cm")) $FResult = ($FMiles * 160934.4); // centimeter
            elseif ((new StrOf)->Same($AUnit, "yd")) $FResult = ($FMiles * 1760); // yard
            elseif ((new StrOf)->Same($AUnit, "ft")) $FResult = ($FMiles * 5280); // feet
            else $FResult = $FMiles; // miles
        }
        if ($ADecimal <> 0) $FResult = (new DefaultOf)->ValueFromString($FResult, $ADecimal);
        return $FResult;
    }

}

// Const Get Array Of
const AO_Combine = "AO_Combine";
const AO_Merge = "AO_Merge";
const AO_Column = "AO_Column";
const AO_Cut = "AO_Cut";
const AO_GetKeyAll = "AO_GetKeyAll";
const AO_Chunk = "AO_Chunk";

/**
 * ArrayOf
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
    public function Length($AValue, $ASubLength = false) {
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
    private function LengthExecute1($AValue, &$AResult) {
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
    public function Value($AValue, $ANumber = 1, $ASubValue = false) {
        $FResult = null;
        if (is_array($AValue)) {
            $FCount = self::Length($AValue);
            if (($FCount > 0) and (new DefaultOf)->IntervalCheck($ANumber, 1, $FCount)) {
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
    public function First($AValue) {
        return self::Value($AValue, 1, true);
    }

    /**
     * @param $AFileName
     * @param $AResult
     * @param string $AKey
     * @param null $AInterval
     * @return int
     */
    public function FromFile($AFileName, &$AResult, $AKey = CH_EQUAL, $AInterval = null) {
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
                        if ((new StrOf)->Found($AInterval, $FChar, 1, SF_SameText)) {
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
    private function FromFileExecute1($AText, $AKey, &$AResult) {
        $FText = trim((new StrOf)->From($AText));
        if ((new StrOf)->Length($FText) > 0) {
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
    public function Of($AParam, ...$AValues) {
        $FResult = [];
        $FCountArg = func_num_args() - 1;
        switch ($AParam) {
            case AO_Combine: // Array combine
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
            case AO_Merge:
                foreach ($AValues as $FValue) {
                    if ((new StrOf)->Length($FValue) > 0) {
                        if (is_array($FValue)) $FResult = array_merge($FResult, $FValue); else array_push($FResult, $FValue);
                    }
                }
                break;
            case AO_Column:
                if ($FCountArg > 1) {
                    // Get param
                    $FValues = func_get_arg(1);
                    $FNames = func_get_arg(2);
                    if (is_array($FValues) and !is_null($FNames)) {
                        $FResult = array_column($FValues, $FNames);
                    }
                }
                break;
            case AO_Cut:
                if ($FCountArg > 1) {
                    // Get param
                    $FParam1 = func_get_arg(1);
                    $FParam2 = func_get_arg(2);
                    if ($FCountArg > 2) $FParam3 = func_get_arg(3); else $FParam3 = null;
                    if ($FCountArg > 3) $FParam4 = func_get_arg(4); else $FParam4 = false;
                    // Execute
                    if (is_array($FParam1) and (new DefaultOf)->TypeCheck($FParam2)) {
                        if ($FParam2 >= 0) $FParam2 -= 1;
                        $FResult = array_slice($FParam1, $FParam2, $FParam3, $FParam4);
                        if ($FParam3 == 1) $FResult = self::Value($FResult);
                    }
                }
                break;
            case AO_GetKeyAll:
                foreach ($AValues as $FValue) {
                    if (self::Length($FValue) > 0) {
                        foreach ($FValue as $FSubKey => $FSubValue) {
                            if ((new DefaultOf)->TypeCheck($FSubKey)) $FParam1 = $FSubValue; else $FParam1 = $FSubKey;
                            if ((new StrOf)->Length($FParam1) > 0) {
                                if (is_array($FParam1)) $FResult = array_merge($FResult, $FParam1); else array_push($FResult, $FParam1);
                            }
                        }
                    } elseif ((new StrOf)->Length($FValue) > 0) array_push($FResult, $FValue);
                }
                break;
            case AO_Chunk:
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
    public function FromString($AValue, $AInterval = CH_SPEC, &$AResult = null, $ALimit = null, $AKeys = null) {
        $AResult = [];
        $FResult = 0;
        $FSubInterval = null;
        if (isset($AValue)) {
            if (is_array($AInterval)) {
                foreach ($AInterval as $FKey => $FValue) {
                    if ((new DefaultOf)->TypeCheck($FKey)) {
                        $FInterval = $FValue;
                        $FSubInterval = null;
                    } else {
                        $FInterval = $FKey;
                        $FSubInterval = $FValue;
                    }
                    if ((new StrOf)->Pos($AValue, $FInterval) > 0) $FResult = self::FromString($AValue, $FInterval, $AResult, $ALimit, $AKeys);
                    if ($FResult > 0) break;
                }
            } elseif ((new StrOf)->Length($AValue) > 0) {
                $FValue = (new StrOf)->From($AValue);
                if (is_null($AInterval)) {
                    $FLen = (new StrOf)->Length($FValue);
                    for ($InX = 1; $InX <= $FLen; $InX++) {
                        $AResult[$InX] = (new StrOf)->Copy($FValue, $InX, 1);
                    }
                } else {
                    if (is_null($ALimit)) $AResult = explode($AInterval, $FValue); else $AResult = explode($AInterval, $FValue, $ALimit);
                }
                if (!is_null($AKeys)) $AResult = (new StrOf)->Replace($AResult, null, $AKeys, SR_ArrayKeys);
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
    public function FromStringWithArray($AValue, $AInterval = CH_SPEC, $ALimit = null, $AKeys = null) {
        $FResult = [];
        self::FromString($AValue, $AInterval, $FResult, $ALimit, $AKeys);
        return $FResult;
    }

    /**
     * @param $AValue
     * @param false $AParse
     * @return array|null
     */
    public function FromJSON($AValue, $AParse = false) {
        if (!(new StrOf)->Found($AValue, [CH_BRACE_FIGURE_BEGIN, CH_BRACE_FIGURE_END], 1, null, true)) $FResult = [];
        elseif ($AParse === false) {
            if (is_array($AValue)) {
                $FResult = [];
                foreach ($AValue as $FKey => $FValue) {
                    $FResult[$FKey] = self::FromJSON($FValue);
                }
            } else $FResult = json_decode((new StrOf)->Replace($AValue, CH_PATH, CH_PATH . CH_PATH), JSON_OBJECT_AS_ARRAY);
        } else {
            $FResult = [];
            $FParseKeys = self::Of(AO_GetKeyAll, $AParse);
            if (is_array($AValue)) {
                foreach ($AValue as $FKey => $FValue) {
                    if ((new StrOf)->Found($FParseKeys, $FKey, 1, SF_SameText)) {
                        if (isset($AParse[$FKey])) $FResult = array_merge($FResult, self::FromJSON($FValue, $AParse[$FKey])); else $FResult = array_merge($FResult, self::FromJSON($FValue, null));
                    } elseif (is_array($FValue)) $FResult[$FKey] = self::FromJSON($FValue, $AParse); else $FResult[$FKey] = $FValue;
                }
            } else {
                $FParseJSON = self::FromJSON($AValue);
                if ($FParseJSON) {
                    if (self::Length($FParseKeys) == 0) $FResult = $FParseJSON; else {
                        foreach ($FParseJSON as $FKey => $FValue) {
                            if ((new StrOf)->Found($FParseKeys, $FKey, 1, SF_SameText)) {
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
    public function ToJSON($AValue, $ASubJSON = false) {
        $FResult = $AValue;
        if ($ASubJSON) {
            if (self::Length($FResult) > 0) {
                foreach($FResult as $FKey => $FValue) {
                    if (is_array($FValue)) {
                        $FEncode = json_encode($FValue, JSON_UNESCAPED_UNICODE);
                        if ($FEncode) $FResult[$FKey] = (new StrOf)->Replace($FEncode, [CH_PATH . CH_PATH, CH_PATH . CH_ANTI_PATH], [CH_PATH, CH_ANTI_PATH]); else $FResult[$FKey] = null;
                    }
                }
            }
        } else {
            $FEncode = json_encode($FResult, JSON_UNESCAPED_UNICODE);
            if ($FEncode) $FResult = (new StrOf)->Replace($FEncode, [CH_PATH . CH_PATH, CH_PATH . CH_ANTI_PATH], [CH_PATH, CH_ANTI_PATH]); else $FResult = null;
        }
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AInterval
     * @param bool $ASubArray
     * @return array|mixed|string|null
     */
    public function ToString($AValue, $AInterval, $ASubArray = true) {
        if (self::Length($AValue) > 0) {
            $FResult = null;
            foreach ($AValue as $FValue) {
                if (is_array($FValue)) {
                    if ($ASubArray) $FSubResult = self::ToString($FValue, $AInterval); else $FSubResult = null;
                } else $FSubResult = $FValue;
                if ($FSubResult === $AInterval) $FResult .= $AInterval; else $FResult = (new StrOf)->Add($FResult, $FSubResult, $AInterval);
            }
        } else $FResult = (new StrOf)->From($AValue);
        return $FResult;
    }

    /**
     * @param $AValue
     * @param $AResult
     * @return array|int
     */
    public function FromStringWithKey($AValue, &$AResult) {
        $AResult = [];
        If ((self::FromString($AValue, CH_NET, $FSubResult) > 0) and (self::FromString(self::Value($FSubResult), CH_SPEC, $FKeys) > 0) and (self::FromString(self::Value($FSubResult, 2), CH_SPEC, $FValues) > 0) and (self::Length($FKeys) == self::Length($FValues))) return $AResult = array_combine($FKeys, $FValues);
        return self::Length($AResult);
    }

    /**
     * @param $AValue
     * @return string|null
     */
    public function ToStringWithKey($AValue) {
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
    public function FromFormatNumber($AValue, &$AResult, $AMin = null, $AMax = null, $AType = FILTER_VALIDATE_INT, $AStep = 1, $ASort = true) {
        $AResult = [];
        if (self::FromString($AValue, CH_COMMA, $FResult) > 0) {
            foreach ($FResult as $FValue) {
                $FValue = trim(self::First($FValue));
                if ((new DefaultOf)->TypeCheck($FValue, $AType)) {
                    $FValue = (new DefaultOf)->ValueFromString($FValue);
                    if ((new DefaultOf)->IntervalCheck($FValue, $AMin, $AMax)) array_push($AResult, $FValue);
                } elseif (self::FromString($FValue, CH_MINUS, $FSubResult) == 2) {
                    $FValue1 = self::Value($FSubResult);
                    $FValue2 = self::Value($FSubResult, 2);
                    if ((new DefaultOf)->TypeCheck($FValue1, $AType) and (new DefaultOf)->TypeCheck($FValue2, $AType) and ($FValue1 <= $FValue2)) {
                        foreach (range($FValue1, $FValue2, $AStep) as $FSubValue) {
                            if ((new DefaultOf)->IntervalCheck($FSubValue, $AMin, $AMax)) array_push($AResult, $FSubValue);
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
    public function FromFormatKeys($AValue, $AKeys, &$AResult, $AMax = null) {
        $AResult = [];
        if (((new StrOf)->Length($AValue) > 0) and (self::Length($AKeys) > 0) and (new StrOf)->Found($AValue, $AKeys)) {
            $FText = $AValue;
            if ((new StrOf)->Found($FText, [1, 2, 3, 4, 5, 6, 7, 8, 9])) {
                $FKey = null;
                $FTextLen = (new StrOf)->Length($FText);
                for ($InX = 0; $InX < $FTextLen; $InX++) {
                    if ((new StrOf)->Found($FText[$InX], $AKeys)) {
                        if ((new DefaultOf)->TypeCheck($FKey) and (is_null($AMax) or (new DefaultOf)->IntervalCheck($FKey, 1, $AMax))) $AResult[$FKey] = $FText[$InX];
                        $FKey = null;
                    } elseif ((new StrOf)->Found($FText[$InX], [1, 2, 3, 4, 5, 6, 7, 8, 9, 0])) $FKey = $FKey .$FText[$InX];
                }
                ksort($AResult);
            } else {
                $FTextLen = floor((new StrOf)->Length($FText) / 2) - 1;
                $FCharList = count_chars($FText, 1);
                arsort($FCharList);
                foreach ($FCharList as $FKey => $FValue) {
                    if ($FValue >= $FTextLen) {
                        if (!(new StrOf)->Found(chr($FKey), $AKeys)) $FText = (new StrOf)->Replace($FText, chr($FKey), CH_FREE);
                    } else break;
                }
                $FTextLen = (new StrOf)->Length($FText);
                if (!is_null($AMax)) $FTextLen = min($FTextLen, $AMax);
                for ($InX = 0; $InX < $FTextLen; $InX++) {
                    if ((new StrOf)->Found($FText[$InX], $AKeys)) $AResult[$InX + 1] = $FText[$InX];
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
    public function FromFormatPath($ASource, $APath, &$AResult, $ASeparator = CH_PATH, $AInterval = CH_POINT_COMMA) {
        $AResult = [];
        if ((new ArrayOf)->Length($ASource) > 0) $FResult = $ASource;
        elseif (self::FromString($ASource, $AInterval, $FResult) == 0) $FResult = null;
        if ((new ArrayOf)->Length($FResult) > 0) {
            $FIndex = self::FromString($APath, $ASeparator) + 1;
            foreach ($FResult as $FValue) {
                if ((((new StrOf)->Length($APath) == 0) or ((new StrOf)->Pos($FValue, $APath . $ASeparator) == 1)) and (self::FromString($FValue, $ASeparator, $FSubResult) >= $FIndex)) array_push($AResult, self::Value($FSubResult, $FIndex));
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
    public function Combine($AValues, $AKeys) {
        $FResult = [];
        if ((self::Length($AValues) > 0) and (self::Length($AKeys) > 0)) {
            foreach ($AValues as $FKey => $FValue) {
                if ((new DefaultOf)->TypeCheck($FKey) and is_array($FValue)) $FResult[$FKey] = self::Combine($FValue, $AKeys); else {
                    foreach ($AKeys as $FSubKey => $FSubValue) {
                        if (is_array($FSubValue)) {
                            if ((new StrOf)->Found($FSubValue, $FKey, 1, SF_WithKeySame)) {
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
 * DateTimeOf
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
    public function LocalDateTime($AFormat = "d.m.Y H:i:s", $ATimeZone = "Asia/Tashkent") {
        date_default_timezone_set($ATimeZone);
        return date($AFormat);
    }

    public function TimeAgo($ADate, $AFormat = "[Time] [Time_Text] ago", $ADefault = "long ago", $ATimeText = ["second", "minute", "hour", "day", "month", "year"]) {
        if (isset($ADate)) {
            $FLength = array("60", "60", "24", "30", "12", "10");
            $FTimestamp = strtotime($ADate);
            $FCurrentTime = time();
            if($FCurrentTime >= $FTimestamp) {
                $FDiff     = time()- $FTimestamp;
                for($FIndex = 0; $FDiff >= $FLength[$FIndex] && $FIndex < (new ArrayOf)->Length($FLength) - 1; $FIndex++) {
                    $FDiff = $FDiff / $FLength[$FIndex];
                }
                $FDiff = round($FDiff);
                return (new StrOf)->Replace($AFormat, ["[Time]", "[Time_Text]"], [$FDiff, $ATimeText[$FIndex]]);
            }
        }
        return $ADefault;
    }

}

// Const Get File Info
const SFI_Curl = "SFI_Curl";

/**
 * SystemOf
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
     * @return array|CURLFile|string|string[]
     */
    public function FileInfo($AFileName, $AOptions = PATHINFO_FILENAME) {
        if ($AOptions == SFI_Curl) return curl_file_create($AFileName); else return pathinfo($AFileName, $AOptions);
    }

    public function Values($ADefault = 'UNKNOWN') {
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
        foreach ((new ArrayOf)->FromStringWithArray(shell_exec('ipconfig/all'), 'Description') as $FItem) {
            if ((new StrOf)->Found($FItem, ['Physical Address', 'IPv4 Address'], 1, null, true)) {
                foreach ((new ArrayOf)->FromStringWithArray($FItem, [CH_NEW_LINE => ':']) as $FItem2) {
                    if ((new ArrayOf)->Length($FItem2) > 0) {
                        $FTitle = (new StrOf)->Replace($FItem2[0], [CH_POINT, CH_TRIM], CH_FREE);
                        if ($FTitle === CH_FREE) {
                            $FValue['Description'] = $FItem2[1];
                        } elseif ((new StrOf)->Same($FTitle, 'Physical Address')) {
                            $FValue['Physical Address'] = $FItem2[1];
                        } elseif ((new StrOf)->Same($FTitle, 'IPv4 Address') and !isset($FValue['IPv4 Address'])) {
                            $FValue['IPv4 Address'] = (new StrOf)->Cut($FItem2[1], 1, CH_BRACE_BEGIN);
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
            if (isset($FValue)) $FResult['System'][$FKey] = (new StrOf)->Replace($FValue, 'SerialNumber', CH_FREE);
        }
        // Clear trimming
        if ((new ArrayOf)->Length($FResult) > 0) return (new StrOf)->Replace($FResult, [CH_NEW_LINE, CH_TRIM], CH_FREE); else return $ADefault;
    }
}

// Const Language
const LNG_Execute = "LNGExecute";
const LNG_Execute2 = "LNGExecute2";
const LNG_Execute3 = "LNGExecute3";
const LNG_Execute4 = "LNGExecute4";
const LNG_Execute5 = "LNGExecute5";
const LNG_Skip = "LNGSkip";

/**
 * LanguageOf
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
     * LanguageOf constructor.
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
        if ((new ArrayOf)->FromFile($this->FFileName, $FResult) > 0) {
            $this->FData = $FResult;
        }
        return $this->FData;
    }

    /**
     * @return array|null
     */
    private function CreateLog() {
        $this->FLog = [];
        if ((new ArrayOf)->FromFile($this->FLogFile, $FResult, null) > 0) {
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
        $FText = trim((new StrOf)->From($AText));
        if (!(new StrOf)->Found($this->FLog, $FText, 1, SF_SameText) and (new StrOf)->ToFile($this->FLogFile, $FText, (new ArrayOf)->Length($this->FLog) > 0)) {
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
        if ((new ArrayOf)->Length($AValues) > 0) {
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
        return (new StrOf)->Replace($FResult, CH_SPEC, CH_NEW_LINE);
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
            $FValuesCount = (new ArrayOf)->Length($AValues);
            if ((new ArrayOf)->FromString($AText, $AInterval, $FResult) > 1) {
                foreach ($FResult as $FKey => $FValue) {
                    if ($FValuesCount > 0) {
                        $FStrValuesCount = (new StrOf)->Found($FValue, ["%s", "%d"], 1, SF_GetCount);
                        if ($FStrValuesCount == $FValuesCount) $FResult[$FKey] = $this->Translate($FValue, $AValues); else {
                            $FResult[$FKey] = $this->Translate($FValue, array_slice($AValues, $FIndex, $FStrValuesCount));
                            $FIndex += $FStrValuesCount;
                        }
                    } else $FResult[$FKey] = $this->Translate($FValue, $AValues);
                }
                $FResult = implode($AInterval, $FResult);
                if (!is_null($AIntervalReplace)) $FResult = (new StrOf)->Replace($FResult, $AInterval, $AIntervalReplace);
            } else $FResult = $this->Translate($AText, $AValues);
        } elseif (is_array($AText)) {
            $FResult = $AText;
            foreach ($FResult as $FKey => $FValue) {
                if ((new StrOf)->Same($FKey, LNG_Execute, 95)) $FResult[$FKey] = $this->Execute((new ArrayOf)->Value($FValue), (new DefaultOf)->ValueCheck((new ArrayOf)->Value($FValue, 2), $AValues), (new DefaultOf)->ValueCheck((new ArrayOf)->Value($FValue, 3), $AInterval), (new DefaultOf)->ValueCheck((new ArrayOf)->Value($FValue, 4), $AIntervalReplace));
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
        return (new ArrayOf)->ToString($this->Execute($AText, $AValues, $AInterval, $AFormat), $AFormat);
    }
}

/**
 * MysqlDbOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class MysqlDbOf extends MysqliDb {

    /**
     * MysqlDbOf constructor.
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
            $FResult = (($this->connect() == null) and (((new ArrayOf)->Length($ATableNames) == 0) or ($this->tableExists($ATableNames))));
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $AValues
     * @param $AWhere
     * @param string $ACond
     * @param string $ADefaultProp
     * @return bool|MysqlDbOf
     */
    public function joinOf($AValues, $AWhere, $ACond = "AND", $ADefaultProp = "ID") {
        $FResult = false;
        try {
            if ((new ArrayOf)->Length($AValues) > 0) {
                foreach ($AValues as $FValue) {
                    if ((new ArrayOf)->FromString($FValue, CH_SPEC, $FSubResult) == 3) {
                        $FResult = $this->join((new ArrayOf)->Value($FSubResult), (new ArrayOf)->Value($FSubResult, 2), (new ArrayOf)->Value($FSubResult, 3));
                    }
                }
            }
            if ($FResult and ((new ArrayOf)->Length($AWhere) > 0)) {
                foreach ($AWhere as $WhereKey => $WhereValue) {
                    if (!(new DefaultOf)->TypeCheck($WhereKey)) {
                        if ((new ArrayOf)->Length($WhereValue) > 0) {
                            foreach ($WhereValue as $FKey => $FValue) {
                                if (is_array($FValue)) {
                                    foreach ($FValue as $FSubKey => $FSubValue) {
                                        if (((new StrOf)->Length($FSubKey) > 0) and ((new StrOf)->Length($FSubValue) > 0)) $FResult = $this->joinWhere($WhereKey, $FKey, $FSubValue, $FSubKey, $ACond);
                                    }
                                } elseif (is_null($FValue)) {
                                    $FResult = $this->joinWhere($WhereKey, $FKey, $FValue, "IS", $ACond);
                                } elseif (!(new DefaultOf)->TypeCheck($FKey)) $FResult = $this->joinWhere($WhereKey, $FKey, $FValue, CH_EQUAL, $ACond);
                                elseif ((new StrOf)->Length($FValue) > 0) $FResult = $this->joinWhere($WhereKey, $FValue);
                            }
                        } elseif ((new DefaultOf)->TypeCheck($WhereValue) or ($ADefaultProp <> "ID")) $FResult = $this->joinWhere($WhereKey, $ADefaultProp, $WhereValue, CH_EQUAL, $ACond);
                        elseif ((new StrOf)->Length($WhereValue) > 0) $FResult = $this->joinWhere($WhereKey, $WhereValue);
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
        if ((new ArrayOf)->Length($FResult) > 0) {
            foreach ($FResult as $FKey => $FValue) {
                if ((new StrOf)->Found(["AND", "OR"], $FKey, 1, SF_SameText) and ((new ArrayOf)->Length($FValue) > 0)) {
                    $FSubQuery = $this->subQuery();
                    $this->whereSub($FSubQuery, $FValue, $FKey, $ADefaultProp);
                    $FSubQuery->_buildCondition(CH_FREE, $FSubQuery->_where);
                    $FResult[$FKey] = CH_BRACE_BEGIN . (new StrOf)->Replace(trim($FSubQuery->replacePlaceHolders($FSubQuery->_query, $FSubQuery->_bindParams)), CH_SPACE . CH_SPACE, CH_SPACE) . CH_BRACE_END;
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
        if (isset($ASubQuery) and ((new StrOf)->Length($AValues) > 0)) {
            if (is_array($AValues)) {
                foreach ($AValues as $FKey => $FValue) {
                    if (is_array($FValue)) {
                        foreach ($FValue as $FSubKey => $FSubValue) {
                            if (((new StrOf)->Length($FSubKey) > 0) and ((new StrOf)->Length($FSubValue) > 0)) $ASubQuery->where($FKey, $FSubValue, $FSubKey, $ACond);
                        }
                    } elseif (is_null($FValue)) {
                        $ASubQuery->where($FKey, $FValue, "IS", $ACond);
                    } elseif (!(new DefaultOf)->TypeCheck($FKey)) {
                        if ((new StrOf)->Found(["AND", "OR"], $FKey, 1, SF_SameText)) $ASubQuery->where($FValue); else $ASubQuery->where($FKey, $FValue, CH_EQUAL, $ACond);
                    } elseif ((new StrOf)->Length($FValue) > 0) $ASubQuery->where($FValue);
                }
            } elseif ((new StrOf)->Found(["AND", "OR"], $ADefaultProp, 1, SF_SameText)) $ASubQuery->where($AValues);
            elseif ((new DefaultOf)->TypeCheck($AValues) or ($ADefaultProp <> "ID")) $ASubQuery->where($ADefaultProp, $AValues, CH_EQUAL, $ACond);
            elseif ((new StrOf)->Length($AValues) > 0) $ASubQuery->where($AValues);
        }
        return $ASubQuery;
    }

    /**
     * @param $AValues
     * @param string $ACond
     * @param string $ADefaultProp
     * @return bool|MysqlDbOf
     */
    public function whereOf($AValues, $ACond = "AND", $ADefaultProp = "ID") {
        $FResult = false;
        if (is_array($AValues)) {
            $FValues = $this->whereSubParse($AValues, $ADefaultProp);
            foreach ($FValues as $FKey => $FValue) {
                if (is_array($FValue)) {
                    foreach ($FValue as $FSubKey => $FSubValue) {
                        if (((new StrOf)->Length($FSubKey) > 0) and ((new StrOf)->Length($FSubValue) > 0)) $FResult = $this->where($FKey, $FSubValue, $FSubKey, $ACond);
                    }
                } elseif (is_null($FValue)) {
                    $FResult = $this->where($FKey, $FValue, "IS", $ACond);
                } elseif (!(new DefaultOf)->TypeCheck($FKey)) {
                    if ((new StrOf)->Found(["AND", "OR"], $FKey, 1, SF_SameText)) $FResult = $this->where($FValue); else $FResult = $this->where($FKey, $FValue, CH_EQUAL, $ACond);
                } elseif ((new StrOf)->Length($FValue) > 0) $FResult = $this->where($FValue);
            }
        } elseif ((new StrOf)->Found(["AND", "OR"], $ADefaultProp, 1, SF_SameText)) $FResult = $this->where($AValues);
        elseif ((new DefaultOf)->TypeCheck($AValues) or ($ADefaultProp <> "ID")) $FResult = $this->where($ADefaultProp, $AValues, CH_EQUAL, $ACond);
        elseif ((new StrOf)->Length($AValues) > 0) $FResult = $this->where($AValues);
        return $FResult;
    }

    /**
     * @param $AValues
     * @return bool|MysqlDbOf
     */
    public function orderByOf($AValues) {
        $FResult = false;
        try {
            if ((new StrOf)->Length($AValues) > 0) {
                if (is_array($AValues)) {
                    foreach ($AValues as $FKey => $FValue) {
                        if ((new StrOf)->Found(["ASC", "DESC"], $FValue, 1, SF_SameText)) $FResult = $this->orderBy($FKey, $FValue); else $FResult = $this->orderBy($FValue);
                    }
                } else $FResult = $this->orderBy($AValues);
            }
        } catch (Exception $e) {
        }
        return $FResult;
    }

    /**
     * @param $AValues
     * @return MysqlDbOf|false
     */
    public function groupByOf($AValues) {
        $FResult = false;
        try {
            if ((new StrOf)->Length($AValues) > 0) {
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
     * @return array|MysqlDbOf|string|null
     */
    public function getOf($ATableName, $AColumns = "*", $ANumRows = null, $AFormat = null, $AFormatClearSubArray = true, $AValueFromString = null, $AJSONParseField = null) {
        $FResult = null;
        try {
            // Get result
            if ($ANumRows === 1) $FResult = $this->getOne($ATableName, $AColumns); else $FResult = $this->get($ATableName, $ANumRows, $AColumns);
            if ($FResult) {
                // Get JSON parsed
                if ((new StrOf)->Length($AJSONParseField) > 0) $FResult = (new ArrayOf)->FromJSON($FResult, $AJSONParseField);
                // Get format
                if (($ANumRows <> 1) and !is_null($AFormat)) {
                    foreach ($FResult as $FKey => $FValue) {
                        if (is_array($FValue)) {
                            $FResult[$FKey] = (new StrOf)->Replace($AFormat, array_keys($FValue), array_values($FValue));
                            if ((new DefaultOf)->TypeCheck($FKey)) $FResult[$FKey] = (new StrOf)->Replace($FResult[$FKey], CH_NUMBER, $FKey + 1);
                            if (($AValueFromString === true) or ((new ArrayOf)->Length($AValueFromString) > 0)) $FResult[$FKey] = (new DefaultOf)->ValueFromString($FResult[$FKey], (new DefaultOf)->ValueCheck($AValueFromString[0], 2), (new DefaultOf)->ValueCheck($AValueFromString[1], CH_FREE));
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
            if ((new StrOf)->Length($ATableName) > 0) {
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
            if ((new ArrayOf)->Length($FValues) > 0) {
                if ($AMultiData === false) {
                    if (!is_null($ADuplicate)) $this->onDuplicate($ADuplicate);
                    if (!is_null($AOtherData)) $FValues = (new ArrayOf)->Of(AO_Merge, $FValues, $AOtherData);
                    if (!is_null($ACombineKey)) $FValues = (new ArrayOf)->Combine($FValues, $ACombineKey);
                    $FID = $this->insert($ATableName, (new ArrayOf)->ToJSON($FValues, true));
                    if ($FID) {
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
            $FAutoCommit = (!isset($this->_transaction_in_progress) || !$this->_transaction_in_progress);
            if($FAutoCommit) $this->startTransaction();
            foreach ($AMultiInsertData as $FValue) {
                if (((new ArrayOf)->Length($ADataKeys) > 0) and ((new ArrayOf)->Length($ADataKeys) == (new ArrayOf)->Length($FValue))) $FValue = array_combine($ADataKeys, $FValue);
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
        if (((new ArrayOf)->Length($AValues) > 0) and $this->whereOf($AID)) {
            try {
                if ($this->update($ATableName, (new ArrayOf)->ToJSON($AValues, true), $ANumRows)) $FResult = true;
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
 * TelegramOf
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
     * TelegramOf constructor.
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
            if ((new ArrayOf)->Length($AButtons) > 0) $FButtons = $this->buildInlineKeyBoard($this->GetBuildButtonsExecute1($AButtons, $AInline));
        } elseif ((new ArrayOf)->Length($AButtons) > 0) $FButtons = $this->buildKeyBoard($this->GetBuildButtonsExecute1($AButtons), false, true); else $FButtons = $this->buildKeyBoardHide();
        return $FButtons;
    }

    /**
     * @param $AButtons
     * @param bool $AInline
     * @return array
     */
    private function GetBuildButtonsExecute1($AButtons, $AInline = false) {
        $FButton = [];
        foreach ($AButtons as $FValue) {
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
        $FValue = (new StrOf)->From($AValue);
        if (((new ArrayOf)->FromString($FValue, CH_EQUAL, $FResult) == 2) or ((new ArrayOf)->FromString($FValue, CH_SPEC, $FResult) == 2)) {
            $FNameStr = (new ArrayOf)->Value($FResult);
            $FValueStr = (new ArrayOf)->Value($FResult, 2);
            if ($AInline) $FResult = $this->buildInlineKeyboardButton($FNameStr, CH_FREE, $FValueStr); else {
                if ((new StrOf)->Same($FValueStr, "[PHONE]")) $FResult = $this->buildKeyboardButton($FNameStr, true);
                elseif ((new StrOf)->Same($FValueStr, "[LOCATION]")) $FResult = $this->buildKeyboardButton($FNameStr, false, true); else $FResult = $this->buildKeyboardButton($FValue);
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
        $this->valid = (!empty($this->getData()) and (((new ArrayOf)->Length($ATypes) == 0) or in_array($this->getUpdateTypeOf(), $ATypes)));
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
            if ((new ArrayOf)->Length($FResult) > 0) $FResult = range("A", "Z")[(new ArrayOf)->First($FResult)]; else $FResult = null;
        } else $FResult = $this->Text();
        return (new StrOf)->CharCase($FResult, $ACharCase);
    }

    /**
     * @param false $APhoneValid
     * @return bool|null
     */
    public function ContactOf($APhoneValid = false) {
        $FResult = (new DefaultOf)->ValueCheck($this->getData()["message"]["contact"], null);
        if ($FResult and $APhoneValid) $FResult = (new StrOf)->Copy($this->PhoneOf(), 1, 1) == 9;
        return $FResult;
    }

    /**
     * @return string
     */
    public function PhoneOf() {
        return (new StrOf)->Copy($this->getData()["message"]["contact"]["phone_number"], 9, 9, true);
    }

    /**
     * @param string $ADefault
     * @return string
     */
    public function FullName($ADefault = "none") {
        if ($this->getUpdateTypeOf() === self::POLL_ANSWER) {
            $FData = $this->getData()["poll_answer"]["user"];
            $FResult = trim(trim($FData["first_name"]) . CH_SPACE . trim($FData["last_name"]));
            if ((new StrOf)->Length($FResult) == 0) $FResult = trim($FData["username"]);
        } else {
            $FResult = trim(trim($this->FirstName()) . CH_SPACE . trim($this->LastName()));
            if ((new StrOf)->Length($FResult) == 0) $FResult = trim($this->Username());
        }
        if ((new StrOf)->Length($FResult) == 0) $FResult = trim($ADefault);
        return $FResult;
    }

    /**
     * @param string $AAction
     * @param null $AChatID
     * @return mixed|null
     */
    public function sendChatActionOf($AAction = "typing", $AChatID = null) {
        $FResult = $this->sendChatAction(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "action" => $AAction]);
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) return $FResult; return null;
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
        if ((new StrOf)->Length($AText) > 0) {
            if (is_null($AButtons)) $FResult = $this->sendMessage(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "text" => $AText, "parse_mode" => "html"]); else $FResult = $this->sendMessage(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, $AInline), "text" => $AText, "parse_mode" => "html"]);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) {
            if ($ADeleteOk and (new DefaultOf)->TypeCheck($AGetMessageID)) $this->deleteMessageOf($AGetMessageID);
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
        if ((new StrOf)->Length($APhoto) > 0) {
            if (is_null($AButtons)) $FResult = $this->sendPhoto(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "photo" => $APhoto, "caption" => $ACaption, "parse_mode" => "html"]); else $FResult = $this->sendPhoto(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, $AInline), "photo" => $APhoto, "caption" => $ACaption, "parse_mode" => "html"]);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) {
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
        if (((new StrOf)->Length($ADocument) > 0) and ((new StrOf)->Pos($ADocument, CH_SPEC) == 0)) {
            if (file_exists($ADocument)) $FDocument = (new SystemOf)->FileInfo($ADocument, SFI_Curl); else $FDocument = $ADocument;
            if (is_null($AButtons)) $FResult = $this->sendDocument(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "document" => $FDocument, "caption" => $ACaption, "parse_mode" => "html"]); else $FResult = $this->sendDocument(["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, $AInline), "document" => $FDocument, "caption" => $ACaption, "parse_mode" => "html"]);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) {
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
        if (((new StrOf)->Length($AQuestion) > 0) and (new DefaultOf)->IntervalCheck((new ArrayOf)->Length($AOptions), 2, 10)) {
            foreach ($AOptions as $FKey => $FValue) $AOptions[$FKey] = (new StrOf)->Copy($FValue, 1, 100, false, CH_POINT_THREE);
            $FContent = ["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "question" => (new StrOf)->Copy($AQuestion, 1, 300, false, CH_POINT_THREE), "options" => json_encode($AOptions), "is_anonymous" => false];
            if (isset($ACorrect) and ($ACorrect > 0)) $FContent["correct_option_id"] = $ACorrect;
            if (isset($APeriod) and ($APeriod > 0)) $FContent["open_period"] = $APeriod;
            if (!is_null($AButtons)) $FContent["reply_markup"] = $this->GetBuildButtons($AButtons);
            $FResult = $this->sendPoll($FContent);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) {
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
        if ((new StrOf)->Length($APhoto) > 0) {
            $FContent = ["chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "animation" => $APhoto];
            if ((new StrOf)->Length($ACaption) > 0) {
                $FContent["caption"] = $ACaption;
                $FContent["parse_mode"] = "html";
            }
            if (!is_null($AButtons)) $FContent["reply_markup"] = $this->GetBuildButtons($AButtons, true);
            if ($AReplyMessage) $FContent["reply_to_message_id"] = $this->MessageID();
            $FResult = $this->sendAnimation($FContent);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) {
            if ($ADeleteOk and (new DefaultOf)->TypeCheck($AGetMessageID)) $this->deleteMessageOf($AGetMessageID);
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
        if ((new StrOf)->Length($AText) > 0) {
            if (is_null($AButtons)) $FResult = $this->editMessageText(["message_id" => (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), "chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "text" => $AText, "parse_mode" => "html"]); else $FResult = $this->editMessageText(["message_id" => (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), "chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, true), "text" => $AText, "parse_mode" => "html"]);
        }

        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) return $FResult; return null;
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
        if ((new StrOf)->Length($ACaption) > 0) {
            if (is_null($AButtons)) $FResult = $this->editMessageCaption(["message_id" => (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), "chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "caption" => $ACaption, "parse_mode" => "html"]); else $FResult = $this->editMessageCaption(["message_id" => (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), "chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, true), "caption" => $ACaption, "parse_mode" => "html"]);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) return $FResult; return null;
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
        if ((new StrOf)->Length($AMedia) > 0) {
            if (is_null($AButtons)) $FResult = $this->editMessageMedia(["message_id" => (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), "chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "media" => $this->buildMedia($AMediaType, $AMedia, $ACaption, "html")]); else $FResult = $this->editMessageMedia(["message_id" => (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), "chat_id" => (new DefaultOf)->ValueCheck($AChatID, $this->ChatID()), "reply_markup" => $this->GetBuildButtons($AButtons, true), "media" => $this->buildMedia($AMediaType, $AMedia, $ACaption, "html")]);
        }
        if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) return $FResult; return null;
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
        } else $this->deleteMessageOfExecute1($AChatID, (new DefaultOf)->ValueCheck($AMessageID, $this->MessageID()), $AExclude, $ATypes);
    }

    /**
     * @param $AChatID
     * @param $AMessageID
     * @param null $AExclude
     * @param array $ATypes
     * @return mixed|null
     */
    private function deleteMessageOfExecute1($AChatID, $AMessageID, $AExclude = null, $ATypes = []) {
        $FChatID = (new ArrayOf)->First($AChatID);
        $FMessageID = (new ArrayOf)->First($AMessageID);
        if ((new DefaultOf)->TypeCheck($FMessageID) and !(new StrOf)->Found($AExclude, $FMessageID, 1, SF_SameText) and (((new ArrayOf)->Length($ATypes) == 0) or in_array($this->getUpdateType(), $ATypes))) return $this->deleteMessage(["chat_id" => (new DefaultOf)->ValueCheck($FChatID, $this->ChatID()), "message_id" => $FMessageID]); else return null;
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
            if ($FData["ok"] and (is_null($ACompareName) or ((new StrOf)->Pos($FFileName, $ACompareName) > 0))) {
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
        if ((new ArrayOf)->Length($AChatID) > 1) {
            $FResult = [];
            foreach ($AChatID as $FValue) $FResult = (new ArrayOf)->Of(AO_Merge, $FResult, $this->getChatMemberOf($FValue, $AUserID));
            if ((new ArrayOf)->Length($FResult) == 0) $FResult = false;
            elseif ((new ArrayOf)->Length($FResult) == 1) $FResult = (new ArrayOf)->First($FResult);
        } else {
            $FChatID = trim((new ArrayOf)->First($AChatID));
            if ((new StrOf)->Length($FChatID) > 0) {
                $FResult = $this->getChatMember(["chat_id" => $FChatID, "user_id" => (new DefaultOf)->ValueCheck($AUserID, $this->UserID())]);
                if ((new DefaultOf)->ValueCheck($FResult["ok"], false)) $FResult = $FResult["result"]["status"]; else $FResult = false;
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
 * FtpOf
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
     * FtpOf constructor.
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

/**
 * EmbedOf
 *
 * @category  Class
 * @package   Utility
 * @author    AlgolTeam <algol.team.uz@gmail.com>
 * @copyright Copyright (c) 2021
 * @link      https://github.com/algol-team
 */

class EmbedOf extends Embed {

    /**
     * @param Crawler|null $crawler
     * @param ExtractorFactory|null $extractorFactory
     */
    public function __construct(Crawler $crawler = null, ExtractorFactory $extractorFactory = null) {
        parent::__construct($crawler, $extractorFactory);
    }

}