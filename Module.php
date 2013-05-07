<?php

namespace AdfabCms;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $translator = $serviceManager->get('translator');
        AbstractValidator::setDefaultTranslator($translator,'adfabcore');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            /*'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),*/
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'adfabcms_doctrine_em' => 'doctrine.entitymanager.orm_default',
                'adfabcms_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
            ),

            'invokables' => array(
                'adfabcms_block_service'     => 'AdfabCms\Service\Block',
                'adfabcms_dynablock_service' => 'AdfabCms\Service\Dynablock',
                'adfabcms_page_service'      => 'AdfabCms\Service\Page',
            ),

            'factories' => array(
                'adfabcms_module_options' => function ($sm) {
                    $config = $sm->get('Configuration');

                    return new Options\ModuleOptions(isset($config['adfabcms']) ? $config['adfabcms'] : array());
                },

                'adfabcms_page_hydrator' => function () {
                    $hydrator = new Mapper\PageHydrator();

                    return $hydrator;
                },

                'adfabcms_page_mapper' => function ($sm) {
                    $mapper = new Mapper\Page(
                        $sm->get('adfabcms_doctrine_em'),
                        $sm->get('adfabcms_module_options')
                    );
                    $mapper->setHydrator($sm->get('adfabcms_page_hydrator'));

                    return $mapper;
                },

                'adfabcms_block_hydrator' => function () {
                    $hydrator = new Mapper\BlockHydrator();

                    return $hydrator;
                },

                'adfabcms_block_mapper' => function ($sm) {
                    $mapper = new Mapper\Block(
                        $sm->get('adfabcms_doctrine_em'),
                        $sm->get('adfabcms_module_options')
                    );
                    $mapper->setHydrator($sm->get('adfabcms_block_hydrator'));

                    return $mapper;
                },

                'adfabcms_dynablock_mapper' => function ($sm) {
                    $mapper = new Mapper\Dynablock(
                            $sm->get('adfabcms_doctrine_em'),
                            $sm->get('adfabcms_module_options')
                    );

                    return $mapper;
                },

                'adfabcms_page_form' => function($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Page(null, $sm, $translator);
                    $page = new Entity\Page();
                    $form->setInputFilter($page->getInputFilter());
                    //$form->setInputFilter($filter);
                    return $form;
                },

                'adfabcms_block_form' => function($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Block(null, $translator);
                    //$form->setInputFilter($filter);
                    return $form;
                },

                'adfabcms_dynablock_form' => function($sm) {
                $translator = $sm->get('translator');
                $form = new Form\Admin\Dynablock(null, $translator);
                //$form->setInputFilter($filter);
                return $form;
                },
            ),
        );
    }

    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'adfabBlock' => function($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\Block;
                    $viewHelper->setBlockMapper($locator->get('adfabcms_block_mapper'));

                    return $viewHelper;
                },
                'adfabDynablock' => function($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\Dynablock;
                    $viewHelper->setBlockMapper($locator->get('adfabcms_block_mapper'));
                    $viewHelper->setDynablockMapper($locator->get('adfabcms_dynablock_mapper'));

                    return $viewHelper;
                },
            ),
        );
    }
}
