<?php
namespace Application\zLibrary;

class extMapping {

  public $map;

  public function __construct()
  {

    $this->map = array (

      'text/vnd.rn-realtext' => 'rt',
      'application/x-rtf' => 'rtf',
      'text/richtext' => 'rtf',
      'application/rtf' => 'rtf',
      'application/msword' => 'doc',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
      'application/octet-stream' => 'doc',
      
      'application/excel' => 'xls',
      'application/x-excel' => 'xls',
      'application/x-msexcel' => 'xls',
      'application/vnd.ms-excel' => 'xls',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',

      'application/pdf' => 'pdf',
      'application/x-pdf' => 'pdf',

      'image/jpeg' => 'jpg',
      'image/pjpeg' => 'jpg',
      'image/png' => 'png',
      'image/bmp' => 'bmp',

      'application/x-compressed' =>  'zip',
      'application/x-zip-compressed' => 'zip',
      'application/zip' => 'zip',
      'multipart/x-zip' => 'zip',
      'multipart/x-gzip' => 'gzip',
      'application/x-gzip' => 'gzip',
      'application/gnutar' => 'tgz',

      'application/powerpoint' => 'ppt',
      'application/x-mspowerpoint' => 'ppt',
      'application/mspowerpoint' =>  'ppt',
      'application/vnd.ms-powerpoint' => 'ppt',



      'audio/mpeg3' => 'mp3',
      'audio/x-mpeg-3' => 'mp3',


    );

    return $this;
  }

  public function getExt($mime)
  {
    if(isset($this->map[$mime])) return $this->map[$mime];
    else return false;
  }

}