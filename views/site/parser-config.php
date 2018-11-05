<h2>Настройки парсера</h2>

<?php 
$f=\yii\widgets\ActiveForm::begin();?>
<?=$f->field($m,'interval');?>
<?=$f->field($m,'words')->textarea(['rows'=>5,'value'=>$m->WordsAsText]);?>
<?=$f->field($m,'feeds')->textarea(['rows'=>5,'value'=>$m->FeedsAsText]);?>
<?=yii\helpers\Html::submitButton('Сохранить');?>
<?php \yii\widgets\ActiveForm::end();?>