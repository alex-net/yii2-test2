<?php 

namespace app\models;

class NewsModel extends \yii\db\ActiveRecord
{
	public static function tableName()
	{
		return 'news';
	}

	public function rules()
	{
		return [
			['hash','existindb'],
		];
	}
	// проверяем наличие записи в базе 
	public function existindb($attr,$params)
	{
		if (self::find()->where([$attr=>$this->$attr])->count())
			$this->addError($attr,'запись уже существует ');
	}

	public function saveParsedData()
	{
		return $this->validate() && $this->save();
	}


	public static function getAllNews()
	{
		return self::find()->orderBy(['date'=>SORT_DESC]);
	}
}