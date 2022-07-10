<?php
namespace Application\zLibrary;

class MailerCustom {


	public function parse($header = null, $body =null, $text = null)
	{

		// в переменной $text сейчас все письмо вместе с заголовками.

		// разделяем письмо на заголовки и тело, еще раз советую почитать Почтовый стандарт "MIME" (RFC1521) (http://webi.ru/webi_files/26_15_f.html)
		$struct=$this->fetch_structure($text);

		// теперь раскладываем заголовки по полочкам
		// и получаем удобный ассоциативный массив с удобным обращением к любому заголовку.
		// например $mass_header['subject'] == "=?windows-1251?B?7/Do4uXy?="
		// $mass_header=$this->decode_header($struct['header']);
		$mass_header=$this->decode_header($header);
		// чтобы воспользоваться заголовком, который может содержать не латинские символы
		// например тема письма, нужно прогнать заголовок через функцию декодирования.
		$mass_header["subject"] = $this->decode_mime_string($mass_header["subject"]);
		$result['subject'] = $mass_header["subject"];
		$result['time'] = $mass_header["date"];
		preg_match_all("/~([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+~/i",$mass_header['to'],$regs);
		if(count($regs[0]) < 1) preg_match_all("~([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+~i",$mass_header['envelope-to'],$regs);
		$result['mail_to'] = array_map(function($i){
			return array('email' => $i);
		}, $regs[0]);
		
		preg_match_all("/~([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+~/i",$mass_header['from'],$regs);
		if(count($regs[0]) < 1) preg_match_all("/~([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+~/i",$mass_header['x-klms-antispam-envelope-from'],$regs);
		$result['from_mail'] = $regs[0][0];
		// если не передано тело возвращаем только заголовки
		if(!$body) return $result;
		// теперь можно использовать тему, теперь тут обычный читаемый текст

		// Сейчас разберем заголовок Content-Type, это тип содержимого. Определим, что в письме, только текст или еще и файлы.
		// Content-Type: text/plain; charset=Windows-1251 это обычное текстовое письмо
		// Content-Type: multipart/mixed; boundary="_----------=_118224799143839" это составное письмо из нескольких частей, с вложенными файлами.
		$type = $ctype = $mass_header['content-type'];
		$ctype = explode(";",$ctype);
		$types = explode("/",$ctype[0]);
		$maintype = trim(strtolower($types[0])); // text или multipart
		$subtype = trim(strtolower($types[1])); // а это подтип(plain, html, mixed)

		// сейчас проверяем тип содержимого письма
		// Если это обычное текстовое содержимое (текст или html) без вложений
		if($maintype=="text")
		{
		    // $subtype можно использовать эту переменную для определения текстовое письмо или html
		    // эту проверку можете поставить сами
		    // Передаем тело письма в функцию, на перекодирование. И так же посылаем заголовки, информирующие о том, как было закодировано письмо.
		    // $body = $this->compile_body($struct['body'],$mass_header["content-transfer-encoding"],$mass_header["content-type"]);
		    $body = $this->compile_body($body,$mass_header["content-transfer-encoding"],$mass_header["content-type"]);
		   $result['text'] = $body;
		}

		// теперь рассмотрим вариант, если письмо имеет несколько разных частей.
		// тут рассматриваю подтипы signed,mixed,related, но есть еще подтип alternative, который служит для альтернативного отображения письма.
		// например, письмо в html и к нему же можно добавить альтернативное текстовое содержание.
		// подробнее читайте про этот подтип в Почтовом стандарте "MIME" (RFC1521) (http://webi.ru/webi_files/26_15_f.html)
		elseif($maintype=="multipart" and preg_match('/'.$subtype.'/',"signed,mixed,related"))
		{
			// получаем метку-разделитель частей письма
		    $boundary=$this->get_boundary($mass_header['content-type']);
		    // $boundary='----=_NextPart_001_1A7B_191502E4.1C13765A';
		    // d($boundary);
		    // на основе этого разделителя разбиваем письмо на части
		    // $part = $this->split_parts($boundary,$struct['body']);
		    $part = $this->split_parts($boundary,$body);
		    // теперь обрабатываем каждую часть письма
		    for($i=0;$i<count($part);$i++) {

		        // разбиваем текущую часть на тело и заголовки
		        $email = $this->fetch_structure($part[$i]);
		        $header = $email["header"];
		        $body = $email["body"];

		        // разбираем заголовки на массив
		        $headers = $this->decode_header($header);
		        $ctype = $headers["content-type"];
		        $cid = $headers["content-id"];
		        $Actype = explode(";",$headers["content-type"]);
		        $types = explode("/",$Actype[0]);
		        $rctype = strtolower($Actype[0]);
		        // теперь проверяем, является ли эта часть прикрепленным файлом
		        $is_download = (preg_match("/name=/",$headers["content-disposition"].$headers["content-type"]) || $headers["content-id"] != "" || $rctype == "message/rfc822");

		        // теперь читаем и выводим само тело части, если это обычный текст
		        if($rctype == "text/plain" && !$is_download) {
		        	$body = $this->compile_body($body,$headers["content-transfer-encoding"],$headers["content-type"]);
		            $result['text'] = $body;
		               // print $body;
		        }

		        // если это html
		        elseif($rctype == "text/html" && !$is_download) {
		        	$body = $this->compile_body($body,$headers["content-transfer-encoding"],$headers["content-type"]);
		            $result['html'] = $body;
		        }

		        // и наконец, если это файл
		        elseif($is_download) {
		        	// Имя файла можно выдернуть из заголовков Content-Type или Content-Disposition
		            $cdisp = $headers["content-disposition"];
		            $ctype = $headers["content-type"];
		            $ctype2 = explode(";",$ctype);
		            $ctype2 = $ctype2[0];
		            $Atype = explode("/",$ctype);
		            $Acdisp = explode(";",$cdisp);
		            $fname = $Acdisp[1];
		            if(preg_match("/filename=(.*)/",$fname,$regs))
		            $filename = $regs[1];
		            if($filename == "" && preg_match("/name=(.*)/",$ctype,$regs))
		            $filename = $regs[1];
		            $filename = str_replace('"', '', $filename);
		            // $filename = preg_replace("\"(.*)\"","\1",$filename);
		            // как получили имя файла, теперь его нужно декодировать
		            $filename = trim($this->decode_mime_string($filename));
		            
		            // теперь читаем файл в переменную.
		            // $body = $this->compile_body($body,$headers["content-transfer-encoding"],$ctype);
		            $attachment['name'] = $filename;
		            $attachment['content'] = $body;
		            $attachment['type'] = $ctype2;
		            $result['attachments'][$i] = $attachment;
		            // содержимое файла теперь в переменной $body и сейчас можно отдать содержимое файла в браузер или например сохранить на диске
		            // $ft=fopen($filename,"wb");
		            // fwrite($ft,$body);
		            // fclose($ft);
		        }
		    }
		}

		return $result;


	}



	// При отправке почты, все не латинские символы в заголовках кодируется,
	// например тема письма может выглядеть так =?windows-1251?B?7/Du4uXw6uA=?=
	// вот такие тексты и будет преобразовывать эта функция
	public function decode_mime_string($subject) {
	    $string = $subject;
	    if(($pos = strpos($string,"=?")) === false) {
	    	// if(mb_check_encoding($string, 'cp1251') == true) {
	    	// 	$string = iconv('cp1251', 'utf-8', $string);
	    	// }
	    	// return $string;
	    }
	    while(!($pos === false)) {
	        $newresult .= substr($string,0,$pos);
	        $string = substr($string,$pos+2,strlen($string));
	        $intpos = strpos($string,"?");
	        $charset = substr($string,0,$intpos);
	        $enctype = strtolower(substr($string,$intpos+1,1));
	        $string = substr($string,$intpos+3,strlen($string));
	        $endpos = strpos($string,"?=");
	        $mystring = substr($string,0,$endpos);
	        $string = substr($string,$endpos+2,strlen($string));
	        if($enctype == "q") $mystring = quoted_printable_decode(preg_replace("/_/"," ",$mystring));
	        else if ($enctype == "b") $mystring = base64_decode($mystring);
	        $newresult .= $mystring;
	        $pos = strpos($string,"=?");
	    }

	    $result = $newresult.$string;
	    if(preg_match("/koi8/", $subject)) $result = convert_cyr_string($result, "k", "w");
	    if(preg_match("/KOI8/", $subject)) $result = convert_cyr_string($result, "k", "w");
	    return $result;
	}

	// перекодировщик тела письма.
	// Само письмо может быть закодировано и данная функция приводит тело письма в нормальный вид.
	// Так же и вложенные файлы будут перекодироваться этой функцией.
	public function compile_body($body,$enctype,$ctype) {
	    $enctype = explode(" ",$enctype); $enctype = $enctype[0];
	    if(strtolower($enctype) == "base64")
	    $body = base64_decode($body);
	    elseif(strtolower($enctype) == "quoted-printable")
	    $body = quoted_printable_decode($body);
	    if(preg_match("/koi8/", $ctype)) $body = convert_cyr_string($body, "k", "w");
	    return $body;
	}

	// Функция для выдергивания метки boundary из заголовка Content-Type
	// boundary это разделитель между разным содержимым в письме,
	// например, чтобы отделить файл от текста письма
	public function get_boundary($ctype){
	    if(preg_match('/boundary[ ]?=[ ]?(["]?.*)/i',$ctype,$regs)) {
	        // $boundary = preg_replace('/^\"(.*)\"$/', "\1", $regs[1]);
	        $boundary = $regs[1];
	        // d($regs);
	        // return trim("--$boundary");
	        return str_replace('"', '', $boundary);
	    }
	}

	// если письмо будет состоять из нескольких частей (текст, файлы и т.д.)
	// то эта функция разобьет такое письмо на части (в массив), согласно разделителю boundary
	public function split_parts($boundary,$body) {
	    $startpos = strpos($body,$boundary)+strlen($boundary)+2;
	    $lenbody = strpos($body,"\r\n$boundary--") - $startpos;
	    $body = substr($body,$startpos,$lenbody);
	    return explode($boundary."\r\n",$body);
	}

	// Эта функция отделяет заголовки от тела.
	// и возвращает массив с заголовками и телом
	public function fetch_structure($email) {
	    $ARemail = Array();
	    $separador = "\r\n\r\n";
	    $header = trim(substr($email,0,strpos($email,$separador)));
	    $bodypos = strlen($header)+strlen($separador);
	    $body = substr($email,$bodypos,strlen($email)-$bodypos);
	    $ARemail["header"] = $header;
	    $ARemail["body"] = $body;
	    return $ARemail;
	}

	// разбирает все заголовки и выводит массив, в котором каждый элемент является соответсвующим заголовком
	public function decode_header($header) {
		$headers = explode("\r\n",$header);
	    $decodedheaders = Array();
	    for($i=0;$i<count($headers);$i++) {
	        $thisheader = trim($headers[$i]);
	        if(!empty($thisheader))
	        if(!preg_match("/^[A-Z0-9a-z_-]+:/",$thisheader))
	        $decodedheaders[$lasthead] .= " $thisheader";
	        else {
	            $dbpoint = strpos($thisheader,":");
	            $headname = strtolower(substr($thisheader,0,$dbpoint));
	            $headvalue = trim(substr($thisheader,$dbpoint+1));
	            if($decodedheaders[$headname] != "") $decodedheaders[$headname] .= "; $headvalue";
	            else $decodedheaders[$headname] = $headvalue;
	            $lasthead = $headname;
	        }
	    }
	    return $decodedheaders;
	}

	// эта функция нам уже знакома. она получает данные и реагирует на точку, которая ставится сервером в конце вывода.
	public function get_data($pop_conn)
	{
	    $data="";
	    while (!feof($pop_conn)) {
	        $buffer = chop(fgets($pop_conn,1024));
	        $data .= "$buffer\r\n";
	        if(trim($buffer) == ".") break;
	    }
	    return $data;
	}


}