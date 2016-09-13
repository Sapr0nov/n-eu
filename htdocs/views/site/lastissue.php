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

$this->title = Yii::t('pages_title','lastissue');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
		<?php 
		$issue = new Issue();
		$lastYear = $issue->find()->max('year');
		$res = $issue->find('year=:year AND published = :published', array(':year'=>$lastYear,':published'=>'1'))->one();

		if ($res->year==$lastYear) {
			echo $res->year." - ".$res->num." <a href = \"".$res->issue_file."\"> Скачать выпуск</a>";
		}else{
			echo"Выпуск ещё не доступен.";
			}

			//		if (Lang::getCurrent()->url == 'ru')

		?>
    </p>

</div>
