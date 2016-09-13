<?php
use yii\helpers\Html;
use \yii\db\ActiveRecord;
use app\frontend\models\Lang;

class Issue extends ActiveRecord
{
    public static function tableName()
    {
		return 'issue';
	}
}

$this->title = Yii::t('pages_title','listissue');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
		<?php 
		$issue = new Issue();
		$res = $issue->find('published = :published', array(':published'=>'1'))->all();
		if ($res<>NULL) {
			foreach ($res as $row) {
				printf ("Выпуск №: %s - %s, <a href =\"/%s\"> Скачать выпуск</a><br/>", $row->year, $row->num, $row->issue_file);
			}
		}
		?>
    </p>

</div>
