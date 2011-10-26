<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $profile
 * @property integer $department_id
 *
 * The followings are the available model relations:
 * @property Answer[] $answers
 * @property State[] $states
 * @property Department $department
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'tbl_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, salt, email, department_id', 'required'),
			array('department_id', 'numerical', 'integerOnly'=>true),
			array('username, password, salt, email', 'length', 'max'=>128),
			array('profile', 'safe'),
                        array('username', 'match', 'pattern'=>'/^([a-z0-9_])+$/'),
                        //array('password', 'match', 'pattern'=>'/^(?=.{8,})(?=.*\d)(?=.*\W)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/','message'=>'Password should be 8 or more characters long and should contain atleast one uppercase alphabet, one lowercase alphabet, one special character(!@#$%^&*=+_-),one number and no spaces.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, salt, email, profile, department_id', 'safe', 'on'=>'search'),
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
			'answers' => array(self::HAS_MANY, 'Answer', 'user_id'),
                        'states' => array(self::HAS_MANY, 'State', 'user_id'),
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
			'username' => 'Username',
			'password' => 'Password',
			'salt' => 'Salt',
			'email' => 'Email',
			'profile' => 'Profile',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('profile',$this->profile,true);
		$criteria->compare('department_id',$this->department_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}


        public function validatePassword($password)
        {
                return $this->hashPassword($password,$this->salt)===$this->password;
        }
        public function hashPassword($password,$salt)
        {
                return md5($salt.$password);
        }
}