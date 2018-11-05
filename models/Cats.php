<?php 

namespace app\models;

class Cats extends \yii\db\ActiveRecord
{
	public function attributeLabels()
	{
		return [
			'title'=>'Заголовок категории',
		];
	}

	public function rules()
	{
		return [
			['title','required'],
			['id','number','min'=>0],
		];
	}
	// охранение данных из формы 
	public function todo($data,$act='save')
	{
		if (!$this->load($data))
			return false;
		switch($act){
			case 'save':
				return $this->validate() && $this->save();		
			break;
			case 'kill':
				$this->delete();
			break;
		}
		return true;
	}

	public static function allCats()
	{
		return self::find();
	}
}