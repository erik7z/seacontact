<?php
namespace Application\Filter;

use Zend\Filter\AbstractFilter;

class ParseYoutubeLink extends AbstractFilter
{
	/**
	 * @var array
	 */
	protected $options = [
	    'format' => 'id',
	];

	/**
	 * Sets default option values for this instance
	 *
	 * @param array|Traversable|bool|null $format
	 */
	public function __construct($format = 'id')
	{
	    if ($format !== null) {
	        if (static::isOptions($format)) {
	            $this->setOptions($format);
	        } else {
	            $this->setFormat($format);
	        }
	    }
	}

	/**
	 * Sets the allowWhiteSpace option
	 *
	 * @param  bool $flag
	 * @return Alnum Provides a fluent interface
	 */
	public function setFormat($format = 'id')
	{
	    $this->options['format'] = $format;
	    return $this;
	}

	/**
	 * Whether white space is allowed
	 *
	 * @return bool
	 */
	public function getFormat()
	{
	    return $this->options['format'];
	}


	public function filter($value)
	{	
		// if (preg_match("/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)(?'id'[^#\&\?]*).*/", $value, $match))
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)(?\'id\'[^"&?/ ]{11})%i', $value, $match) && isset($match['id'])) {
		    if($this->options['format'] == 'link') return '//www.youtube.com/embed/'.$match['id']; 
		    else return $match['id'];
		}
		return false;
	}

}

