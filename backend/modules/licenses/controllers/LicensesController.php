<?php

namespace backend\modules\licenses\controllers;

use backend\modules\licenses\models\LicensesCreateService;
use backend\modules\licenses\models\LicensesForm;
use backend\modules\licenses\models\LicensesSearch;

use common\models\db\licenses\Licenses;
use common\yii\web\Controller;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер модуля Лицензии
 * Class LicensesController
 * @author Ruslan Mukhamedyarov
 */
class LicensesController extends Controller
{
    public const ACTION_INDEX  = 'index';
    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    static::ACTION_CREATE => ['POST'],
                ]
            ]
        ];
    }


    /**
     * @return string
     * @author Ruslan Mukhamedyarov
     */
    public function actionIndex(): string
    {
        $searchModel = new LicensesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(static::ACTION_INDEX, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return Response|string
     * @author Ruslan Mukhamedyarov
     */
    public function actionCreate(): Response|string
    {
        try {
            if ((new LicensesCreateService())->create()) {
                Yii::$app->session->setFlash('success', 'Ключ создан');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ошибка создания ключа. ' . $e->getMessage());
        }

        return $this->redirect([static::ACTION_INDEX]);

    }

    /**
     * @param string $id
     * @return Response|string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     * @author Ruslan Mukhamedyarov
     */
    public function actionUpdate(string $id): Response|string
    {
        $form = new LicensesForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post())) {

            try {

                if ($form->save()) {
                    Yii::$app->session->setFlash('success', 'Лицензия обновлена');
                    return $this->redirect([static::ACTION_INDEX]);
                }

            } catch (\Exception $e) {

                Yii::$app->session->setFlash('error', 'Ошибка обновления лицензии. ' . $e->getMessage());

            }
        }

        return $this->render(static::ACTION_UPDATE, [
            'form' => $form,
        ]);
    }

    /**
     * @param string $id
     * @return Licenses
     * @throws NotFoundHttpException
     * @author Ruslan Mukhamedyarov
     */
    private function findModel(string $id): Licenses
    {
        if (($model = Licenses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}