<?php

class AnswerController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','index','view','create','update','display','csv','deleteall','analyseoverall','analysecsv'),
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
	public function actionView($user_id, $survey_id)
	{
                $user=User::model()->findByPk($user_id);
                $survey=Survey::model()->findByPk($survey_id);
		$this->render('view',array(
			'user'=>$user,
                        'survey'=>$survey,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Answer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Answer']))
		{
			$model->attributes=$_POST['Answer'];
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

		if(isset($_POST['Answer']))
		{
			$model->attributes=$_POST['Answer'];
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
                $users=User::model()->findAll();
                $surveys=Survey::model()->findAll();
		$this->render('index',array(
			'users'=>$users,
                        'surveys'=>$surveys,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Answer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Answer']))
			$model->attributes=$_GET['Answer'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        /**
         * Displays a table for particular user for a particular survey.
         */

        public function actionDisplay($survey_id,$user_id)
	{
            $this->layout = '//layouts/column1';
            $answers=Answer::model()->findAll('survey_id=:survey_id AND user_id=:user_id',array(':survey_id'=>$survey_id,':user_id'=>$user_id));
            $departments=Department::model()->findAll();
            $questions=Question::model()->with('questionGroup')->findAll();
            $user=  User::model()->findByPk($user_id);
            $survey=  Survey::model()->findByPk($survey_id);
            $this->render('display',array(
                    'answers'=>$answers,
                    'departments'=>$departments,
                    'questions'=>$questions,
                    'user'=>$user,
                    'survey'=>$survey,
            ));
	}

        /**
         *
         * @param integer $survey_id
         * @param integer $user_id
         * generates csv of answers.
         */
        public function actionCsv($survey_id, $user_id)
        {
            $answers=Answer::model()->findAll('survey_id=:survey_id AND user_id=:user_id',array(':survey_id'=>$survey_id,':user_id'=>$user_id));
            $departments=Department::model()->findAll();
            $questions=Question::model()->with('questionGroup')->findAll();
            $user=  User::model()->findByPk($user_id);
            $survey=  Survey::model()->findByPk($survey_id);
            $this->renderPartial('csv',array(
                    'answers'=>$answers,
                    'departments'=>$departments,
                    'questions'=>$questions,
                    'survey'=>$survey,
                    'user'=>$user,
            ));

        }
        /**
         *
         * @param Integer $survey_id
         * @param Integer $user_id
         * Delete all reponses of a particular user for a particular survey
         */
        public function actionDeleteall($survey_id, $user_id)
	{
                if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
                        $answers=Answer::model()->deleteAll('survey_id=:survey_id AND user_id=:user_id',array(':survey_id'=>$survey_id,':user_id'=>$user_id));

                        if(!isset($_GET['ajax']))
                                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
                }
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

        /**
         * Analyse each department's score and ranking
         */
        public function actionAnalyseoverall()
	{
                $this->layout="//layouts/column1";
                $model=new AnalysisForm();
                $surveys= Survey::model()->findAll();
                $questionGroups=QuestionGroup::model()->findAll();
		$departments=Department::model()->findAll();
                $post=array();
                $average=array();
                $score=array();
                if(isset($_POST['AnalysisForm']))
                {
                
                    if($_POST['AnalysisForm']['survey']=='All' && $_POST['AnalysisForm']['questionGroup']=='All')
                    {
                        foreach($departments as $department)
                        {
                            $answers=Answer::model()->findAll('department_id=:department_id',array(':department_id'=>$department->id));
                            $count=0;
                            $score[$department->name]=0;
                            foreach($answers as $answer)
                            {
                                if($answer->content!='0')
                                {
                                    $count++;
                                    $score[$department->name]+=(int)$answer->content;
                                }
                            }
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }
                    else if($_POST['AnalysisForm']['survey']=='All')
                    {
                        foreach($departments as $department)
                        {
                            $questionGroup=QuestionGroup::model()->find('content=:content',array('content'=>$_POST['AnalysisForm']['questionGroup']));
                            $questions=Question::model()->findAll('question_group_id=:qgroupid',array(':qgroupid'=>$questionGroup->id));
                            $count=0;
                            $score[$department->name]=0;
                            foreach($questions as $question)
                            {
                                $answers=Answer::model()->findAll('department_id=:department_id AND question_id=:question_id',array(':department_id'=>$department->id,':question_id'=>$question->id));
                                foreach($answers as $answer)
                                {
                                    if($answer->content!='0')
                                    {
                                        $count++;
                                        $score[$department->name]+=(int)$answer->content;
                                    }
                                }
                                
                            }
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }
                    else if($_POST['AnalysisForm']['questionGroup']=='All'){
                        foreach($departments as $department)
                        {
                            $survey=Survey::model()->find('date=:date',array('date'=>$_POST['AnalysisForm']['survey']));
                            $count=0;
                            $score[$department->name]=0;
                            $answers=Answer::model()->findAll('department_id=:department_id AND survey_id=:survey_id',array(':department_id'=>$department->id,':survey_id'=>$survey->id));
                            foreach($answers as $answer)
                            {
                                if($answer->content!='0')
                                {
                                    $count++;
                                    $score[$department->name]+=(int)$answer->content;
                                }
                            }
                        
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }
                    else
                    {
                        foreach($departments as $department)
                        {
                            $questionGroup=QuestionGroup::model()->find('content=:content',array('content'=>$_POST['AnalysisForm']['questionGroup']));
                            $questions=Question::model()->findAll('question_group_id=:qgroupid',array(':qgroupid'=>$questionGroup->id));
                            $survey=Survey::model()->find('date=:date',array('date'=>$_POST['AnalysisForm']['survey']));
                            $count=0;
                            $score[$department->name]=0;
                            foreach($questions as $question)
                            {
                                $answers=Answer::model()->findAll('department_id=:department_id AND question_id=:question_id AND survey_id=:survey_id',array(':department_id'=>$department->id,':question_id'=>$question->id,':survey_id'=>$survey->id));
                                foreach($answers as $answer)
                                {
                                    if($answer->content!='0')
                                    {
                                        $count++;
                                        $score[$department->name]+=(int)$answer->content;
                                    }
                                }

                            }
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }


                    $post['questionGroup']=$_POST['AnalysisForm']['questionGroup'];
                    $post['survey']=$_POST['AnalysisForm']['survey'];
                }

                $this->render('analyseoverall',array(
                        'scores'=>$score,
                        'averages'=>$average,
                        'departments'=>$departments,
                        'surveys'=>$surveys,
                        'questionGroups'=>$questionGroups,
                        'model'=>$model,
                        'post'=>$post,
                ));
	}
        
        
        public function actionAnalysecsv()
	{
                
                $model=new AnalysisForm();
                $surveys= Survey::model()->findAll();
                $questionGroups=QuestionGroup::model()->findAll();
		$departments=Department::model()->findAll();
                $post=array();
                $average=array();
                $score=array();
                if(isset($_POST['AnalysisForm']))
                {
                
                    if($_POST['AnalysisForm']['survey']=='All' && $_POST['AnalysisForm']['questionGroup']=='All')
                    {
                        foreach($departments as $department)
                        {
                            $answers=Answer::model()->findAll('department_id=:department_id',array(':department_id'=>$department->id));
                            $count=0;
                            $score[$department->name]=0;
                            foreach($answers as $answer)
                            {
                                if($answer->content!='0')
                                {
                                    $count++;
                                    $score[$department->name]+=(int)$answer->content;
                                }
                            }
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }
                    else if($_POST['AnalysisForm']['survey']=='All')
                    {
                        foreach($departments as $department)
                        {
                            $questionGroup=QuestionGroup::model()->find('content=:content',array('content'=>$_POST['AnalysisForm']['questionGroup']));
                            $questions=Question::model()->findAll('question_group_id=:qgroupid',array(':qgroupid'=>$questionGroup->id));
                            $count=0;
                            $score[$department->name]=0;
                            foreach($questions as $question)
                            {
                                $answers=Answer::model()->findAll('department_id=:department_id AND question_id=:question_id',array(':department_id'=>$department->id,':question_id'=>$question->id));
                                foreach($answers as $answer)
                                {
                                    if($answer->content!='0')
                                    {
                                        $count++;
                                        $score[$department->name]+=(int)$answer->content;
                                    }
                                }
                                
                            }
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }
                    else if($_POST['AnalysisForm']['questionGroup']=='All'){
                        foreach($departments as $department)
                        {
                            $survey=Survey::model()->find('date=:date',array('date'=>$_POST['AnalysisForm']['survey']));
                            $count=0;
                            $score[$department->name]=0;
                            $answers=Answer::model()->findAll('department_id=:department_id AND survey_id=:survey_id',array(':department_id'=>$department->id,':survey_id'=>$survey->id));
                            foreach($answers as $answer)
                            {
                                if($answer->content!='0')
                                {
                                    $count++;
                                    $score[$department->name]+=(int)$answer->content;
                                }
                            }
                        
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }
                    else
                    {
                        foreach($departments as $department)
                        {
                            $questionGroup=QuestionGroup::model()->find('content=:content',array('content'=>$_POST['AnalysisForm']['questionGroup']));
                            $questions=Question::model()->findAll('question_group_id=:qgroupid',array(':qgroupid'=>$questionGroup->id));
                            $survey=Survey::model()->find('date=:date',array('date'=>$_POST['AnalysisForm']['survey']));
                            $count=0;
                            $score[$department->name]=0;
                            foreach($questions as $question)
                            {
                                $answers=Answer::model()->findAll('department_id=:department_id AND question_id=:question_id AND survey_id=:survey_id',array(':department_id'=>$department->id,':question_id'=>$question->id,':survey_id'=>$survey->id));
                                foreach($answers as $answer)
                                {
                                    if($answer->content!='0')
                                    {
                                        $count++;
                                        $score[$department->name]+=(int)$answer->content;
                                    }
                                }

                            }
                            if($count==0) $average[$department->name] = 0;
                            else $average[$department->name]=$score[$department->name]/$count;
                        }
                    }


                    $post['questionGroup']=$_POST['AnalysisForm']['questionGroup'];
                    $post['survey']=$_POST['AnalysisForm']['survey'];
                }

                $this->renderPartial('analysecsv',array(
                        'scores'=>$score,
                        'averages'=>$average,
                        'departments'=>$departments,
                        'surveys'=>$surveys,
                        'questionGroups'=>$questionGroups,
                        'model'=>$model,
                        'post'=>$post,
                ));
	}
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Answer::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='answer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
