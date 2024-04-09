<?php

namespace backend\modules\identity\models;

use common\models\db\UsersIdentityConfirm;

use yii\data\ActiveDataProvider;

/**
 * Class IdentitySearch
 * @package backend\modules\identity\models
 * @author Ruslan Mukhamedyarov
 */
class IdentitySearch
{
    /**
     * @return ActiveDataProvider
     * @author Ruslan Mukhamedyarov
     */
	public function search(): ActiveDataProvider
	{
		//Получаем список сделок для перехода прав доли актива
		$query = UsersIdentityConfirm::find()
			->joinWith(UsersIdentityConfirm::RELATION_USER)
			->joinWith(UsersIdentityConfirm::RELATION_FILE)
			->andWhere(['=',  UsersIdentityConfirm::TABLE_NAME . '.' . UsersIdentityConfirm::ATTR_STATUS, UsersIdentityConfirm::STATUS_NEW])
        ;

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => [UsersIdentityConfirm::ATTR_DATE_CREATE => SORT_DESC]]
		]);

		return $dataProvider;
	}
}