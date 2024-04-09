<?php
namespace backend\modules\identity\models;

use common\yii\validators\RequiredValidator;
use common\yii\validators\StringValidator;
use common\yii\validators\TrimValidator;

use yii\base\Model;

/**
 * Форма с комментарием для подтверждения личности
 * Class IdentityCommentForm
 * @package backend\modules\identity\models
 * @author Ruslan Mukhamedyarov
 */
class IdentityCommentForm extends Model
{
	public $comment;
	const ATTR_COMMENT = 'comment';

	public function formName()
	{
		return '';
	}

	public function rules()
	{
		return [
			[static::ATTR_COMMENT, TrimValidator::class],
			[static::ATTR_COMMENT, RequiredValidator::class, RequiredValidator::ATTR_MESSAGE => 'Заполните причину отказа'],
			[static::ATTR_COMMENT, StringValidator::class, StringValidator::ATTR_MAX => StringValidator::VARCHAR_LENGTH_1000]
		];
	}
}