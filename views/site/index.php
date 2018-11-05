<?php
use \yii\widgets\ListView;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <h2>Новости</h2>

    <?=ListView::widget([
            'dataProvider'=>$p,
            'itemView'=>'news-row',
        ]); ?>
</div>
