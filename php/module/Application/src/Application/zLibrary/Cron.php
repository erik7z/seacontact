<?php
namespace Application\zLibrary;

class Cron {


	public function go()
	{
		echo 'Go Go Go';
		$file = '';
		$time = date("H:i:s / d-M-Y", time());
		if(file_exists('test.txt')) {
		  $file = file_get_contents(__DIR__.'/test.txt');
		}

		file_put_contents(__DIR__.'/test.txt', $file.PHP_EOL.'Cron job test '.$time);

		echo 'test cron jobs completed';



	}

	public function goAction()
	{
		echo 'Go Go Go 2222';
		$file = '';
		$time = date("H:i:s / d-M-Y", time());
		if(file_exists('test.txt')) {
		  $file = file_get_contents(__DIR__.'/test.txt');
		}

		file_put_contents(__DIR__.'/test.txt', $file.PHP_EOL.'Cron job test '.$time);

		echo 'test cron jobs completed';



	}
}