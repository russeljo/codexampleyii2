<?php

namespace backend\modules\identity\controllers;

use backend\controllers\IndexController;

use backend\modules\identity\models\IdentityCommentForm;
use backend\modules\identity\models\IdentityConfirmService;
use backend\modules\identity\models\IdentityRejectService;
use common\models\db\UsersIdentityConfirm;
use common\yii\web\Controller;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class IdentityController extends Controller
{
	const ACTION_CONFIRM = 'confirm';
	const ACTION_REJECT = 'reject';

	public function behaviors(): array
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::Class,
					'actions' => [
						static::ACTION_CONFIRM => ['POST'],
						static::ACTION_REJECT => ['POST']
					],
				],
			]
		);
	}

    /**
     * @param string $id
     * @return Response
     * @throws NotFoundHttpException
     * @author Ruslan Mukhamedyarov
     */
	public function actionConfirm(string $id): Response
    {
		$service = new IdentityConfirmService($this->findModel($id));

		try {
			$service->execute();
			Yii::$app->session->setFlash('success', 'Личность подтверждена. Уведомление отправлено.');
		} catch (\Exception $exception) {
			Yii::$app->session->setFlash('error', $exception->getMessage());
		}

		return $this->redirect(Url::to(IndexController::getUrlRoute(IndexController::ACTION_INDEX)));
	}

    /**
     * @param string $id
     * @return Response
     * @throws NotFoundHttpException
     * @author Ruslan Mukhamedyarov
     */
	public function actionReject(string $id): Response
    {
		$model = $this->findModel($id);

		$form = new IdentityCommentForm();
		$form->load(Yii::$app->request->post());

		if ($form->validate()) {
			try {
				$service = new IdentityRejectService($model, $form->comment);
				$service->execute();

				Yii::$app->session->setFlash('success', 'Подтверждение личности отклонено. Уведомление отправлено.');
			} catch (\Exception $exception) {
				Yii::$app->session->setFlash('error', $exception->getMessage());
			}
		}

		return $this->redirect(Url::to(IndexController::getUrlRoute(IndexController::ACTION_INDEX)));
	}

    /**
     * @param string $id
     * @return UsersIdentityConfirm
     * @throws NotFoundHttpException
     * @author Ruslan Mukhamedyarov
     */
	protected function findModel(string $id): UsersIdentityConfirm
    {
		$model = UsersIdentityConfirm::find()
			->andWhere([UsersIdentityConfirm::ATTR_STATUS => UsersIdentityConfirm::STATUS_NEW])
			->andWhere([UsersIdentityConfirm::ATTR_ID => $id])
			->one();

		if ($model !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}