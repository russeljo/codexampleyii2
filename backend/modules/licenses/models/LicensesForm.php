<?php

namespace backend\modules\licenses\models;

use common\models\db\licenses\Licenses;
use common\yii\validators\StringValidator;
use common\yii\validators\TrimValidator;

use yii\base\Model;
use yii\db\Exception;

class LicensesForm extends Model
{

    public ?string $description;
    public const ATTR_DESCRIPTION = 'description';

    public Licenses $_model;

    /**
     * @param Licenses|null $model
     * @param $config
     * @throws Exception
     */
    public function __construct(Licenses $model = null, $config = [])
    {
        if (null === $model)
            throw new Exception('Модель лицензии не загружена');

        $this->_model      = $model;
        $this->description = $model->description;

        parent::__construct($config);

    }

    public function formName(): string
    {
        return '';
    }


    public function rules(): array
    {
        return [
            [static::ATTR_DESCRIPTION, StringValidator::class],
            [static::ATTR_DESCRIPTION, TrimValidator::class],
            [static::ATTR_DESCRIPTION, StringValidator::class, StringValidator::ATTR_MAX => Licenses::DESCRIPTION_LENGTH],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            self::ATTR_DESCRIPTION => 'Текст лицензии (до '. Licenses::DESCRIPTION_LENGTH .' знаков)',
        ];
    }

    /**
     * @return bool
     * @throws Exception
     * @author Ruslan Mukhamedyarov
     */
    public function save(): bool
    {
        $result = false;

        if (true === $this->validate()) {
            $service = new LicensesUpdateService($this);
            $result = $service->save();
        }

        return $result;
    }
}