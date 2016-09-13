<?php
use yii\helpers\Html;

$this->title = Yii::t('pages_title','about');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
		<?php echo Yii::t('pages','about'); ?>
    </p>

</div>
