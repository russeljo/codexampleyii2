<?php

namespace common\models\db;

use common\models\queries\UsersIdentityConfirmQuery;
use common\yii\validators\DefaultValueValidator;
use common\yii\validators\ExistValidator;
use common\yii\validators\IntegerValidator;
use common\yii\validators\NumberValidator;
use common\yii\validators\RequiredValidator;
use common\yii\validators\StringValidator;
use common\yii\validators\TrimValidator;
use common\yii\validators\UniqueValidator;
use common\yii\validators\UuidValidator;

use JetBrains\PhpStorm\ArrayShape;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/** Подтверждение личности
 * Модель UsersIdentityConfirm
 * Class UsersIdentityConfirm
 * @package common\models\db
 *
 * @property string  $id          'ID записи'
 * @property string  $date_create 'Дата и время добавления'
 * @property string  $date_modify 'Дата и время изменения'
 * @property string  $user_id     'id файла (фото паспорта)'
 * @property string  $file_id     'id пользователя'
 * @property int     $status      'Статус, 0: новый, 1: подтверждено, 2: отклонено, 3: в архиве'
 * @property string  $comment     'Комментарий, причина отказа'
 *
 * @property Users $user
 * @property Files $file
 *
 * @author Ruslan Mukhamedyarov
 */
class UsersIdentityConfirm extends ActiveRecord
{
    const TABLE_NAME = 'users_identity_confirm';

    const ATTR_ID          = 'id';
    const ATTR_DATE_CREATE = 'date_create';
    const ATTR_DATE_MODIFY = 'date_modify';
    const ATTR_USER_ID     = 'user_id';
    const ATTR_FILE_ID     = 'file_id';
    const ATTR_STATUS      = 'status';
    const ATTR_COMMENT     = 'comment';

    const STATUS_NEW       = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_REJECTED  = 2;
    const STATUS_ARCHIVED  = 3;

    const RELATION_USER = 'user';
    const RELATION_FILE = 'file';

    /**
     * @return string
     * @author Ruslan Mukhamedyarov
     */
    public static function tableName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @return string[]
     * @author Ruslan Mukhamedyarov
     */
    public static function primaryKey(): array
    {
        return [static::ATTR_ID];
    }

    /**
     * Автоматическая установка значений "по-умолчанию"
     *
     * @return array
     *
     * @author Ruslan Mukhamedyarov
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => [static::ATTR_DATE_CREATE],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => [static::ATTR_DATE_MODIFY],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     *
     * @author Ruslan Mukhamedyarov
     */
    public function rules(): array
    {
        return [
            [static::ATTR_ID, UuidValidator::class],
            [static::ATTR_ID, UniqueValidator::class],

            [static::ATTR_USER_ID, UuidValidator::class],
            [static::ATTR_USER_ID, RequiredValidator::class],
            [
                static::ATTR_USER_ID,
                ExistValidator::class,
                ExistValidator::ATTR_TARGET_CLASS => Users::class,
                ExistValidator::ATTR_TARGET_ATTRIBUTE => Users::ATTR_ID
            ],

            [static::ATTR_FILE_ID, UuidValidator::class],
            [static::ATTR_FILE_ID, RequiredValidator::class],
            [
                static::ATTR_FILE_ID,
                ExistValidator::class,
                ExistValidator::ATTR_TARGET_CLASS => Files::class,
                ExistValidator::ATTR_TARGET_ATTRIBUTE => Files::ATTR_ID
            ],

            [
                static::ATTR_STATUS,
                DefaultValueValidator::class,
                DefaultValueValidator::ATTR_VALUE => static::STATUS_NEW,
            ],
            [
                static::ATTR_STATUS,
                IntegerValidator::class,
                NumberValidator::ATTR_MIN => static::STATUS_NEW,
                NumberValidator::ATTR_MAX => static::STATUS_ARCHIVED
            ],

            [static::ATTR_COMMENT, StringValidator::class],
            [static::ATTR_COMMENT, TrimValidator::class]
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @author Ruslan Mukhamedyarov
     */
    public function attributeLabels(): array
    {
        return [
            static::ATTR_ID          => 'ID записи',
            static::ATTR_DATE_CREATE => 'Дата и время добавления',
            static::ATTR_DATE_MODIFY => 'Дата и время изменения',
            static::ATTR_USER_ID     => 'id файла (фото паспорта)',
            static::ATTR_FILE_ID     => 'id пользователя',
            static::ATTR_STATUS      => 'Статус',
            static::ATTR_COMMENT     => 'Комментарий, причина отказа',
        ];
    }


    /**
     * Получение списка статусов
     * @return array
     *
     * @author Ruslan Mukhamedyarov
     */
    #[ArrayShape([
        self::STATUS_NEW => "string",
        self::STATUS_CONFIRMED => "string",
        self::STATUS_REJECTED => "string",
        self::STATUS_ARCHIVED => "string"
    ])] public static function getStatusVariants(): array
    {
        return [
            static::STATUS_NEW       => 'Новый',
            static::STATUS_CONFIRMED => 'Подтвержден',
            static::STATUS_REJECTED  => 'Отклонен',
            static::STATUS_ARCHIVED  => 'В архиве'
        ];
    }

    /**
     * Получение текстового статуса
     * @return string
     *
     * @author, Ruslan Mukhamedyarov
     */
    public function getStatusVariantsLabel(): string
    {
        return (self::getStatusVariants())[$this->{static::ATTR_STATUS}];
    }



    /**
     * @return ActiveQuery
     *
     * @author Ruslan Mukhamedyarov
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(Users::class, [Users::ATTR_ID => static::ATTR_USER_ID]);
    }

    /**
     * @return ActiveQuery
     *
     * @author Ruslan Mukhamedyarov
     */
    public function getFile(): ActiveQuery
    {
        return $this->hasOne(Files::class, [Files::ATTR_ID => static::ATTR_FILE_ID]);
    }

    /**
     * {@inheritdoc}
     * @return UsersIdentityConfirmQuery the active query used by this AR class.
     *
     * @author Ruslan Mukhamedyarov
     */
    public static function find(): UsersIdentityConfirmQuery
    {
        return new UsersIdentityConfirmQuery(get_called_class());
    }
}