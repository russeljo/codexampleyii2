<?php

namespace common\models\db\licenses;

use common\models\db\Users;
use common\models\queries\licenses\LicensesQuery;
use common\yii\validators\DateValidator;
use common\yii\validators\ExistValidator;
use common\yii\validators\RequiredValidator;
use common\yii\validators\StringValidator;
use common\yii\validators\TrimValidator;
use common\yii\validators\UniqueValidator;
use common\yii\validators\UuidValidator;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Справочник лицензий
 * Class Licenses
 * @package сommon\models\db\licenses
 *
 * @property string $id
 * @property string $date_create - дата создания записи
 * @property string $author_id - id пользователя создателя
 * @property string $description - текст лицензии
 * @property string $key - ключ
 *
 * @property Users $author - создатель
 * @property Users $user - alias for author
 *
 * @author Ruslan Mukhamedyarov
 */
class Licenses extends ActiveRecord
{
    public const TABLE_NAME = 'licenses';

    public const ATTR_ID          = 'id';
    public const ATTR_DATE_CREATE = 'date_create';
    public const ATTR_AUTHOR_ID   = 'author_id';
    public const ATTR_DESCRIPTION = 'description';
    public const ATTR_KEY         = 'key';

    public const RELATION_AUTHOR = 'author';
    public const RELATION_USER   = 'user';

    public const DESCRIPTION_LENGTH = StringValidator::VARCHAR_LENGTH_1000;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [static::ATTR_ID, UuidValidator::class],
            [static::ATTR_ID, UniqueValidator::class],

            [static::ATTR_DATE_CREATE, DateValidator::class, DateValidator::ATTR_FORMAT => DateValidator::FORMAT_DATATIM],

            [static::ATTR_AUTHOR_ID, RequiredValidator::class],
            [static::ATTR_AUTHOR_ID, UuidValidator::class],
            [
                static::ATTR_AUTHOR_ID,
                ExistValidator::class,
                ExistValidator::ATTR_TARGET_CLASS => Users::class,
                ExistValidator::ATTR_TARGET_ATTRIBUTE => Users::ATTR_ID
            ],

            [static::ATTR_DESCRIPTION, StringValidator::class],
            [static::ATTR_DESCRIPTION, TrimValidator::class],
            [static::ATTR_DESCRIPTION, StringValidator::class, StringValidator::ATTR_MAX => static::DESCRIPTION_LENGTH],

            [static::ATTR_KEY, RequiredValidator::class],
            [static::ATTR_KEY, StringValidator::class],
            [static::ATTR_KEY, TrimValidator::class],
            [static::ATTR_KEY, StringValidator::class, StringValidator::ATTR_MAX => StringValidator::VARCHAR_LENGTH],

        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            self::ATTR_ID          => 'id записи',
            self::ATTR_DATE_CREATE => 'Дата создания',
            self::ATTR_AUTHOR_ID   => 'id создателя',
            self::ATTR_DESCRIPTION => 'Текст лицензии',
            self::ATTR_KEY         => 'Ключ',
            self::RELATION_AUTHOR  => 'Создатель ключа',
            self::RELATION_USER    => 'Создатель ключа',
        ];
    }

    /**
     * Gets query for [[Author]]
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Users::class, [Users::ATTR_ID => static::ATTR_AUTHOR_ID]);
    }

    /**
     * Gets query for [[User]]
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(Users::class, [Users::ATTR_ID => static::ATTR_AUTHOR_ID]);
    }

    /**
     * @return LicensesQuery
     */
    public static function find(): LicensesQuery
    {
        return new LicensesQuery(get_called_class());
    }

}