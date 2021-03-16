<?php
namespace Api\V1\Access;

use ZF\OAuth2\Adapter\PdoAdapter;

/**
 * Custom extension of PdoAdapter to validate against the user table.
 */
class ScPdoAdapter extends PdoAdapter
{
    public function __construct($connection, $config = array())
    {
        $config = [
            'user_table' => 'user',
            'client_table' => 'oauth_clients'
        ];
        return parent::__construct($connection, $config);
    }

    public function getUser($username)
    {
        $sql = sprintf(
            'SELECT * from %s where email=:username',
            $this->config['user_table']
        );
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array('username' => $username));

        if (!$userInfo = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return false;
        }
        
        // the default behavior is to use "username" as the user_id
        return array_merge(array(
            'user_id' => $userInfo['id']
        ), $userInfo);
    }

    public function setUser($email, $password, 
        $name = null, $surname = null)
    {
        d('setUser');
        // do not store in plaintext, use bcrypt
       $password = $this->hashPassword($password);

        // if it exists, update it.
        if ($this->getUser($email)) {
            $sql = sprintf(
                'UPDATE %s SET password=:password, name=:name,
                    surname=:surname WHERE email=:email',
                $this->config['user_table']
            );
            $stmt = $this->db->prepare($sql);
        } else {
            $sql = sprintf(
                'INSERT INTO %s (email, password, name, surname)
                    VALUES (:email, :password, :name, :surname)',
                $this->config['user_table']
            );
            $stmt = $this->db->prepare($sql);
        }

        return $stmt->execute(compact('email', 'password', 'name',
            'surname'));
    }

    protected function checkPassword($user, $password)
    {
        $auth_password = $this->hashPassword($password);
        return $auth_password == $user['password'];
    }

    protected function hashPassword($password)
    {
        $sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
        return $sl->get('salt')->hash($password);
    }

}