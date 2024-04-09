<?php

declare (strict_types=1);

namespace backend\modules\identity;

use yii\base\Module;

/**
 * Class IdentityModule
 * @package backend\modules\identity
 * @author Ruslan Mukhamedyarov
 */
class IdentityModule extends Module
{
    public const MODULE_ID = 'identity';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\identity\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}