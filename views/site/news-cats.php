<?php 
use \yii\helpers\Html;
?>
<h2>Категории</h2>

<?php $f=\yii\widgets\ActiveForm::begin();?>

<?=$f->field($m,'title');?>
<?=\yii\helpers\Html::submitButton('Сохранить',['class'=>'btn btn-success','name'=>'act','value'=>'save']);?>
<?php if ($m->id):?>
	<?=\yii\helpers\Html::submitButton('Бахнуть',['class'=>'btn btn-danger','name'=>'act','value'=>'kill']);?>
<?php endif;?>
<?php \yii\widgets\ActiveForm::end();?>


<?=\yii\grid\GridView::widget([
		'dataProvider'=>$p,
		'columns'=>[['attribute'=>'title','content'=>function($m,$k,$i,$c){return Html::a($m->title,['','edit'=>$m->id]);}]],
	]); ?>