<?php

namespace bupy7\pages\models;

use Yii;
use bupy7\pages\Module;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class PageTranslate extends ActiveRecord
{
    
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'catalog',
                'slugAttribute' => 'message',
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */

     public static function tableName()
    {
//        return Yii::$app->getModule('pages')->tableNameTranslate;
        return Yii::$app->getModule('pages')->tableNameTransMsg;
    }
   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['translation'], 'string', 'max' => 65535],
            [['category', 'message'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'category' => Module::t('CATEGORY'),
            'message' => Module::t('MESSAGE'),
            'language' => Module::t('LANGUAGE'),
            'translation' => Module::t('TRANSLATION'),
        ];
    }
}
