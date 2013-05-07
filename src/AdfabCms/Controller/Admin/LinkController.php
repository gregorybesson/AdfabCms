<?php

namespace AdfabCms\Controller\Admin;

//use AdfabCms\Service\Link as AdminLinkService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LinkController extends AbstractActionController
{
    protected $options, $linkMapper;

    /**
     * @var UserService
     */
    protected $adminLinkService;

    public function listAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('adfab-cms/links/list');
		return $viewModel;

        
    }

    public function createAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('adfab-cms/links/create');
		return $viewModel;
    }

    public function editAction()
    {
       
    }

    public function removeAction()
    {
        
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('adfabcms_module_options'));
        }

        return $this->options;
    }


}
