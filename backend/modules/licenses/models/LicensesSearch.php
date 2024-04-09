<?php

namespace backend\modules\licenses\models;

use common\models\db\licenses\Licenses;
use common\models\db\Users;
use common\yii\validators\StringValidator;
use common\yii\validators\TrimValidator;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Class LicensesSearch
 * @package backend\modules\licenses\models
 * @author Ruslan Mukhamedyarov
 */
class LicensesSearch extends Model
{
    public ?string $author = null;
    public const  ATTR_AUTHOR = 'author';

    public ?string $date_create = null;
    public const  ATTR_DATE_CREATE = 'date_create';

    public ?string $key = null;
    public const  ATTR_KEY = 'key';

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [static::ATTR_AUTHOR, TrimValidator::class],
            [static::ATTR_AUTHOR, StringValidator::class, StringValidator::ATTR_MAX => StringValidator::VARCHAR_LENGTH],

            [static::ATTR_KEY, TrimValidator::class],
            [static::ATTR_KEY, StringValidator::class, StringValidator::ATTR_MAX => StringValidator::VARCHAR_LENGTH],

            [static::ATTR_DATE_CREATE, StringValidator::class, StringValidator::ATTR_MAX => StringValidator::VARCHAR_LENGTH],
        ];
    }

    public function formName(): string
    {
        return '';
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @author Ruslan Mukhamedyarov
     */
    public function search($params): ActiveDataProvider
    {
        $query = Licenses::find()
            ->joinWith(Licenses::RELATION_AUTHOR);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => [Licenses::ATTR_DATE_CREATE => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes[static::ATTR_AUTHOR] = [
            'asc'  => [Users::TABLE_NAME . '.' . Users::ATTR_LAST_NAME => SORT_ASC],
            'desc' => [Users::TABLE_NAME . '.' . Users::ATTR_LAST_NAME => SORT_DESC],
        ];

        $this->load($params);

        if (true === $this->validate()) {
            $query->andFilterWhere(['ilike', new Expression(
                'CONCAT_WS(\' \','
                . Users::TABLE_NAME . '.' . Users::ATTR_LAST_NAME . ','
                . Users::TABLE_NAME . '.' . Users::ATTR_FIRST_NAME . ','
                . Users::TABLE_NAME . '.' . Users::ATTR_SECOND_NAME
                . ')'
            ), $this->author]);

            $query->andFilterWhere(['ilike', Licenses::TABLE_NAME . '.' . Licenses::ATTR_KEY, $this->key]);

            $query->andFilterWhere(['ilike', new Expression('CAST(' . Licenses::TABLE_NAME . '.' . static::ATTR_DATE_CREATE . ' AS text)'), $this->date_create]);
        }

        return $dataProvider;
    }
}