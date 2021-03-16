<?php
namespace Application\zLibrary;

class uploadImageCrop extends AbstractUpload {

	protected $types = array('image/gif', 'image/png', 'image/jpeg', 'image/jpg', 'image/pjpeg');
	protected $max_size = '15242880';
	protected $upload_dir;
	protected $www_dir;

	protected $max_w = '3200';
	protected $max_h = '3200';

	protected $thumb_w = '360';
	protected $thumb_h = '240';

	protected $crop_w = '180';
	protected $crop_h = '270';

	protected $error = array (
		'big_size' => 'The file size is too big',
		'forbidden_type' => 'The file type is not acceptable',
		);


	public function __construct($optionsOrDir = null)
	{
		parent::__construct($optionsOrDir);
		if(isset($optionsOrDir) && $optionsOrDir){
			$this->init($optionsOrDir);
		} else $this->init(_PICSROOT_);
	}

	public function init($optionsOrDir)
	{
		if(is_array($optionsOrDir)){
			if(isset($optionsOrDir['upload_dir'])) 
				$this->upload_dir = $optionsOrDir['upload_dir'];
				$this->www_dir = '/'._PICSWWW_;
				// $this->getWWWfromRoot($root);
			if(isset($optionsOrDir['thumb'])) {
				$this->thumb_w = $options['thumb'][0];
				$this->thumb_h = $optionsOrDir['thumb'][1];
			}
			if(isset($optionsOrDir['size'])) {
				$this->max_w = $optionsOrDir['size'][0];
				$this->max_h = $optionsOrDir['size'][1];		
			}
			if(isset($optionsOrDir['crop'])) {
				$this->crop_w = $optionsOrDir['crop'][0];
				$this->crop_h = $optionsOrDir['crop'][1];		
			}
		} else {
			$this->upload_dir = $optionsOrDir;
			$this->www_dir = '/'._PICSWWW_;
		}				
		z_createDir($this->upload_dir);		
	}


	public function uploadCVavatar($uploadImage, $user_id)
	{
		return $this->upload($uploadImage, false, $user_id, 'cv_avatar');
	}

	public function upload($uploadImage, $makeThumb = true, $user_id, $image_type = 'image')
	{
		if(!$uploadImage['tmp_name'] || !$uploadImage) return false;
		if(!in_array($uploadImage['type'], $this->types))
			throw new \Application\Exception\Exception($this->error['forbidden_type']);
		if($uploadImage['size'] > $this->max_size)
			throw new \Application\Exception\Exception($this->error['big_size']);

		$source = @$this->__toJpeg($uploadImage);
		if(!$source) $source = $this->__emptyImg();

		return $this->getImageFromSource($source, $makeThumb, $user_id, $image_type);
	}

	public function getImageFromSource($source, $makeThumb = true, $user_id, $image_type = 'image')
	{
		$name = z_generateName($this->upload_dir);
		$image_name = $name.'.jpg';
		$image_src = $this->upload_dir.$image_name;

		$source = $this->__resizeImage($source);
		imagejpeg($source, $image_src, 100);

		if(true == $makeThumb){
			$thumb_name = 'th_'.$name.'.jpg';
			$thumb_src = $this->upload_dir.$thumb_name;
			$thumb_source = $this->__makeThumb($source);
			imagejpeg($thumb_source, $thumb_src, 100);
			$result['thumb'] = $thumb_name;
			$result['thumb_src'] = $thumb_src;
			$result['thumb_www'] = $this->www_dir.$thumb_name;
			$result['thumb_w'] = imagesx($thumb_source); 
			$result['thumb_h'] = imagesy($thumb_source);
			$this->UploadsTable->recordUpload($thumb_name, $thumb_src, $image_type, $user_id);
		}

		$result['img_base_url'] = '/'._PICSWWW_;
		$result['img'] = $image_name;
		$result['img_src'] = $image_src;
		$result['img_www'] = $this->www_dir.$image_name;
		$result['img_w'] = imagesx($source); 
		$result['img_h'] = imagesy($source); 
		$result['crop_w'] = $this->crop_w;
		$result['crop_h'] = $this->crop_h;
		$this->UploadsTable->recordUpload($image_name, $image_src, $image_type, $user_id);
		
		return $result;
	}

	public function crop($user_id, $uploaded_image_name, $width, $height, $start_w, $start_h)
	{
		$crop_name = 'crop_'.$uploaded_image_name;
		$thumb_name = 'th_'.$uploaded_image_name;
		$img_src = $this->upload_dir.$uploaded_image_name; 
		$crop_src = $this->upload_dir.$crop_name;
		$newImage = imagecreatetruecolor($width, $height);
		$source = imagecreatefromjpeg($img_src);
		imagecopyresampled($newImage, $source, 0, 0,$start_w, $start_h, $width, $height, $width, $height);
		$newImage = $this->__resizeImage($newImage, $this->crop_w);

		$crop_src = $this->upload_dir.$crop_name;
		imagejpeg($newImage, $crop_src, 100);
		chmod($crop_src, 0777);

		$this->UploadsTable->recordUpload($crop_name, $crop_src, 'avatar', $user_id);
		return array(
			'img' => $uploaded_image_name,
			'thumb' => $thumb_name,
			'img_src' => $img_src,
			'img_www' => $this->www_dir.'/'.$uploaded_image_name,
			'crop' => $crop_name,
			'crop_src' => $crop_src,
			'crop_www' => $this->www_dir.'/'.$crop_name,
			'crop_w' => imagesx($newImage),
			'crop_h' => imagesy($newImage),
			);
	}


	protected function __emptyImg()
	{
		/* Создаем пустое изображение */
		$im  = imagecreatetruecolor(150, 30);
		$bgc = imagecolorallocate($im, 255, 255, 255);
		$tc  = imagecolorallocate($im, 0, 0, 0);

		imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

		/* Выводим сообщение об ошибке */
		imagestring($im, 1, 5, 5, 'Image Loading Error', $tc);
		return $im;
	}


	public function __loadJpeg($url, $exc = 0)
	{
	    $im = @imagecreatefromjpeg($url);
	    if(!$im) {
	    	if($exc) throw new \Application\Exception\Exception("image not loaded", 1);
	    	$im = $this->__emptyImg();
	    }
	    return $im;
	}


	protected function __toJpeg($file)
	{

		if ($file['type'] == 'image/jpeg' || $file['type'] == 'image/pjpeg') 
			$source = imagecreatefromjpeg($file['tmp_name']);
		elseif ($file['type'] == 'image/png') 
			$source = imagecreatefrompng($file['tmp_name']); 
		elseif ($file['type'] == 'image/gif') 
			$source = imagecreatefromgif($file['tmp_name']); 
		else throw new \Application\Exception\Exception("Only jpeg/png/gif files could be converted");
		return $source;		
	}

	public function __resizeImage($image, $max_w = null) {
		if($max_w == null) $max_w = $this->max_w;
		$width = imagesx($image); 
		$height = imagesy($image);

		if ($width > $max_w) {

			$scale = $max_w/$width;
			$newImageWidth = ceil($width * $scale);
			$newImageHeight = ceil($height * $scale);

			$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);

			imagecopyresampled($newImage,$image,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

			return $newImage;
		} else {
			return $image;
		}
	}


	public function __makeThumb($image){

		$width = imagesx($image); 
		$height = imagesy($image);

		$scale = $this->thumb_w/$width;
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);

		$thumb_source = imagecreatetruecolor($newImageWidth,$newImageHeight);
		imagecopyresampled($thumb_source,$image,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

		return $thumb_source;
	}

}