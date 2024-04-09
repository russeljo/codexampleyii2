<?php

namespace backend\modules\identity\models;

use yii\data\ActiveDataProvider;

/**
 * Class IdentityProvider
 * @package backend\modules\identity\models
 * @author Ruslan Mukhamedyarov
 */
class IdentityProvider
{
	public IdentitySearch $searchModel;
	public ActiveDataProvider $dataProvider;

	public function __construct()
	{
		$this->searchModel = new IdentitySearch();
		$this->dataProvider = $this->searchModel->search();
	}
}