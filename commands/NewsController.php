<?php 

namespace app\commands;

use yii\helpers\Console;
use Yii;
class NewsController extends \yii\console\Controller
{
	public $defaultAction = 'parse';
	/** 
		Стягивание новстей в буферную таблицу  
	*/
	public function actionParse()
	{
		$pc= new \app\models\ParserConfigurator();
		if (empty($pc['feeds']))
			echo $this->ansiFormat('Ошибка!',Console::BG_RED)." Ленты отсуствуют!\n";

		
		$fn=Yii::getAlias(Yii::$app->params['paser-starter']);
		$ls=file_exists($fn)?file_get_contents($fn):time();
		
		if (time()-$ls<$pc['interval']*60)
			return;


		$co=0;
		foreach($pc['feeds'] as $f){
			echo $this->ansiFormat("Обработка ленты: ",Console::FG_BLUE).$this->ansiFormat($f,Console::BOLD)."\n";
			$res=file_get_contents($f);
			if ($res && ($xml=simplexml_load_string($res)) && $xml->channel->item->count()) // удалось прочитать .. 
				foreach($xml->channel->item as $el){
					$inc=true;
					if (!empty($pc['words'])){
						$inc=false;
						foreach($pc['words'] as $w){
							$s1=strtolower( $el->title.$el->description);
							$s2=strtolower($w);
							if (strpos($s1, $s2)!==false){
								$inc=true;
								break;
							}
						}

					}
					// добавляем строку в базу. 
					if ($inc){
						$m=new \app\models\NewsModel([
							'hash'=>md5($el->link),
							'title'=>$el->title,
							'description'=>$el->description,
							'link'=>$el->link,
							'date'=>date('c',strtotime($el->pubDate)),
						]);
						if ($m->saveParsedData()){
							echo "Новость '{$el->title}' добавлена в базу \n";
							$co++;
						}


					}
					//echo $el->title."\n";// ->chanel->item->
				}
			

			//echo $res."\n";

		}
		if ($co)
			echo $this->ansiFormat("Процесс добавления завершон. Добавлено новстей: $co \n",Console::FG_GREEN);

		file_put_contents($fn, time());
		
		return \yii\console\ExitCode::OK;
	}
}