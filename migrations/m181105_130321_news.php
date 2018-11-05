<?php

use yii\db\Migration;

/**
 * Class m181105_130321_news
 */
class m181105_130321_news extends Migration
{
   

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('news',[
            'hash'=>$this->string(32)->comment('Ключ нвости'),
            'title'=>$this->string(200)->comment('Заголовок'),
            'description'=>$this->text()->comment('Описание'),
            'link'=>$this->string(100)->comment('Ссылка'),
            'date'=>$this->datetime()->comment('Дата создания'),
            'cat'=>$this->integer()->comment('Категория')->defaultValue(0),
        ]);
        $this->addPrimaryKey('hashnews','news',['hash']);
        $this->createIndex('news_date','news','date');
        $this->createIndex('news_cat','news','cat');
    }

    public function down()
    {
        $this->dropTable('news');
        return true;
    }

}
