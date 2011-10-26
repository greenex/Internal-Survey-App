<?php

class SurveyController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','take','attendance'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','toggle'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
                if(Yii::app()->user->name != "admin")
                    $this->layout = '//layouts/column1';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Survey;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Survey']))
		{
			$model->attributes=$_POST['Survey'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Survey']))
		{
			$model->attributes=$_POST['Survey'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
                if(Yii::app()->user->name != "admin")
                    $this->layout = '//layouts/column1';
		$dataProvider=new CActiveDataProvider('Survey');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Survey('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Survey']))
			$model->attributes=$_GET['Survey'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Survey::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='survey-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionToggle($survey_id)
        {
                $survey = Survey::model()->findByPk($survey_id);
                if(!$survey->active)
                {
                    $survey->active = 1;
                }
                else
                    $survey->active = 0;
                $survey->save();
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        
        public function actionAttendance($survey_id)
        {
                $this->layout = '//layouts/column1';
                $survey = Survey::model()->findByPk($survey_id);
                $this->render('attendance',array(
                    'survey' => $survey,
                ));
        }
        
        /**
         * Handles survey run
         * @param type $survey_id Survey to be answered
         */
        public function actionTake($survey_id)
        {
            
            $this->layout = '//layouts/column1';
            $complete = false;
            $questionGroupId = 0;
            $question_group_objects = QuestionGroup::model()->findAll();//get all question groups
            $question_groups = array(); //dummy variable to store in session
            foreach($question_group_objects as $group)
            {
                $question_groups[] = $group->id; //create indexed array of question group ids
            }
            if(isset($_POST['Answer']))
            {
                $valid=true;
                foreach($_POST['Answer'] as $i=>$answer)
                {
                    if($answer['content']=="") $valid=false;
                }
                if(!$valid){Yii::app()->user->setFlash('Error','Oops. Seems you missed out some answers.');}  // all items aren't valid

                if($valid)
                {
                    foreach($_POST['Answer'] as $i=>$answer)
                    {
                        if($answer['content']!=""){

                            $model=new Answer();
                            $model->attributes=$answer;
                            $model->save();


                        }
                    }
                    $questionGroupId = $question_groups[(Yii::app()->session['state_index'])]; //get questionGroup id from state index
                    //get state model
                    $state = State::model()->find('survey_id=:sid AND user_id=:uid',array(
                        ':sid'=>$survey_id,
                        ':uid'=>Yii::app()->user->id));
                    if(isset($question_groups[(Yii::app()->session['state_index'])+1]))
                    {
                        $state_index = Yii::app()->session['state_index'];
                        $state_index++;   
                        $questionGroupId = $question_groups[$state_index];
                    }
                    else
                    {
                        $state_index = -1;
                        $questionGroupId = -1;
                    }
                    Yii::app()->session['state_index'] = $state_index;    
                    $state->question_group_id = $questionGroupId;
                    $state->save();
                }
            }

            
            //When the user clicks on another survey while another survey was midway or somehow still in session, destroy previous session variables
            if(isset(Yii::app()->session['survey_id']) && Yii::app()->session['survey_id'] != $survey_id)
            {
                unset(Yii::app()->session['survey_id']);
                unset(Yii::app()->session['state_index']);
            }
            
            if(isset(Yii::app()->session['state_index']) && isset(Yii::app()->session['survey_id']))
            {
                if(Yii::app()->session['state_index'] != -1)
                {
                   $state_index = Yii::app()->session['state_index'];
                   $questionGroupId = $question_groups[$state_index];
                }
                else
                {
                    Yii::app()->user->setFlash('Successful','Thank you for your time. Your data has been saved successfully.');
                }
                
            }
            else //when no session variables or incomplete session variables
            {
                $state = State::model()->find('survey_id=:survey_id AND user_id=:user_id', array(':survey_id'=>$survey_id,':user_id'=>Yii::app()->user->id));
                //get state from table
                if($state == NULL) //check if it's NULL, first time survey attempt
                {
                    
                    Yii::app()->session['state_index'] = 0; //store current index of array as 0
                    
                    
                    $questionGroupId = $question_groups[Yii::app()->session['state_index']]; //get questionGroup id from state index
                    //store the state(question group id) in database
                    $state = new State;
                    $state->question_group_id = $questionGroupId;
                    $state->survey_id = $survey_id;
                    $state->user_id = Yii::app()->user->id;
                    $state->save();
                }
                else if($state->question_group_id == -1)
                {
                    Yii::app()->user->setFlash('answered','Sorry, but you have already answered the survey.');
                }
                else
                {
                    $i = 0;
                    foreach($question_groups as $group)
                    {
                        if($group == $state->question_group_id) {
                                Yii::app()->session['state_index'] = $i; //store current index of array as the one that matches the state's question group id
                                break;
                        }
                        $i++;
                    } 
                    
                    $questionGroupId = $question_groups[Yii::app()->session['state_index']]; //get questionGroup id from state index
                    
                }

                Yii::app()->session['survey_id'] = $survey_id; //get survey id from passed variable
                
            }
            $user=User::model()->findByPk(Yii::app()->user->id);
            $survey=Survey::model()->findByPk($survey_id);
            if(!Yii::app()->user->hasFlash('answered') && !Yii::app()->user->hasFlash('Successful'))
            {
                $questions = Question::model()->findAll('question_group_id=:qid', array(':qid'=>$questionGroupId));
                $question_group = QuestionGroup::model()->findByPk($questionGroupId);
                $departments=Department::model()->findAll();
                $answer = new Answer();
                $sticky_answers = NULL;
                if(Yii::app()->user->hasFlash('Error'))
                {
                    $sticky_answers = $_POST['Answer'];
                }
                $this->render('take',array(
                    'questions' => $questions,
                    'question_group' => $question_group,
                    'departments' => $departments,
                    'answer' => $answer,
                    'user' => $user,
                    'survey' => $survey,
                    'sticky_answers' => $sticky_answers,
                ));
            }
            else
            {
                $this->render('take',array(
                    'user' => $user,
                    'survey' => $survey,
                ));
            }
        }

}
