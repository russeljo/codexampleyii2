<?php

namespace backend\modules\licenses\models;

/**
 * Генерирует лицензионный ключ
 * Class LicenseGenerateService
 * @package common\services\licenses
 * @author Ruslan Mukhamedyarov
 */
class LicenseGenerateService
{
    private const MIN = 0;
    private const MAX = 8;

    /**
     * Генерация ключа
     * @return string
     * @author Ruslan Mukhamedyarov
     */
    public static function create(): string
    {
        return mb_strtoupper(implode('-', [
            mb_substr(md5(mt_rand()), static::MIN, static::MAX),
            mb_substr(md5(mt_rand()), static::MIN, static::MAX),
            mb_substr(md5(mt_rand()), static::MIN, static::MAX),
            mb_substr(md5(mt_rand()), static::MIN, static::MAX)
        ]));
    }
}