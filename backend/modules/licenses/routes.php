<?php

use backend\modules\licenses\controllers\LicensesController;
use common\helpers\UuidHelper;

$uuidRegex = UuidHelper::REGEX;

$baseRoute = "/licenses";

return [
    "$baseRoute"                          => LicensesController::routeId(LicensesController::ACTION_INDEX),
    "$baseRoute/create"                   => LicensesController::routeId(LicensesController::ACTION_CREATE),
    "$baseRoute/<id:{$uuidRegex}>/update" => LicensesController::routeId(LicensesController::ACTION_UPDATE),
];