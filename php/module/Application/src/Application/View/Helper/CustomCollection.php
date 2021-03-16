<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\FormCollection;
use Zend\Form\ElementInterface;
use Zend\Form\Element\Collection as CollectionElement;
use Zend\Form\FieldsetInterface;

class CustomCollection extends FormCollection
{
    protected $defaultElementHelper = 'customRow';
    private $elementformat =
            '
            <fieldset class="col-md-12">    
                <div class="row" style="padding-bottom: 0.5em">
                    <span class="glyphicon glyphicon-remove pull-left delete-entry" data-id="#"></span>
                </div>
                <div class="form-group">
                        %s
                </div>
            </fieldset>
            ';

    public function __invoke(ElementInterface $element = null, $wrap = true)
    {
        if(!$element){
            return $this;
        }
        $this->setShouldWrap($wrap);
        return $this->render($element);
    }


    public function setElementFormat($format) {
        $this->elementformat = $format;
        return $this;
    }

    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            return '';
        }

        $markup           = '';
        $templateMarkup   = '';
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $elementHelper    = $this->getElementHelper();
        $fieldsetHelper   = $this->getFieldsetHelper();



        if ($element instanceof CollectionElement && $element->shouldCreateTemplate()) {
            $templateMarkup = $this->renderTemplate($element);
        }

        foreach ($element->getIterator() as $elementOrFieldset) {
            if ($elementOrFieldset instanceof FieldsetInterface) {
                $markup .= sprintf($this->elementformat,$fieldsetHelper($elementOrFieldset));
            } elseif ($elementOrFieldset instanceof ElementInterface) {
                $markup .= $elementHelper($elementOrFieldset);
            }
        }

        if (!empty($templateMarkup)) {
            $markup .= $templateMarkup;
        }

        return $markup;
    }

    public function renderTemplate(CollectionElement $collection)
    {
        $elementHelper          = $this->getElementHelper();
        $escapeHtmlAttribHelper = $this->getEscapeHtmlAttrHelper();
        $templateMarkup         = '';

        $elementOrFieldset = $collection->getTemplateElement();

        if ($elementOrFieldset instanceof FieldsetInterface) {
            $templateMarkup .= $this->render($elementOrFieldset);
        } elseif ($elementOrFieldset instanceof ElementInterface) {
            $templateMarkup .= $elementHelper($elementOrFieldset);
        }

        $element = sprintf($this->elementformat, $templateMarkup);

        return sprintf(
            '<span data-template="%s"></span>',
            $escapeHtmlAttribHelper($element)
        );
    }
}