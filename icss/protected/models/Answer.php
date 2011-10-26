<?php

/**
 * This is the model class for table "tbl_answer".
 *
 * The followings are the available columns in table 'tbl_answer':
 * @property integer $id
 * @property integer $question_id
 * @property string $content
 * @property integer $survey_id
 * @property integer $user_id
 * @property integer $department_id
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Survey $survey
 * @property Question $question
 * @property Department $department
 */
class Answer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Answer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_answer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_id, survey_id, user_id, department_id,content', 'required'),
			array('question_id, survey_id, user_id, department_id', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, question_id, content, survey_id, user_id, department_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'survey' => array(self::BELONGS_TO, 'Survey', 'survey_id'),
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
			'department' => array(self::BELONGS_TO, 'Department', 'department_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'question_id' => 'Question',
			'content' => 'Content',
			'survey_id' => 'Survey',
			'user_id' => 'User',
			'department_id' => 'Department',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('survey_id',$this->survey_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('department_id',$this->department_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}