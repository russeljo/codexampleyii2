<?php

use common\enums\db\TablesEnum;
use common\enums\dbFields\CommonFieldsEnum;
use common\enums\dbFields\IndexSuffixesEnum;
use common\types\UuidTypeTrait;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%licenses}}`.
    Поля:
    id - uuid,
    date_create - timestamp now(),
    author_id   - id uuid + fk на users
    description - text null (т.е. может быть пустым) - Текст лицензии,
    key         - varchar(255) not null unique - Ключ лицензии
 */
class m231225_141041_create_licenses_table extends Migration
{
    use UuidTypeTrait;

    public const TABLE_NAME = 'licenses';

    private const ATTR_ID          = 'id';
    private const ATTR_DATE_CREATE = 'date_create';
    private const ATTR_AUTHOR_ID   = 'author_id';
    private const ATTR_DESCRIPTION = 'description';
    private const ATTR_KEY         = 'key';

    private const PK = self::TABLE_NAME . '_id-pk';

    private const FK_USER =
        self::TABLE_NAME . '__'
        . self::ATTR_AUTHOR_ID . '-'
        . TablesEnum::USERS_TABLE_NAME . '__'
        . CommonFieldsEnum::ATTR_ID;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(static::TABLE_NAME, [
            static::ATTR_ID        => $this->uuidPK()->comment('ID записи'),
            self::ATTR_DATE_CREATE => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания записи'),
            self::ATTR_AUTHOR_ID   => $this->uuid()->notNull()->comment('Создатель - id пользователя'),
            self::ATTR_DESCRIPTION => $this->text()->null()->comment('Текст лицензии'),
            self::ATTR_KEY         => $this->string(255)->notNull()->unique()->comment('Ключ лицензии'),
        ]);

        $this->addPrimaryKey(static::PK, static::TABLE_NAME, static::ATTR_ID);
        $this->addCommentOnTable(static::TABLE_NAME, 'Справочник лицензий');

        $this->addForeignKey(
            static::FK_USER,
            static::TABLE_NAME,
            static::ATTR_AUTHOR_ID,
            TablesEnum::USERS_TABLE_NAME,
            CommonFieldsEnum::ATTR_ID
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(static::FK_USER, static::TABLE_NAME);
        $this->dropPrimaryKey(static::PK, static::TABLE_NAME);
        $this->dropTable(static::TABLE_NAME);
    }
}
