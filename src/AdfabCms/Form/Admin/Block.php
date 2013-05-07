<?php

namespace AdfabCms\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\I18n\Translator\Translator;

class Block extends ProvidesEventsForm
{
    protected $serviceManager;

    public function __construct($name = null, Translator $translator)
    {
        //$this->setServiceManager($serviceManager);
        parent::__construct();

        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0,
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'title',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
                'name' => 'identifier',
                'options' => array(
                        'label' => 'identifier',
                ),
                'attributes' => array(
                        'type' => 'text'
                ),
        ));

        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'is_active',
                'options' => array(
                    'value_options' => array(
                            '0' => $translator->translate('No', 'adfabcms'),
                            '1' => $translator->translate('Yes', 'adfabcms'),
                    ),
                    'label' => $translator->translate('Active', 'adfabcms'),
                ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'on_call',
            'options' => array(
                 'label' => 'This Block is available for DynaBlock',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'content',
            'options' => array(
                'label' => $translator->translate('Content', 'adfabcms'),
            ),
            'attributes' => array(
                'cols' => '10',
                'rows' => '10',
                'id' => 'content',
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
        ->setLabel('Create')
        ->setAttributes(array(
                'type'  => 'submit',
        ));

        $this->add($submitElement, array(
                'priority' => -100,
        ));
    }

/*    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }*/
}
