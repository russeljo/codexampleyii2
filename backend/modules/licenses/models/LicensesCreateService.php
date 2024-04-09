<?php

namespace backend\modules\licenses\models;

use common\models\db\licenses\Licenses;
use common\models\db\Users;
use Yii;
use yii\db\Exception;


/** Создает лицензию
 * Class LicensesCreateService
 * @package backend\modules\licenses\models
 * @author Ruslan Mukhamedyarov
 */
class LicensesCreateService
{
    private Licenses $model;
    private ?Users   $user;
    private array    $errors = [];

    public function __construct()
    {
        $this->model = new Licenses();
        $this->user = Yii::$app->user->getId() ? Yii::$app->user->getIdentity() : null;
    }

    /**
     * Сохраняет модель
     * @return bool
     * @throws Exception
     * @author Ruslan Mukhamedyarov
     */
    public function create(): bool
    {

        $this->checkUser();

        $transaction = Yii::$app->db->beginTransaction();

        $this->model->author_id = $this->user->id;
        $this->model->key       = LicenseGenerateService::create();

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
     * @return void
     * @throws Exception
     * @author Ruslan Mukhamedyarov
     */
    private function checkUser() {

        if ($this->user === null) {
            $this->errors[] = 'Необходима авторизация!';
            throw new Exception(implode('; ', $this->getErrors()));
        }

    }


    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}