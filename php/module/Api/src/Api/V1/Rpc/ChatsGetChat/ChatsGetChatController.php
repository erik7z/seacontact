<?php
namespace Api\V1\Rpc\ChatsGetChat;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use OAuth2\Request as OAuth2Request;
use OAuth2\Server;

class ChatsGetChatController extends AbstractActionController
{
    public function chatsGetChatAction()
    {
		try {
		    $table = $this->get('MessageTable');
		    $permitted_fields = [
		        '_user_fields' => $this->get('UserTable')->getStandartFields(),
		        '_message_fields' => $table->getStandartFields(),
		    ];

		    $default_options = [
			    '_user_fields' => ['id'],
			    '_message_fields' => ['id','text'],
		    ];
		    $request = $this->apiQoptions($default_options, $permitted_fields);
		    $chat_id = $request['filters']['id'];
	        $identity = $this->get('api-identity')->getAuthenticationIdentity();
	        if(!$this->isPermitted('my\controller\messages.pmflow', null, null,$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
	            throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

	        if(!$table->isChatMember($identity['user_id'], $chat_id)) throw new \Application\Exception\Exception("You cannot Access this chat", 1);
	        $table->setReadedMessages($identity['user_id'], $chat_id);
	        $data_list = $table->getChat($chat_id, $request['filters'], $request['q_options'])->toArray();
	        $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
		    $request['q_options']['count'] = 1;
		    $total_results = $table->getChat($chat_id, $request['filters'], $request['q_options'])->current()->count;
		} catch (\Exception $e) {
		    $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		    return new ApiProblemResponse(new ApiProblem(406, $message));
		}

		return new ViewModel(array(
		    'data_list' => $data_list,
		    'total_results' => $total_results,
		    '_page' => $request['q_options']['_page'],
		    '_limit' => $request['q_options']['_limit'],
		    ));
    }
}
