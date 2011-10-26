<?php

/**
 * ChangePasswordForm class.
 * ChangePasswordForm is the data structure for keeping
 * change password form data.
 */
class ChangePasswordForm extends CFormModel
{
	public $old_password;
	public $new_password;
        public $new_password_repeat;


	/**
	 * Declares the validation rules.
	 * The rules state that survey and questionGroup are required.
	 */
	public function rules()
	{
		return array(
			// survey and questionGroup are required
			array('old_password, new_password, new_password_repeat', 'required'),
                        array('new_password', 'match', 'pattern'=>'/^(?=.{8,})(?=.*\d)(?=.*\W)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/','message'=>'Password should be 8 or more characters long and should contain atleast one uppercase alphabet, one lowercase alphabet, one special character(!@#$%^&*=+_-),one number and no spaces.'),
                        array('new_password_repeat', 'compare', 'compareAttribute' => 'new_password'),
                        array('old_password', 'authenticate'),
		);
	}
        
        public function authenticate($attribute,$params)
        {
            $user = User::model()->findByPk(Yii::app()->user->id);
            $password = User::model()->hashPassword($this->old_password, $user->salt);
            if($password != $user->password)
                $this->addError('old_password','Incorrect password.');
        }

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'old_password'=>'Old Password',
                        'new_password'=>'New Password',
                        'new_password_repeat' => 'Repeat New Password',
		);
	}

}
