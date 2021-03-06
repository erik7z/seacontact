<?php

// debugging
function d($somevar = '', $cont = false) {
	echo 'Debug: <pre> ';
	print_r($somevar);
	echo '</pre>';
	if(!$cont) die;
}

function zgenerateIP()
{
	return mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255);
}

function zcamel2dashed($className) {
    return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className));
}

function zgetNav($resource)
{

	if(strpos($resource, 'admin') !== false) $nav = 'admin_nav';
	elseif(strpos($resource, 'userinfo') !== false) $nav = 'user_navigation';
	elseif(strpos($resource, 'companyinfo') !== false) $nav = 'company_navigation';
	else $nav = 'navigation';
	return $nav;
}

function zgetMBpass($mailbox)
{
	switch ($mailbox) {
		case _MBOFFICE_:
			return _MBOFFICEPWD_;
			break;
		case _MBCREW_:
			return _MBCREWPWD_;
			break;
		default:
			return false;
			break;
	}
}

function zstripForHashTag($word)
{
	return str_replace(array(' ', '/', '.', '\\'), '_', str_replace(array('-', '[', ']', '(', ')'), '', ($word)));
}

function zmakeHashTagString($comma_separated_string)
{
	$tags = explode(',', $comma_separated_string);
	$result = '';
	if (count($tags) > 1) {
		for ($i=0; $i < count($tags); $i++) {
			$result .= '#'.$tags[$i].' ';
		}
	}
	$result .= '#seacontact ';
	return $result;
}

function removeBrakes($text)
{
	$breaks = array("<br />","<br>","<br/>","\r", '&nbsp;', '&amp;nbsp;');
 	return str_ireplace($breaks, '', $text);
}

function zbr2nl($text){
	$breaks = array("<br />","<br>","<br/>");
 	$text = str_ireplace($breaks, "\n", $text);
 	return $text;
}

function zstripNl($text){
 	$text = str_ireplace(["\n", "\r"], '', $text);
 	return $text;
}

function zgetNotesVisibility($visibility)
{
	$translator = \Application\Translator\StaticTranslator::getTranslator();
	switch ($visibility) {
		case 0:
			$result = $translator->translate('Any Company');
			break;
		case 1:
			$result = $translator->translate('Company Private');
			break;
		case 2:
			$result = $translator->translate('Any Admin');
			break;
		case 3:
			$result = $translator->translate('Admin Private');
			break;
		default:
			$result = $translator->translate('Not stated');
			break;
	}
	return $result;
}

function zgetVisaStatus($status)
{
	$translator = \Application\Translator\StaticTranslator::getTranslator();
	switch ($status) {
		case 1:
			$result = $translator->translate('Yes');
			break;
		case 2:
			$result = $translator->translate('No');
			break;
		default:
			$result = $translator->translate('Not stated');
			break;
	}
	return $result;
}

function zgetUserLogin($user_info, $predicat = '')
{
	if(is_object($user_info)) $user_info = (array) $user_info;

	$login = $predicat.'login';
	$user_id = $predicat.'user_id';
	$user = $predicat.'user';
	$company_id = $predicat.'company_id';

	if(isset($user_info[$login]) && $user_info[$login] != '') return $user_info[$login];
	else if(isset($user_info[$user_id]) && $user_info[$user_id]) return 'id'.$user_info[$user_id];
	else if(isset($user_info[$company_id]) && $user_info[$company_id]) return 'id'.$user_info[$company_id];
	else if(isset($user_info[$user]) && $user_info[$user]) return 'id'.$user_info[$user];
	else return null;
}

function zgetUserName($user_info, $predicat = '', $short_name = 0)
{
	if(!is_array($user_info)) $user_info = (array) $user_info;
	if(!isset($user_info['checked_count'])) $user_info['checked_count'] = 0;
	else if($user_info['checked_count'] > 1) return null;
	$name = $predicat.'name';
	$surname = $predicat.'surname';
	$full_name = $predicat.'full_name';
	$company_name = $predicat.'company_name';
	if((isset($user_info[$predicat.'role']) && $user_info[$predicat.'role'] == 'company') || (isset($user_info[$predicat.'company_name']) && $user_info[$predicat.'company_name'] != '')) $user_name = $user_info[$company_name];
	else if(isset($user_info[$name]) && $user_info[$name] != '' && isset($user_info[$surname]) && $user_info[$surname] != '') $user_name = $user_info[$name].' '.$user_info[$surname];
	else if (isset($user_info[$full_name]) && $user_info[$full_name] != '') $user_name = $user_info[$full_name];
	else if (isset($user_info[$name]) && $user_info[$name] != '') $user_name = $user_info[$name];
	else $user_name = zgetUserLogin($user_info, $predicat);
	if(!$user_name) {
		$user_info['checked_count']++;
		$user_name = zgetUserName($user_info, 'soc_');
	}
	if($short_name) $user_name = zbreakToWords($user_name)[0];
	return $user_name;
}

function zgetUserAvatar($user_info, $predicat = '', $strict = 0)
{
	if(is_object($user_info)) $user_info = (array) $user_info;

	$imagefile = '';
	$avatar = $predicat.'avatar';
	$cv_avatar = $predicat.'cv_avatar';
    if(isset($user_info[$avatar]) && $user_info[$avatar] != '') {
		if(strpos($user_info[$avatar], 'http') !== false) $imagefile = $user_info[$avatar];
		else $imagefile = _ADDRESS_._PICSWWW_.$user_info[$avatar];
	}
	else if(isset($user_info[$cv_avatar]) && $user_info[$cv_avatar] != '') {
		if(strpos($user_info[$cv_avatar], 'http') !== false) $imagefile = $user_info[$cv_avatar];
        $imagefile = _ADDRESS_._PICSWWW_.$user_info[$cv_avatar];
	}

	if (!(file_exists($imagefile))) {
        $imagefile = _AVABLANKUSER_;
        if(
            (isset($user_info[$predicat.'role']) && $user_info[$predicat.'role'] == 'company')
            || (isset($user_info[$predicat.'company_name']) && $user_info[$predicat.'company_name'] != '')
        ) $imagefile = _AVABLANKCOMPANY_;

        if(!$strict) $imagefile = _ADDRESS_._IMGWWW_.$imagefile;
    }

    return $imagefile;
}

function zgetVacancyAvatar($vacancy) {
    if(isset($vacancy->avatar) && file_exists(_PICSWWW_.$vacancy->avatar)) return _PICSWWW_.$vacancy->avatar;
    else return _IMGWWW_._AVABLANKCOMPANY_;
}


function zgetUserCvAvatar($user_info, $predicat = '')
{
	if(is_object($user_info)) $user_info = (array) $user_info;

	$cv_avatar = $predicat.'cv_avatar';
	if(isset($user_info[$cv_avatar]) && $user_info[$cv_avatar] != ''){
		if(strpos($user_info[$cv_avatar], 'http') !== false) $avatar = $user_info[$cv_avatar];
		else $avatar = _ADDRESS_._PICSWWW_.$user_info[$cv_avatar];
	}
	return (file_exists($avatar)) ? $avatar : _ADDRESS_._IMGWWW_._AVABLANKUSER_;
}

function zhashInfo($text, $open_chars = 6, $show = false)
{
	if($show == true) return $text;
	$str = '';
	foreach(zbreakToWords($text) as $word) {
		$chars_count = strlen($word) - $open_chars;
		$replace = '';
		for ($i=0; $i < $chars_count; $i++) {
			$replace .= '*';
		}
		$str .= substr_replace($word, $replace, $open_chars);
		$str .= ' ';
	}
	return $str;
}

function zhashUserName($text, $open_chars = 0, $show = false)
{
	if($show == true) return $text;
	$str = '';
	$w = 0;
	foreach(zbreakToWords($text) as $word) {
		if($w == 0) $str .= $word.' ';
		else {
			$chars_count = strlen($word) - $open_chars;
			$replace = '';
			for ($i=0; $i < $chars_count; $i++) {
				$replace .= '*';
			}
			$str .= substr_replace($word, $replace, $open_chars);
			$str .= ' ';
		}
		$w++;
	}
	return $str;
}
function zenglishLevel($level_int)
{
	$translator = \Application\Translator\StaticTranslator::getTranslator();

	switch ($level_int) {
		case 5:
			$res = $translator->translate('Fluent');
			break;
		case 4:
			$res = $translator->translate('Good');
			break;
		case 3:
			$res = $translator->translate('Satisfactory');
			break;
		case 2:
			$res = $translator->translate('Poor');
			break;
		default:
			$res = $translator->translate('Not stated');
			break;
	}
	return $res;
}


function ztranslit($string, $to_rus = 0) {
    $converter = array(
        "??" => "a",   "??" => "b",   "??" => "v",
        "??" => "g",   "??" => "d",   "??" => "e",
        "??" => "e",   "??" => "zh",  "??" => "z",
        "??" => "i",   "??" => "y",   "??" => "k",
        "??" => "l",   "??" => "m",   "??" => "n",
        "??" => "o",   "??" => "p",   "??" => "r",
        "??" => "s",   "??" => "t",   "??" => "u",
        "??" => "f",   "??" => "h",   "??" => "c",
        "??" => "ch",  "??" => "sh",  "??" => "sch",
        "??" => "\'",  "??" => "y",   "??" => "\'",
        "??" => "e",   "??" => "yu",  "??" => "ya",

        "??" => "A",   "??" => "B",   "??" => "V",
        "??" => "G",   "??" => "D",   "??" => "E",
        "??" => "E",   "??" => "Zh",  "??" => "Z",
        "??" => "I",   "??" => "Y",   "??" => "K",
        "??" => "L",   "??" => "M",   "??" => "N",
        "??" => "O",   "??" => "P",   "??" => "R",
        "??" => "S",   "??" => "T",   "??" => "U",
        "??" => "F",   "??" => "H",   "??" => "C",
        "??" => "Ch",  "??" => "Sh",  "??" => "Sch",
        "??" => "\'",  "??" => "Y",   "??" => "\'",
        "??" => "E",   "??" => "Yu",  "??" => "Ya",
    );
	if($to_rus) $converter = array_flip($converter);
    return strtr($string, $converter);
}

function zstr2url($str) {
    // ?????????????????? ?? ????????????????
    $str = rus2translit($str);
    // ?? ???????????? ??????????????
    $str = strtolower($str);
    // ?????????????? ?????? ???????????????? ?????? ???? "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // ?????????????? ?????????????????? ?? ???????????????? '-'
    $str = trim($str, "-");
    return $str;
}

//habr version
function ztranslit2en($string, $gost=false)
{
	if($gost)
	{
		$replace = array("??"=>"A","??"=>"a","??"=>"B","??"=>"b","??"=>"V","??"=>"v","??"=>"G","??"=>"g","??"=>"D","??"=>"d",
                "??"=>"E","??"=>"e","??"=>"E","??"=>"e","??"=>"Zh","??"=>"zh","??"=>"Z","??"=>"z","??"=>"I","??"=>"i",
                "??"=>"I","??"=>"i","??"=>"K","??"=>"k","??"=>"L","??"=>"l","??"=>"M","??"=>"m","??"=>"N","??"=>"n","??"=>"O","??"=>"o",
                "??"=>"P","??"=>"p","??"=>"R","??"=>"r","??"=>"S","??"=>"s","??"=>"T","??"=>"t","??"=>"U","??"=>"u","??"=>"F","??"=>"f",
                "??"=>"Kh","??"=>"kh","??"=>"Tc","??"=>"tc","??"=>"Ch","??"=>"ch","??"=>"Sh","??"=>"sh","??"=>"Shch","??"=>"shch",
                "??"=>"Y","??"=>"y","??"=>"E","??"=>"e","??"=>"Iu","??"=>"iu","??"=>"Ia","??"=>"ia","??"=>"","??"=>"");
	}
	else
	{
		$arStrES = array("????","????","????","????","????","????","????","????","????","????","????","????","????","????");
		$arStrOS = array("????","????","????","????","????","????","????","????","????","????","????","????","????","????");
		$arStrRS = array("??$","??$","??$","??$","??$","??$","??$","??$","??$","??$","??$","??$","@","@");

		$replace = array("??"=>"A","??"=>"a","??"=>"B","??"=>"b","??"=>"V","??"=>"v","??"=>"G","??"=>"g","??"=>"D","??"=>"d",
                "??"=>"Ye","??"=>"e","??"=>"Ye","??"=>"e","??"=>"Zh","??"=>"zh","??"=>"Z","??"=>"z","??"=>"I","??"=>"i",
                "??"=>"Y","??"=>"y","??"=>"K","??"=>"k","??"=>"L","??"=>"l","??"=>"M","??"=>"m","??"=>"N","??"=>"n",
                "??"=>"O","??"=>"o","??"=>"P","??"=>"p","??"=>"R","??"=>"r","??"=>"S","??"=>"s","??"=>"T","??"=>"t",
                "??"=>"U","??"=>"u","??"=>"F","??"=>"f","??"=>"Kh","??"=>"kh","??"=>"Ts","??"=>"ts","??"=>"Ch","??"=>"ch",
                "??"=>"Sh","??"=>"sh","??"=>"Shch","??"=>"shch","??"=>"","??"=>"","??"=>"Y","??"=>"y","??"=>"","??"=>"",
                "??"=>"E","??"=>"e","??"=>"Yu","??"=>"yu","??"=>"Ya","??"=>"ya","@"=>"y","$"=>"ye");

		$string = str_replace($arStrES, $arStrRS, $string);
		$string = str_replace($arStrOS, $arStrRS, $string);
	}

	return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}



function zhtml_cut($code, $limit)
{
	if ( strlen($code) <= $limit )
	{
	    return $code;
	}

	$html = mb_substr($code, 0, $limit);
	preg_match_all ( "#<([a-zA-Z]+)#", $html, $result );

	foreach($result[1] AS $key => $value)
	{
	    if ( strtolower($value) == 'br' )
	    {
	        unset($result[1][$key]);
	    }
	}
	$openedtags = $result[1];

	preg_match_all ( "#</([a-zA-Z]+)>#iU", $html, $result );
	$closedtags = $result[1];

	foreach($closedtags AS $key => $value)
	{
	    if ( ($k = array_search($value, $openedtags)) === FALSE )
	    {
	        continue;
	    }
	    else
	    {
	        unset($openedtags[$k]);
	    }
	}

	if ( empty($openedtags) )
	{
	    if ( strpos($code, ' ', $limit) == $limit )
	    {
	        return $html."...";
	    }
	    else
	    {
	        return mb_substr($code, 0, strpos($code, ' ', $limit))."...";
	    }
	}

	$position = 0;
	$close_tag = '';
	foreach($openedtags AS $key => $value)
	{
	    $p = strpos($code, ('</'.$value.'>'), $limit);

	    if ( $p === FALSE )
	    {
	        $code .= ('</'.$value.'>');
	    }
	    else if ( $p > $position )
	    {
	        $close_tag = '</'.$value.'>';
	        $position = $p;
	    }
	}

	if ( $position == 0 )
	{
	    return $code;
	}
	mb_internal_encoding('UTF-8');
	return mb_substr($code, 0, $position).$close_tag."...";
}

function zlessChars($text, $chars_count = 255)
{
	$text = strip_tags($text);
	mb_internal_encoding('UTF-8');
	if(strlen($text) >= $chars_count) $text = mb_substr($text, 0, $chars_count).'...';
	return $text;
}

function zshorterText($text, $words_count = 30)
{
	$chars = '????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????1234567890_-@.';
	$words = str_word_count($text, 2, $chars);
	if(count($words) < $words_count) return $text;
	$i = 0;
	foreach ($words as $key => $value) {
		$i++;
		if($i == $words_count) break;
	}
	$key--;
	mb_internal_encoding('UTF-8');
	$result = mb_substr($text, 0, $key).'...';
	return $result;
}

function zbreakToWords($text)
{
	$chars = '????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????1234567890_-@.';
	return str_word_count($text, 2, $chars);
}

function zdaysToMonths($days)
{
	return round($days / 30, 1).' Months';
}

function zconvertFormDate($date)
{
	if(is_array($date)) {
		$day = (isset($date['day']))? $date['day'] : '01';
		$month = (isset($date['month']))? $date['month'] : '01';
		return strtotime($day.'-'.$month.'-'.$date['year']);
	} else {
		if(strpos($date, '-')) return strtotime($date);
		else return $date;
	}
}

function zgetOnlineStatus($last_activity_timestamp, $return = 'word')
{
	$translator = \Application\Translator\StaticTranslator::getTranslator();
	$timeout = time() - $last_activity_timestamp;
	if('bool' == $return){
		$status = ($timeout < _ONLINE_TIME_)? true : false;
	} else if('word' == $return) {
		$status = ($timeout < _ONLINE_TIME_)? $translator->translate('online') : $translator->translate('offline');
	} else if('text' == $return) {
		switch ($timeout) {
			case ($timeout < _ONLINE_TIME_):
				$status = $translator->translate('online');
				break;
			case ($timeout > _ONLINE_TIME_ && $timeout < 1200):
				$status = sprintf($translator->translate('was %s minutes ago'), 15);
				break;
			case ($timeout > 1200):
				$status = sprintf($translator->translate('was %s minutes ago'), 20);
				break;
			default:
				$status = $translator->translate('Last Online :').zformatDateTime($last_activity_timestamp);
				break;
		}
	}
	return $status;
}

function zgetTimePosted($timestamp, $return = 'text')
{
	$translator = \Application\Translator\StaticTranslator::getTranslator();
	$timeout = time() - $timestamp;
	if('bool' == $return){
		$status = ($timeout < 300)? true : false;
	} else {
		$date = date('d/m/Y', $timestamp);
		switch ($timeout) {
			case ($timeout < 0):
				$status = $translator->translate('Will be published'). ' > '.zformatDateTime($timestamp);
				break;
			case ($timeout < 300):
				$status = $translator->translate('Just now');
				break;
			case ($timeout > 300 && $timeout < 600):
				$status = $translator->translate('5 minutes ago');
				break;
			case ($timeout > 600 && $timeout < 3600):
				$status = $translator->translate('Last hour');
				break;
			case ($timeout > 3600 && $timeout < 172800):
					if($date == date('d/m/Y')) {
						$status = $translator->translate('Today').' '.zformatTime($timestamp);
					} else if($date == date('d/m/Y',time() - (24 * 60 * 60))) {
				      	$status = $translator->translate('Yesterday').' '.zformatTime($timestamp);
				    } else $status = zformatDateTime($timestamp);
				break;
			default:
				$status = zformatDateTime($timestamp);
				break;
		}
	}
	return $status;
}




//------------- Time formatting

function zformatMusicTime($time) {
	return date('i:s', $time);
}

function zformatSlashDate($time) {
	return date('d/m/Y', $time);
}
function zformatDate($time) {
	return date("d-M", $time);
}

function zformatDateYear($time) {
	return date("d-M-Y", $time);
}


function zformatTime($time) {
	return date("H:i", $time);
}

function zformatDateTime($time) {
	return date("H:i / d-M", $time);
}

function zformatDateTimeYear($time) {
	return date("H:i / d-M-Y", $time);
}


// ??????????????t ????????????
function z_delete($file) {
	if(is_array($file)) {
		foreach ($file as $key => $value) {
			if(!file_exists($value)) continue;
			z_delete($value);
		}
		return true;
	}
	if(file_exists($file)) {
		unlink($file);
		return true;
	} else return false;
}

function z_makeResourceMap($resource_name, $sub_resources = [])
{
	$map = [];
	foreach ($sub_resources as $resource) {
		$map[$resource_name.'\\'.$resource] = $resource_name;
	}
	return $map;
}

function z_generateNameFromMethod($method, $short_name = 1)
{
    $full_name = explode("::", $method);
    $class_name = explode('\\', $full_name[0]);
    $class_name = end($class_name);
    $method_name = $full_name[1];
    if($short_name) $class_name = substr($class_name, 0, 3);
    if($short_name) $method_name = substr($method_name, 0, 10);
    $name = strtolower($class_name.'_'.$method_name);
    return $name;
}

function z_generateName($path = null, $ext = null, $name = null, $chars = 5) {
	$salt = null;

	for($i = 0; $i < $chars; $i++){
		$r_small = chr(mt_rand(65, 90));
		$r_nums = mt_rand(1, 9);
		$r_capital = chr(mt_rand(97, 122));
		$salt .= $r_small.$r_nums.$r_capital;
	}

	if($name) $file_name = $name.'_'.$salt;
	else $file_name = $salt;

	if($ext) $file_name = $file_name.'.'.$ext;

	if($path) {
		if(file_exists($path.$file_name)) return z_generateName($path, $ext, $file_name, $chars);
		else return $file_name;
	}
	return $file_name;
}

function z_createDir($dir) {
	if(!is_dir($dir)){
		mkdir($dir, 0777);
		chmod($dir, 0777);
	}
	return $dir;
}




return array();