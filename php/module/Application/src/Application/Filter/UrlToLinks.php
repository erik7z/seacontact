<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class UrlToLinks implements FilterInterface 
{

	private $searches;
	private $matches;

	public function filter($text)
	{	
			// cleaning from href links
			$text = $this->parseHrefs($text);

			//replacing remaining urls with new hrefs
			// $urlReg = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			// $urlReg = "$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i";
			// $urlReg = "#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i"; //not bad
			// $urlReg = "/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?:@\-_=#])*/"; // best
			// #((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i
			$urlReg = "/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.[a-zA-Z]{2,3}(\/\S*)?/"; // best2
			$text = $this->replaceLinks($urlReg, $text);
			// putting back old href links in position
			$text = str_ireplace($this->searches, $this->matches, $text);

			$this->searches = [];
			$this->matches = [];


		return $text;
	}

	public function parseHrefs($text){
		$hrefReg = '/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1"[^>]*>(.*)<\/a>/siU';
		$i = 0;
		$text = preg_replace_callback($hrefReg, function($match){
			global $i;
			$i++;
			$this->matches[] = $match[0];
			$this->searches[] = '###'.$i;
			return '###'.$i;
		}, $text);
		return $text;
	}


	public function replaceLinks($urlReg, $text){
		$urls = array();
		$urlsToReplace = array();
		if(preg_match_all($urlReg, $text, $urls)) {
			$numOfMatches = count($urls[0]);
			$numOfUrlsToReplace = 0;
			for($i=0; $i<$numOfMatches; $i++) {
				$alreadyAdded = false;
				$numOfUrlsToReplace = count($urlsToReplace);
				for($j=0; $j<$numOfUrlsToReplace; $j++) {
					if($urlsToReplace[$j] == $urls[0][$i]) {
						$alreadyAdded = true;
					}
				}
				if(!$alreadyAdded) {
					array_push($urlsToReplace, $urls[0][$i]);
				}
			}
			$numOfUrlsToReplace = count($urlsToReplace);
			for($i=0; $i<$numOfUrlsToReplace; $i++) {
				$full_link = $urlsToReplace[$i];
				// if (strpos($full_link, 'http') !== true) {
				// 	$full_link = 'http://'.$urlsToReplace[$i];
				// }
				$full_link = str_replace('http://', '', $full_link);
				$text = str_replace($urlsToReplace[$i], "<a href=\"http://".$full_link."\" target=\"blank\" >".$urlsToReplace[$i]."</a> ", $text);
			}
		}
		return $text;
	}

	

}

