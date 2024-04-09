<?php
use backend\modules\licenses\controllers\LicensesController;
use backend\modules\licenses\models\LicensesForm;
use common\models\db\licenses\Licenses;
use common\yii\validators\StringValidator;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var View         $this */
/* @var LicensesForm $form */
?>

<div class="row">
    <div class="col-3">
        Создатель:
    </div>
    <div class="col">
        <?php echo $form->_model->author->getFio(); ?>
    </div>
</div>
<div class="row">
    <div class="col-3">
        Ключ:
    </div>
    <div class="col">
        <?php echo $form->_model->key; ?>
    </div>
</div>
<div class="row">
    <div class="col-3">
        Дата создания
    </div>
    <div class="col">
        <?php echo Yii::$app->formatter->asDateTime($form->_model->date_create); ?>
    </div>
</div>
<div class="row">
    <div class="col">
        <br>
    </div>
</div>

<?php $activeForm = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $activeForm->field($form, LicensesForm::ATTR_DESCRIPTION)->textarea(['maxlength' => Licenses::DESCRIPTION_LENGTH, 'rows' => 10]) ?>
        </div>
    </div>
    <div class="form-group">

        <input type="hidden"
               name="<?= Yii::$app->request->csrfParam; ?>"
               value="<?= Yii::$app->request->getCsrfToken(); ?>"
        />

        <?= Html::a(
            'Отмена',
            Url::to(LicensesController::getUrlRoute(LicensesController::ACTION_INDEX)),
            ['class' => 'btn btn-danger']
        ); ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>