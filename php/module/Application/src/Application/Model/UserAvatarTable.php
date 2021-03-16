<?php
namespace Application\Model;

class UserAvatarTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('user_avatar');
	}


	public function getUserAvatars($user_id)
	{
		return $this->getAllOnField('user', $user_id, 'time', false);
	}

	public function getUserAvatar($user_id)
	{
		return $this->getLastByField('user', $user_id);
	}

	public function getUserIdOnAvaId($ava_id)
	{
		return $this->getFieldByField('user', 'id', $ava_id);
	}

	public function deleteCurrentAvatar($user_id)
	{
		$avatar = $this->getUserAvatar($user_id);
		if($avatar)	{
			return $this->delete($avatar['id']);
		}
		return false;
	}

	public function deleteUnusedAvatar($ava_id)
	{
		if(!$user_id = $this->getUserIdOnAvaId($ava_id)) throw new \Application\Exception\Exception("Unknown Error, User / Avatar not founded");
		
		$avatars = $this->getUserAvatars($user_id)->toArray();
		if(count($avatars) > 1 && $avatars[0]['id'] != $ava_id) return $this->delete($ava_id);
		return false;
	}

	public function delete($ava_id, $field = 'id')
	{
		$avatar = $this->get($ava_id);
		if($avatar) {
			$this->sl()->get('UploadsTable')->deleteUpload($avatar['img'], 'avatar');
			parent::delete($avatar['id']);
			return true;		
		}
		throw new \Application\Exception\Exception("Avatar not found");
	}

	public function addAvatar($user_id, $img, $thumb, $crop)
	{
		return $this->insert(array(
			'user' => $user_id,
			'img' => $img,
			'thumb' => $thumb,
			'crop' => $crop,
			'time' => time()
			));
	}

}