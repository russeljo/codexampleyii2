<?php

namespace backend\modules\licenses;

use common\yii\base\Module;

/**
 * Модуль Лицензии
 * Class LicensesModule
 * @package backend\modules\licenses
 * @author Ruslan Mukhamedyarov
 */
class LicensesModule extends Module
{
	const ID_CONFIG = 'licenses';

	/**
	 * @var string
	 */
	public $controllerNamespace = 'backend\modules\licenses\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
	}
}
