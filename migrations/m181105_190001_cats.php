<?php

use yii\db\Migration;

/**
 * Class m181105_190001_cats
 */
class m181105_190001_cats extends Migration
{
   
    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('cats',[
            'id'=>$this->primaryKey()->comment('Ключик'),
            'title'=>$this->string(150)->notNull()->comment('Заголовок'),
        ]);
    }

    public function down()
    {
        $this->dropTable('cats');
        return true;
    }
    
}
