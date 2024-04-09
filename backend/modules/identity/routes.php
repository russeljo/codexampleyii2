<?php

use backend\modules\identity\controllers\IdentityController;
use common\helpers\UuidHelper;

$uuidRegex = UuidHelper::REGEX;

$baseRoute = '/identity';

return [
	"$baseRoute/<id:{$uuidRegex}>/reject"  => IdentityController::routeId(IdentityController::ACTION_REJECT),
	"$baseRoute/<id:{$uuidRegex}>/confirm" => IdentityController::routeId(IdentityController::ACTION_CONFIRM),
];