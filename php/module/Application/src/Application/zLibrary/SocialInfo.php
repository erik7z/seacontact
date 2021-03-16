<?php
namespace Application\zLibrary;

include_once __DIR__.'/Goutte/vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class SocialInfo
{
	public $appId;
	public $secretKey;
	public $client;

	 public function __construct()
	 {
	     $this->appId = _VK_APP_ID_;
	     $this->secretKey = _VK_APP_SECR_KEY_;

	     $this->client = new Client();
	     $this->client->setHeader(
	     	'User-Agent', 
	     	'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A537a Safari/419.3');
	     $this->client->setHeader(
	     	'Accept-Language', 
	     	'en');

	     $this->client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT, 1000000);
	     $this->client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TIMEOUT_MS, 1000000);
	     $this->client->getClient()->setDefaultOption('config/curl/'.CURLOPT_CONNECTTIMEOUT, 1000000);
	     $this->client->getClient()->setDefaultOption('config/curl/'.CURLOPT_TCP_NODELAY, 0);
	 }


	 public function getCode()
	 {
 		$code_link = "http://api.vkontakte.ru/oauth/authorize?" .
 	            "client_id={$this->appId}&scope=offline,wall,groups,pages," .
 	            "photos,docs,audio,video,notes,stats,notify,notifications,nohttps&amp;" .
 	            "redirect_uri=https://oauth.vk.com/blank.html&amp;response_type=code";

 	    // $cl2 = "http://api.vkontakte.ru/oauth/authorize?client_id={$this->appId}&scope=offline,wall,groups,pages,photos,docs,audio,video,notes,stats,notify,notifications,nohttps&redirect_uri=https://oauth.vk.com/blank.html&response_type=CODE";
 	    return $this->getPage($code_link);
	 }

	 public function getToken($code)
	 {
	 	$token_link = "https://api.vkontakte.ru/oauth/access_token?client_id={$this->appId}" .
	            "&amp;client_secret={$this->secretKey}&amp;v=5.8&amp;redirect_uri=https://oauth.vk.com/blank.html&amp;code={$code}";
	    $tl2 = "https://api.vkontakte.ru/oauth/access_token?client_id={$this->appId}&client_secret={$this->secretKey}&v=5.8&redirect_uri=https://oauth.vk.com/blank.html&code={$code}";
	    return $token_link;
	 }

	 public function getPage($request)
	 {
	 	$this->client->followRedirects(true);
	 	$page = $this->client->request('GET', $request);

	 	// d($request);
	 	// d(get_class_methods($page));
	 	
	 	if($this->client->getResponse()->getStatus() == 404) 
	 		throw new Exception("Page not found", 404);

	 	return $page->html();
	 }



	 public function auth($auth_url, $credentials)
	 {
	 	$this->client->request('POST', $auth_url, $credentials);

	 }



	 /**
	  * @param string $accessToken
	  * @param string $accessSecret
	  */
	 public function setAccessData($user_id, $accessToken, $accessSecret)
	 {
	     $this->userId = $userId;
	     $this->groupId = $userId; 
	     $this->accessToken = $accessToken;
	     $this->accessSecret = $accessSecret;
	  }

	 /**
	  * Hack
	  */
	 public function getAccessData()
	 {
	     echo "<!doctype html><html><head><meta charset='utf-8'></head>
	         <body><a href='http://api.vkontakte.ru/oauth/authorize?" .
	         "client_id={$this->appId}&scope=offline,wall,groups,pages," .
	         "photos,docs,audio,video,notes,stats,notify,notifications,nohttps&amp;" .
	         "redirect_uri=https://oauth.vk.com/blank.html&amp;response_type=code'
	             target='_blank'>Получить CODE</a><br>Ссылка для получения токена:<br>
	             <b>https://api.vkontakte.ru/oauth/access_token?client_id={$this->appId}" .
	         "&amp;client_secret={$this->secretKey}&amp;v=5.8&amp;redirect_uri=https://oauth.vk.com/blank.html&amp;code=CODE</b></body></html>";

	     exit;
	 }

	 /**
	  * @param string $method
	  * @param mixed $parameters
	  * @return mixed
	  */

	// public function callMethod($method, $parameters)
	//  {
	//      if (!$this->accessToken) return false;
	//      if (is_array($parameters)) $parameters = http_build_query($parameters);
	//      $queryString = "/method/$method?$parameters&access_token={$this->accessToken}";
	//      $querySig = md5($queryString . $this->accessSecret);
	//      return json_decode(file_get_contents(
	//          "http://api.vk.com{$queryString}&sig=$querySig"
	//      ), 1);
	//  }

	public function init($access_token, $user_id = null, $accessSecret = null) {
	    $this->access_token = $access_token;
	    $this->user_id = $user_id;
	    $this->accessSecret = $accessSecret;
	    return true;
	}



   public function callMethod($method, $params, $nohttps = false, $owner_rights = false){
    return false;
        if(!$nohttps) {
            $params['access_token'] = $this->access_token;
	        	return $this->get("https://api.vk.com/method/$method", $params);
        } else {
            if($owner_rights) $this->access_token = $this->owner_token;
            if($owner_rights) $this->accessSecret = $this->owner_token_secr;

            if(!$this->access_token) return false;
            if(!$this->accessSecret) return false;
           
            // $params['access_token'] = $this->accessSecret;
            $params['access_token'] = $this->access_token;
            $queryParams = http_build_query($params);
		        $queryString = "/method/$method?$queryParams";
            $querySig = md5($queryString . $this->accessSecret);
            $params['sig'] = $querySig;

            return $this->get("https://api.vk.com/method/$method", $params);
        }

    }

	public function getQuery($url, $params, $parse = true)
	{   
	    $query = urldecode(http_build_query($params));
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url . '?' . $query);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    $result = curl_exec($curl);
	    curl_close($curl);

	    if ($parse) {
	        $result = json_decode($result, true);
	    }

	    return $result;
	}

}