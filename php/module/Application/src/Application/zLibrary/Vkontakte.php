<?php

namespace Application\zLibrary;

/**
 * The Vkontakte PHP SDK
 *
 * @author Bocharsky Victor, https://github.com/Vastly
 */
class Vkontakte
{

    const VERSION = '5.5';

    /**
     * The application ID
     * @var integer
     */
    private $app_id;

    /**
     * The application app_secret code
     * @var string
     */
    private $app_secret;

    /**
     * The scope for login URL
     * @var array
     */
    private $scope = array();

    /**
     * The URL to which the user will be redirected
     * @var string
     */
    private $redirect_uri;

    /**
     * The response type of login URL
     * @var string
     */
    private $responseType = 'code';

    /**
     * The current access token
     * @var \StdClass
     */
    private $token_data;

    private $user_id;
    private $user_token;
    private $user_secret;
    private $https = false;

    public $captcha_sid;
    public $captcha_key;
    public $captcha_method;

    /**
     * The Vkontakte instance constructor for quick configuration
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['user_token'])) {
            $this->setUserToken($config['user_token']);
        }
        if (isset($config['user_secret'])) {
            $this->setUserSecret($config['user_secret']);
        }
        if (isset($config['app_id'])) {
            $this->setAppId($config['app_id']);
        }
        if (isset($config['app_secret'])) {
            $this->setAppSecret($config['app_secret']);
        }
        if (isset($config['scopes'])) {
            $this->setScope($config['scopes']);
        }
        if (isset($config['redirect_uri'])) {
            $this->setRedirectUri($config['redirect_uri']);
        }
        if (isset($config['response_type'])) {
            $this->setResponseType($config['response_type']);
        }
    }



    /**
     * Set the application id
     * @param integer $app_id
     * @return \Vkontakte
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;

        return $this;
    }

    /**
     * Get the application id
     * @return integer
     */
    public function getAppId()
    {

        return $this->app_id;
    }

    /**
     * Set the application app_secret key
     * @param string $app_secret
     * @return \Vkontakte
     */
    public function setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;

        return $this;
    }

    /**
     * Get the application app_secret key
     * @return string
     */
    public function getAppSecret()
    {

        return $this->app_secret;
    }

    /**
     * Set the scope for login URL
     * @param array $scope
     * @return \Vkontakte
     */
    public function setScope(array $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get the scope for login URL
     * @return array
     */
    public function getScope()
    {

        return $this->scope;
    }

    /**
     * Set the URL to which the user will be redirected
     * @param string $redirect_uri
     * @return \Vkontakte
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;

        return $this;
    }

    /**
     * Get the URL to which the user will be redirected
     * @return string
     */
    public function getRedirectUri($success_action = null)
    {
        if($success_action) $this->redirect_uri .= '?success_action='.$success_action;
        return $this->redirect_uri;
    }

    /**
     * Set the response type of login URL
     * @param string $responseType
     * @return \Vkontakte
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * Get the response type of login URL
     * @return string
     */
    public function getResponseType()
    {

        return $this->responseType;
    }

    /**
     * Get the login URL via Vkontakte
     * @return string
     */
    public function getLoginUrl($success_action = null)
    {

        return 'https://oauth.vk.com/authorize'
        . '?client_id=' . urlencode($this->getAppId())
        . '&scope=' . urlencode(implode(',', $this->getScope()))
        . '&redirect_uri=' . urlencode($this->getRedirectUri($success_action))
        . '&response_type=' . urlencode($this->getResponseType())
        . '&v=' . urlencode(self::VERSION);
    }

    /**
     * Authenticate user and get access token from server
     * @param string $code
     * @return \Vkontakte
     */
    public function authenticate($code = NULL, $success_action = null)
    {
        $code = $code ? $code : $_GET['code'];

        $url = 'https://oauth.vk.com/access_token'
        . '?client_id=' . urlencode($this->getAppId())
        . '&client_secret=' . urlencode($this->getAppSecret())
        . '&code=' . urlencode($code)
        . '&redirect_uri=' . urlencode($this->getRedirectUri($success_action));

        $token_data = $this->curl($url);
        $token_data = json_decode($token_data);

        if(isset($token_data->error)) {
            switch ($token_data->error->error_code) {
                case 14:
                throw new \Application\Exception\Exception(serialize(['captcha_sid' => $token_data->error->captcha_sid, 'captcha_img' =>  $token_data->error->captcha_img]), $response->error->error_code);
                break;
                default:
                $code = 0;
                $message = '';
                if(isset($token_data->error->error_code)) $code = $token_data->error->error_code;
                if(isset($token_data->error_description)) $message .= $token_data->error_description;
                if(isset($token_data->error->error_msg)) $message .= ' '.$token_data->error->error_msg;
                if(isset($token_data->error->error_description)) $message .= ' '.$token_data->error->error_description;
                throw new \Application\Exception\Exception($message, $code);
                break;
            }
        }

        if(isset($token_data->access_token))  $this->user_token = $token_data->access_token;
        if(isset($token_data->secret))  $this->user_secret = $token_data->secret;
        if(isset($token_data->user_id))  $this->user_id = $token_data->user_id;
        if(isset($token_data->expires_in))  $this->user_token_expires = $token_data->expires_in;
        $this->user_token_created = time(); 

        $this->setTokenData($token_data);

        return $this;
    }



    /**
     * Set the access token
     * @param string $token The access token
     * @return \Vkontakte
     */
    public function setTokenData($token_data)
    {
        $this->token_data = $token_data;

        return $this;
    }

    /**
     * Get the access token
     * @param string $code
     * @return string The access token 
     */
    public function getTokenData()
    {
        return $this->token_data;
    }

    /**
     * Check is access token expired
     * @return boolean
     */
    public function isAccessTokenExpired()
    {
        return time() > $this->user_token_created + $this->user_token_expires;
    }

    /**
     * Get the user id of current access token
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserSecret()
    {
        return $this->user_secret;
    }

    public function setUserSecret($user_secret)
    {
        $this->user_secret = $user_secret;
        return $this;
    }

    public function getUserToken()
    {
        return $this->user_token;
    }

    public function setUserToken($user_token)
    {
        $this->user_token = $user_token;
        return $this;
    }


    public function getCaptcha()
    {
        if($this->captcha_sid && $this->captcha_key)             
            return [
                'sid' => $this->captcha_sid,
                'key' => $this->captcha_key,
                'method' => $this->captcha_method,
            ];
        return false;
    }

    public function setCaptcha($sid, $key)
    {
        $this->captcha_sid = $sid;
        $this->captcha_key = $key;
        return $this;
    }

    public function getHttps()
    {
        return $this->https;
    }

    public function setHttps($https)
    {
        $this->https = $https;
        return $this;
    }


    public function getGroups($group_ids)
    {
        $params = [
        'group_ids' => $group_ids,
        'fields' => 'city,country,place,status,description,members_count,can_post,can_see_all_post,can_message',
        ];
        return $this->api('groups.getById', $params);

    }

    public function getUserInfo($vk_user_id)
    {
        $response = $this->getUsers($vk_user_id);
        if(isset($response[0])) return $response[0];
        else return $response;
    }

    public function getUsers($user_ids)
    {
        $params = [
        'user_ids' => $user_ids,
        'fields' => 'photo_50,photo_100,photo_200,photo_max,bdate,domain,screen_name',
        ];

        return $this->api('users.get', $params);
    }

    public function getWall($vk_user_id)
    {

        $params = [
        'owner_id' => $vk_user_id,
        'count' => _MAX_VK_POST_PARSING_,
        'filter' => 'all',
        ];

        return $this->api('wall.get', $params);
    }

    public function getLikes($type, $owner_id, $item_id, $options = [])
    {
        $params = [
        'type' => $type,
        'owner_id' => $owner_id,
        'item_id' => $item_id,
        ];
        $params['offset'] = isset($options['offset'])? $options['offset'] : 0;
        $params['count'] = isset($options['count'])? $options['count'] : _MAX_VK_LIKES_PARSING_;
        $params['extended'] = isset($options['extended'])? $options['extended'] : 0;

        return $this->api('likes.getList', $params, ['set_version' => 1]);
    }

    public function getWallComments($owner_id, $post_id, $options = [])
    {
        $params = [
        'owner_id' => $owner_id,
        'post_id' => $post_id,
        ];
        $params['need_likes'] = isset($options['need_likes'])? $options['need_likes'] : 1;
        $params['offset'] = isset($options['offset'])? $options['offset'] : 0;
        $params['count'] = isset($options['count'])? $options['count'] : _MAX_VK_COMMENTS_PARSING_;
        $params['preview_length'] = isset($options['preview_length'])? $options['preview_length'] : 0;
        $params['extended'] = isset($options['extended'])? $options['extended'] : 1;
        if(isset($options['start_comment_id']) && $options['start_comment_id']) $params['start_comment_id'] = $options['start_comment_id'];
  
        return $this->api('wall.getComments', $params, ['set_version' => 1]);
    }

    public function getVideo($owner_id, $video_id, $access_key = null)
    {
        $video_id =  $owner_id.'_'.$video_id;
        if($access_key) $video_id .= '_'.$access_key;
        $params = [
        'videos' => $video_id,
        ];

        return $this->api('video.get', $params);
    }

    


    /**
     * @param $publicID int vk group official identifier
     * @param $fullServerPathToImage string full path to the image file, ex. /var/www/site/img/pic.jpg
     * @param $text string message text
     * @param $tags array message tags
     * @return bool true if operation finished successfully and false otherwise
     */
    public function postToPublic($publicID, $text, $images = array(), $tags = array(), $link = null, $publish_date = null, $from_group = true)
    {   

        $attachments = '';
        $uploaded_images = [];
        if(count($images) > 0) for ($i=0; $i < count($images); $i++) {
            $image = $images[$i];
            if(!file_exists($image))
                throw new \Application\Exception\Exception("Image File {$image} not found", 1);

            $response = $this->api('photos.getWallUploadServer', [
                'group_id' => $publicID,
                ]);

            if(isset($response->error))
                throw new \Application\Exception\Exception($response->error->error_msg, $response->error->error_code);

            $uploadURL = $response->upload_url;
            $output = [];
            exec("curl -X POST -F 'photo=@$image' '$uploadURL'", $output);
            if (count($output)) {
                $response = json_decode($output[0]);

                $photo_response = $this->api('photos.saveWallPhoto', [
                    'group_id' => $publicID,
                    'photo' => $response->photo,
                    'server' => $response->server,
                    'hash' => $response->hash,
                    ]);

                if(isset($photo_response[$i]->error))
                    throw new \Application\Exception\Exception($photo_response[$i]->error->error_msg, $photo_response->error->error_code);
                $uploaded_images[] = $photo_response[0]->id;
            }
            

        }
        


        $tags_string = '';
        if($tags) {
            foreach ($tags as $tag) {
                if($tag != '') $tags_string .= ' #' . str_replace(' ', '_', trim($tag));
            }
        }

        $text = $tags_string."\n".$text;
        $text = zbr2nl(html_entity_decode($text));
        $request = [
        'owner_id' => -$publicID,
        'from_group' => $from_group,
        'message' => "$text",
        ];

        if(count($uploaded_images) > 0 ) $attachments = implode(',', $uploaded_images);

        if($link) $attachments .= ($attachments != '')? ','.$link : $link;

        if($attachments) $request['attachments'] = $attachments;
        if($publish_date) $request['publish_date'] = $publish_date;
        
        $response = $this->api('wall.post',$request);
        if(isset($response->error))
            throw new \Application\Exception\Exception($response->error->error_msg, $response->error->error_code);
        if(!$response->post_id) throw new \Application\Exception\Exception("Unknown VK Error, Not posted to vk", 1);
        
        return $response->post_id;
    }


    /**
     * Make an API call to https://api.vk.com/method/
     * @return string The response, decoded from json format
     */
    public function api($method, array $params = [], $options = [])
    {
        $https = (isset($options['https']))? $options['https'] : $this->getHttps();
        if(!$this->getUserSecret()) $https = 1;
        $set_token = (isset($options['set_token']))? $options['set_token'] : 1;
        $set_version = (isset($options['set_version']))? $options['set_version'] : 0;

        if($this->getCaptcha()) {
            $params['captcha_sid'] = $this->captcha_sid;
            $params['captcha_key'] = $this->captcha_key;
            // if($method == $this->captcha_method) 
            //     $this->setCaptcha(null, null);
        }

        if($set_version) $params['v'] = urlencode(self::VERSION);
        if($set_token && $this->getUserToken()) 
            $params['access_token'] = $this->getUserToken();

        $query_params = http_build_query($params);
        $query = '/method/' . $method . '?' . $query_params;
        if($https) {
            $url = 'https://api.vk.com'.$query;
        } else {
            $querySig = md5($query . $this->getUserSecret());
            $url = 'http://api.vk.com'.$query."&sig=$querySig";
        } 
       
        $response = json_decode($this->curl($url));
        if (isset($response->response)) {
            $response = $response->response;
        }
 
        if(isset($response->error)) {
            switch ($response->error->error_code) {
                case 14:
                $this->captcha_method = $method;
                throw new \Application\Exception\Exception(serialize(['captcha_sid' => $response->error->captcha_sid, 'captcha_img' =>  $response->error->captcha_img]), $response->error->error_code);
                break;
                default:
                throw new \Application\Exception\Exception($response->error->error_msg, $response->error->error_code);
                break;
            }
        }
        return $response;
    }

    /**
     * Make the curl request to specified url
     * @param string $url The url for curl() function
     * @return mixed The result of curl_exec() function
     * @throws \Exception
     */
    protected function curl($url)
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // disable SSL verifying
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // $output contains the output string
        $result = curl_exec($ch);

        if (!$result) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
        }

        // close curl resource to free up system resources
        curl_close($ch);

        if (isset($errno) && isset($error)) {
            throw new \Application\Exception\Exception("Curl Connection Error", $errno);
        }

        return $result;
    }

}