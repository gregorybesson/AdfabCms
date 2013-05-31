<?php

namespace AdfabCms\Controller\Admin;

use AdfabCms\Service\Block as AdminBlockService;
use AdfabCms\Entity\Block as EntityBlock;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlockController extends AbstractActionController
{
protected $options, $blockMapper;

    /**
     * @var adminBlockService
     */
    protected $adminBlockService;

    public function listAction()
    {
        $blockMapper = $this->getBlockMapper();
        $blocks = $blockMapper->findAllBy(array('created_at' => 'DESC'));
        if (is_array($blocks)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($blocks));
        } else {
            $paginator = $blocks;
        }

        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));

        return array(
            'blocks' => $paginator
        );
    }

    public function createAction()
    {
        $block = new EntityBlock();

        $form = $this->getServiceLocator()->get('adfabcms_block_form');
        $form->get('submit')->setlabel('Add');
        $form->setAttribute('action', $this->url()->fromRoute('zfcadmin/adfabcmsadmin/blocks/create', array('blockId' => 0)));
        $form->setAttribute('method', 'post');
        $form->bind($block);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('adfab-cms/block/block');

        if ($this->getRequest()->isPost()) {
            $block = $this->getAdminBlockService()->create((array) $this->getRequest()->getPost(), $block);
            if ($block) {
                $this->flashMessenger()->setNamespace('adfabcms')->addMessage('The block was created');

                return $this->redirect()->toRoute('zfcadmin/adfabcmsadmin/blocks/list');
            }
        }

        return $viewModel->setVariables(array('createBlockForm' => $form));
    }

    public function editAction()
    {
        $service = $this->getAdminBlockService();
        $form = $this->getServiceLocator()->get('adfabcms_block_form');

        $viewModel = new ViewModel();
        $viewModel->setTemplate('adfab-cms/block/block');

        $blockId = $this->getEvent()->getRouteMatch()->getParam('blockId');
        if (!$blockId) {
            return $this->redirect()->toRoute('zfcadmin/adfabcmsadmin/blocks/create');
        }

        $block = $service->getBlockMapper()->findById($blockId);
        $form->get('submit')->setLabel('Update');
        $form->setAttribute('action', $this->url()->fromRoute('zfcadmin/adfabcmsadmin/blocks/edit', array('blockId' => $blockId)));
        $form->setAttribute('method', 'post');
        $form->bind($block);

        $form = $this->getServiceLocator()->get('adfabcms_block_form');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $block = $this->getAdminBlockService()->edit((array) $request->getPost(), $block);
            if ($block) {
                $this->flashMessenger()->setNamespace('adfabcms')->addMessage('The block was created');

                return $this->redirect()->toRoute('zfcadmin/adfabcmsadmin/blocks/list');
            }
        }

        return $viewModel->setVariables(array('createBlockForm' => $form));
    }
	
	public function removeAction()
    {
        $blockId = $this->getEvent()->getRouteMatch()->getParam('blockId');

        if (!$blockId) {
            return $this->redirect()->toRoute('zfcadmin/adfabcmsadmin/blocks/list');
        }

        $block = $this->getAdminBlockService()->getBlockMapper()->findById($blockId);

        if ($block) {
            try {
                $this->getAdminBlockService()->getBlockMapper()->remove($block);
                $this->flashMessenger()->setNamespace('adfabcms')->addMessage('The block has been deleted');
            } catch (\Doctrine\DBAL\DBALException $e) {
                
            }
        }

        return $this->redirect()->toRoute('zfcadmin/adfabcmsadmin/blocks/list');
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

    public function getBlockMapper()
    {
        if (null === $this->blockMapper) {
            $this->blockMapper = $this->getServiceLocator()->get('adfabcms_block_mapper');
        }

        return $this->blockMapper;
    }

    public function setBlockMapper(BlockMapperInterface $blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

    public function getAdminBlockService()
    {
        if (!$this->adminBlockService) {
            $this->adminBlockService = $this->getServiceLocator()->get('adfabcms_block_service');
        }

        return $this->adminBlockService;
    }

    public function setAdminBlockService(AdminBlockService $service)
    {
        $this->adminBlockService = $service;

        return $this;
    }
}
