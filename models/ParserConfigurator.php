<?php 

namespace app\models;

use Yii;

class ParserConfigurator extends \yii\base\Model
{
	/// интервал запуска в минутах
	public $interval=2;
	// список клчевых слов ...
	public $words=[];
	// список лент .
	public $feeds=[];

	public function init()
	{
		parent::init();
		$fn=Yii::getAlias(Yii::$app->params['parserconf']);
		if (file_exists($fn)){
			$f=json_decode(file_get_contents($fn),true);
			foreach(array_keys($this->attributes) as $a)
				if (!empty($f[$a]))
					$this->$a=$f[$a];
			
		}
	}

	public function attributeLabels()
	{
		return [
			'interval'=>'Интервал проверки лент, мин',
			'words'=>'Ключевые слова',
			'feeds'=>'Источники',
		];
	}

	public function getWordsAsText()
	{
		return implode("\n",$this->words);
	}

	public function getFeedsAsText()
	{
		return implode("\n", $this->feeds);
	}

	public function rules ()
	{
		return [
			['interval',\yii\validators\NumberValidator::className(),'min'=>1],
			
			[['words','feeds'],\yii\validators\FilterValidator::className(),'filter'=>[$this,'str2array']],
			//[['words','feeds'],'isstringarray','skipOnEmpty'=>false],
			[['interval','words','feeds'],'required'],
		];
	}
	private function trimmer($s)
	{
		return preg_replace('#^[\s,.;:]*(.*?)[\s,.:;]*$#i', '$1', $s);
	}
	// преобразуем строку в массив с вырезанием . пробелов . и т.п. 
	public function str2array($v)
	{
		$v=array_map([$this,'trimmer'],explode("\n",$v));
		foreach ($v as $x=>$y)
			if (empty($y))
				unset($v[$x]);
		return $v;
	}
	// валидация массива .
	public function isstringarray($attr,$params)
	{
		if (empty($this->$attr))
			$this->addError($attr,'Нужно заполнить поле '.$attr);
		Yii::info($this->$attr,'validattr '.$attr);
	}

	/** 
		сохраняем. данные ..
	*/
	public function saveConfig($post)
	{
		if (!$this->load($post) || !$this->validate())
			return false;

		$res=json_encode($this->attributes);

		//Yii::info($this->attributes,'attrs');
		
		$f=fopen(Yii::getAlias(Yii::$app->params['parserconf']), 'w');
		if ($f){
			fwrite($f, $res);
			fclose($f);
		}

		//Yii::info(Yii::getAlias('parserconf'));
		return true;
	}

}