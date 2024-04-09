<?php

namespace backend\modules\licenses\models;

use common\models\db\licenses\Licenses;
use Yii;
use yii\db\Exception;

/**
 * Class LicensesUpdateService
 * @package backend\modules\licenses\models
 * @author Ruslan Mukhamedyarov
 */
class LicensesUpdateService
{

    private Licenses     $model;
    private LicensesForm $form;
    private array        $errors = [];

    public function __construct(LicensesForm $form)
    {
        $this->model = $form->_model;
        $this->form  = $form;
    }

    /** Обновляет модель
     * @return bool
     * @throws Exception
     * @author Ruslan Mukhamedyarov
     */
    public function save(): bool
    {

        $transaction = Yii::$app->db->beginTransaction();

        $this->model->description = $this->form->description;

        if ($this->model->validate() && $this->model->save()) {

            $transaction->commit();

        } else {

            $transaction->rollBack();

            $this->errors = $this->model->getFirstErrors();
            throw new Exception(implode(' <br> ', $this->getErrors()));
        }

        return true;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}