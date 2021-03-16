<?php
namespace UserInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class CvController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */
	public function indexAction()
	{
		$query = $this->params()->fromRoute('query', $this->params()->fromQuery());
		$login = $this->params()->fromRoute('user');
		if(!$login) throw new \Application\Exception\Exception($this->translate("User id or login not provided"), 1);

		$lang = (isset($query['lang'])) ? $query['lang'] : 'ru';
		if ($lang == 'en') $this->get('translator')->setLocale('en_US');
		else $this->get('translator')->setLocale('ru_RU');
		$viewer_id = ($this->identity())? $this->identity()->id : null;
		
		$user_id = $this->get('UserTable')->getUserIdByLogin($login);
		$user = $this->get('UserTable')->getUsersList($viewer_id, ['id' => $user_id], ['_limit' => 1, 'show_stats' => 1])->current();
		
		$this->get('ActivityViewsTable')->addView('user', $user_id, $viewer_id);
		

		$educationTable = new \Application\Model\zEmptyTable('user_education');
		$education = $educationTable->getAllOnField('user', $user->id)->buffer();

		$user['dob'] = date('Y-m-d', $user['dob']);
		$user['readiness_date'] = ($user['readiness_date']) ? date('Y-m-d', $user['readiness_date']) : null;
		if($user['visa_usa_exp']) $user['visa_usa_exp'] = date('Y-m-d', $user['visa_usa_exp']);
		if($user['visa_shenghen_exp']) $user['visa_shenghen_exp'] = date('Y-m-d', $user['visa_shenghen_exp']);

		if(isset($query['hide']) && count($query['hide'] > 0)) {
			foreach ($query['hide'] as $hide_field) {
				if(isset($user->{$hide_field}))	$user->{$hide_field} = '_HIDDEN_';
			}
		}


		$view_data = [
			'user' => $user,
			'user_experience' => $this->get('ExperienceTable')->getUserExperience($user->id),
			'user_education' => $education,
			'user_docs' => $this->get('DocumentsTable')->getAllUserDocs($user->id),
			'user_last_note' => $this->get('UserCvNotesTable')->getLastUserCvNote($user->id, $viewer_id, 1,0,0),
			'print' => false
		];
		$view = new ViewModel($view_data);
		if (isset($query['format'])) {
			
			if($query['format'] == 'print') {
				$view_data['print'] = true;
				$view = new ViewModel($view_data);
				$view->setTemplate('user-info/cv/plain');
				$view->setTerminal(true);
				return $view;
			}

			if($query['format'] == 'pdf') {
				$view->setTemplate('user-info/cv/plain');
				$view->setOption('has_parent', true);
				$view_html = $this->get('ViewManager')->getView()->render($view);

				$dompdf = $this->get('dompdf');
				$dompdf->set_options(['temp_dir' => '/home/exillo14/tmp']);
				$dompdf->set_options(['enable_remote' => true]);
				$dompdf->set_base_path($_SERVER['DOCUMENT_ROOT']);
				$dompdf->load_html($view_html);
				$dompdf->render();
				$output = $dompdf->output();
				$fileName =  'sc_'.$user->desired_rank.'_'.zgetUserName($user).'_'.$lang.'.pdf';
				$fileName = str_replace(' ', '_', $fileName);

			    $response = $this->getResponse();
			    $response->setContent($output);

			    $headers = $response->getHeaders();
			    $headers->clearHeaders()
			        ->addHeaderLine('Content-Type', 'application/pdf')
			        ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
			        ->addHeaderLine('Content-Length', strlen($output));


			    return $this->response;
			}
		}
		return $view;	
	}

}