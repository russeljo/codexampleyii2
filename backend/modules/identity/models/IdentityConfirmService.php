<?php
namespace backend\modules\identity\models;

use common\models\db\UsersIdentityConfirm;
use Yii;

use Exception;

/**
 * Class IdentityConfirmService
 * @package backend\modules\identity\models
 * @author Ruslan Mukhamedyarov
 */
class IdentityConfirmService extends AbstractIdentityResult
{
    const NOTIFICATION_MESSAGE = 'Ваша личность подтверждена';

    public function __construct(UsersIdentityConfirm $model)
    {
        parent::__construct($model);
    }


    public function execute()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (true === $this->mutator->confirm()) {

                $this->sendNotificationToUser(self::NOTIFICATION_MESSAGE);

                $transaction->commit();

            } else {
                throw new Exception('Не получилось обновить подтверждение личности');
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }

}