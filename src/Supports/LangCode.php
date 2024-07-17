<?php

namespace Carlin\TranslateDrivers\Supports;

class LangCode
{
    /** 自动检测 */
    public const Auto = 'auto';
    /** 中文 */
    public const ZH = 'zh';
    /** 繁体中文 */
    public const ZH_TW = 'zh-tw';
    /** 英语 */
    public const  EN = 'en';
    /** 德语 */
    public const  DE = 'de';
    /** 俄语 */
    public const  RU = 'ru';
    /** 葡萄牙语 */
    public const  PT = 'pt';
    /** 西班牙语 */
    public const  ES = 'es';
    /** 日语 */
    public const  JA = 'ja';
    /** 法语 */
    public const  FR = 'fr';
    /** 印尼 */
    public const  MS = 'ms';
    /** 泰语 */
    public const  TH = 'th';
    /** 韩语 */
    public const  KO = 'ko';
    /** 越语 */
    public const  VI = 'vi';
    /** 印尼语 */
    public const  ID = 'id';

    public static function getDescription($value): string
    {
        if ($value === self::ZH) {
            return '中文';
        }
        if ($value === self::ZH_TW) {
            return '繁体中文';
        }
        if ($value === self::EN) {
            return '英语';
        }
        if ($value === self::DE) {
            return '德语';
        }
        if ($value === self::RU) {
            return '俄语';
        }
        if ($value === self::PT) {
            return '葡萄牙语';
        }
        if ($value === self::ES) {
            return '西班牙语';
        }
        if ($value === self::JA) {
            return '日语';
        }
        if ($value === self::FR) {
            return '法语';
        }
        if ($value === self::ID) {
            return '马来语';
        }
        if ($value === self::TH) {
            return '泰语';
        }
        if ($value === self::KO) {
            return '韩语';
        }
        if ($value === self::VI) {
            return '越语';
        }
        if ($value === self::Auto) {
            return '自动检测';
        }
        if ($value === self::MS) {
            return '马来语';
        }

		return $value;
    }
}
