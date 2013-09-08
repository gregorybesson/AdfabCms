<?php

namespace AdfabCmsTest\Controller\Frontend;
use \AdfabCms\Entity\Page as PageEntity;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../TestConfig.php'
        );

        parent::setUp();
    }

    public function testIndexActionNoIdentifier()
    {
    
    	$this->dispatch('/page');
    	$this->assertResponseStatusCode(404);
    }
    
    public function testIndexActionNonExistentPage()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $pluginManager    = $this->getApplicationServiceLocator()->get('ControllerPluginManager');

        $page = new PageEntity();

        //mocking the method checkExistingEntry
        $f = $this->getMockBuilder('AdfabCms\Service\Page')
        ->setMethods(array('getPageMapper'))
        ->disableOriginalConstructor()
        ->getMock();

        $serviceManager->setService('adfabcms_page_service', $f);
        
        $pageMapperMock = $this->getMockBuilder('AdfabCms\Mapper\Page')
        ->disableOriginalConstructor()
        ->getMock();
        
        $f->expects($this->once())
        ->method('getPageMapper')
        ->will($this->returnValue($pageMapperMock));
        
        $pageMapperMock->expects($this->once())
        ->method('findByIdentifier')
        ->will($this->returnValue(false));

    	$this->dispatch('/page/fakepage');
    	$this->assertResponseStatusCode(404);
    }
    
    public function testIndexActionNonActivePage()
    {
    	$serviceManager = $this->getApplicationServiceLocator();
    	$serviceManager->setAllowOverride(true);
    
    	$pluginManager    = $this->getApplicationServiceLocator()->get('ControllerPluginManager');
    
    	$page = new PageEntity();
    	$page->setActive(false);
    
    	//mocking the method checkExistingEntry
    	$f = $this->getMockBuilder('AdfabCms\Service\Page')
    	->setMethods(array('getPageMapper'))
    	->disableOriginalConstructor()
    	->getMock();
    
    	$serviceManager->setService('adfabcms_page_service', $f);
    
    	$pageMapperMock = $this->getMockBuilder('AdfabCms\Mapper\Page')
    	->disableOriginalConstructor()
    	->getMock();
    
    	$f->expects($this->once())
    	->method('getPageMapper')
    	->will($this->returnValue($pageMapperMock));
    
    	$pageMapperMock->expects($this->once())
    	->method('findByIdentifier')
    	->will($this->returnValue($page));
    
    	$this->dispatch('/page/fakepage');
    	$this->assertResponseStatusCode(404);
    }
    
    public function testIndexActionPage()
    {
    	$serviceManager = $this->getApplicationServiceLocator();
    	$serviceManager->setAllowOverride(true);
    
    	$pluginManager    = $this->getApplicationServiceLocator()->get('ControllerPluginManager');
    
    	$page = new PageEntity();
    	$page->setActive(true);
    	$page->setTitle('titre');
    	$page->setContent('content');
    	$page->setIdentifier('fakepage');
    
    	//mocking the method checkExistingEntry
    	$f = $this->getMockBuilder('AdfabCms\Service\Page')
    	->setMethods(array('getPageMapper'))
    	->disableOriginalConstructor()
    	->getMock();
    
    	$serviceManager->setService('adfabcms_page_service', $f);
    
    	$pageMapperMock = $this->getMockBuilder('AdfabCms\Mapper\Page')
    	->disableOriginalConstructor()
    	->getMock();
    
    	$f->expects($this->once())
    	->method('getPageMapper')
    	->will($this->returnValue($pageMapperMock));
    
    	$pageMapperMock->expects($this->once())
    	->method('findByIdentifier')
    	->will($this->returnValue($page));
    
    	$this->dispatch('/page/fakepage');
    	
    	$this->assertModuleName('adfabcms');
    	$this->assertActionName('index');
    	$this->assertControllerName('adfabcms');
    	$this->assertControllerClass('IndexController');
    	$this->assertMatchedRouteName('frontend/cms');

    }
}
