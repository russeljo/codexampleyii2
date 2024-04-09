<?php

use backend\modules\licenses\controllers\LicensesController;

use backend\modules\licenses\models\LicensesForm;
use yii\helpers\Html;
use yii\web\View;

/* @var View         $this */
/* @var LicensesForm $form */

$this->title = 'Обновить лицензию';
$this->params['breadcrumbs'][] = [
	'label' => 'Справочник лицензий',
	'url'   => [LicensesController::ACTION_INDEX]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">

					<h1><?= Html::encode($this->title) ?></h1>

					<?= $this->render('_form', [
						'form' => $form,
					]) ?>
				</div>
			</div>
		</div>
	</div>
</div>