<?php

namespace backend\modules\identity\models;

use common\models\db\UsersIdentityConfirm;
use common\models\mutators\UsersIdentityConfirmMutator;
use common\services\notification\NotificationService;
use Exception;

/**
 * Class AbstractIdentityResult
 * @package backend\modules\identity\models
 * @author Ruslan Mukhamedyarov
 */
abstract class AbstractIdentityResult
{
	public UsersIdentityConfirm $model;
    public string $comment;
    public UsersIdentityConfirmMutator $mutator;

	public function __construct(UsersIdentityConfirm $model, string $comment = '')
	{
        $this->model   = $model;
        $this->comment = $comment;
		$this->mutator = new UsersIdentityConfirmMutator($model);
	}

	//Выполнение основных действий
	abstract public function execute();

    /**
     * Отправляет уведомление пользователю
     * @throws Exception
     */
    public function sendNotificationToUser(string $message): void
    {
        if (!NotificationService::create($message, $this->model->user_id))
            throw new Exception('Не получилось обновить подтверждение личности. Ошибка отправки уведомления.');

	}

}