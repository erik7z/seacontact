<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class EmptyElement extends Element implements InputProviderInterface
{

    protected $attributes = array();

    protected $validators = array();

    protected $filters = array();




    //merging base element options with current ones
    public function setOptions($newOptions)
    {
        if(!class_exists($newOptions['baseElement'])) throw new \Application\Exception\Exception("no baseElement option provided!", 1);
        
        $baseElement = new $newOptions['baseElement'];
        $this->attributes = array_merge($this->attributes, $baseElement->getAttributes());
 
        if($baseElement instanceof InputProviderInterface) {
            $baseSpec = $baseElement->getInputSpecification();
            $this->filters = $this->mergeOptions($baseSpec['filters'], $newOptions['filters']);
            $this->validators = $this->mergeOptions($baseSpec['validators'], $newOptions['validators']);
        } else {
            $this->filters = $newOptions['filters'];
            $this->validators = $newOptions['validators'];
        }

        return parent::setOptions($newOptions);
    }



    public function getInputSpecification()
    {
        return array(
            'name' => $this->getName(),
            'required' => true,
            'filters' => $this->filters,
            'validators' => $this->validators,
        );
    }




    private function mergeOptions($baseOptions, $newOptions)
    {
        $newOptions = array_filter($newOptions, function($item) use ($baseOptions) {
            if(array_search($item, $baseOptions) !== false)  return false;
            return true;
        }); 

        return array_merge($baseOptions,$newOptions);
    }   



}
