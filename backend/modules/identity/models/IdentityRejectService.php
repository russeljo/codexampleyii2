<?php
namespace backend\modules\identity\models;

use common\models\db\UsersIdentityConfirm;
use Yii;

use Exception;

/**
 * Class IdentityRejectService
 * @package backend\modules\identity\models
 * @author Ruslan Mukhamedyarov
 */
class IdentityRejectService extends AbstractIdentityResult
{
    const NOTIFICATION_MESSAGE = 'Подтверждение личности отклонено!';

    public function __construct(UsersIdentityConfirm $model, string $comment)
    {
        parent::__construct($model, $comment);
    }


    public function execute()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (true === $this->mutator->reject($this->comment)) {

                $message = self::NOTIFICATION_MESSAGE;
                $message .= ' <br>Причина отказа: ' . $this->comment;

                $this->sendNotificationToUser($message);

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