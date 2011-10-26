<?php

/**
 * AnalysisForm class.
 * AnalysisForm is the data structure for keeping
 * user analysis form data. It is used by the 'analyse' action of 'AnswerController'.
 */
class AnalysisForm extends CFormModel
{
	public $survey;
	public $questionGroup;


	/**
	 * Declares the validation rules.
	 * The rules state that survey and questionGroup are required.
	 */
	public function rules()
	{
		return array(
			// survey and questionGroup are required
			array('survey, questionGroup', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Question Group',
                        'survey'=>'Survey',
		);
	}

}
