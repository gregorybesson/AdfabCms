<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'adfabcms_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/AdfabCms/Entity'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'AdfabCms\Entity'  => 'adfabcms_entity'
                )
            )
        )
    ),

    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type'         => 'phpArray',
                'base_dir'     => __DIR__ . '/../language',
                'pattern'      => '%s.php',
                'text_domain'  => 'adfabcms'
            ),
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
        ),
        'template_path_stack' => array(
            'adfabcms' => __DIR__ . '/../view/admin',
        	'adfabcms' => __DIR__ . '/../view/frontend',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'adfabcms'                  => 'AdfabCms\Controller\Frontend\IndexController',
            'adfabcmsadminpage'         => 'AdfabCms\Controller\Admin\PageController',
            'adfabcmsadminblock'        => 'AdfabCms\Controller\Admin\BlockController',
            'adfabcmsadmindynablock'    => 'AdfabCms\Controller\Admin\DynablockController',
        ),
    ),

    'dynacms' => array(
        'dynablocks' => array(
            'newsletter_subscription' => array(
                'title'       => 'Bloc de souscription',
                'description' => 'bloc dynamique',
                'widget'      => 'AdfabNewsletter\Widget\Subscribe',
            ),
        ),
        'dynareas' => array(
            'column_home' => array(
                'title' => 'Colonne de la home',
                'description' => 'ceci est une description',
                'location' => 'adfab-game\index\index',
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'cms' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/page/:id',
                    'defaults' => array(
                        'controller' => 'adfabcms',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/:p]',
                            'defaults' => array(
                                'controller' => 'adfabcms',
                                'action'     => 'list',
                            ),
                        ),
                    ),
                ),
            ),
            'winner' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/les-gagnants',
                    'defaults' => array(
                        'controller' => 'adfabcms',
                        'action'     => 'winnerList',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'page' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id',
                            'defaults' => array(
                                'controller' => 'adfabcms',
                                'action'     => 'winnerPage',
                            ),
                        ),
                    ),
                    'pagination' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:p]',
                            'defaults' => array(
                                'controller' => 'adfabcms',
                                'action'     => 'winnerList',
                            ),
                        ),
                    ),
                ),
            ),

            'zfcadmin' => array(
                'child_routes' => array(
                    'adfabcmsadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/cms',
                            'defaults' => array(
                                'controller' => 'adfabcmsadminpage',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'pages' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/pages',
                                    'defaults' => array(
                                        'controller' => 'adfabcmsadminpage',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:filter][/:p]',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminpage',
                                                'action'     => 'list',
                                                'filter' 	 => 'DESC'
                                            ),
                                            'constraints' => array(
				                                'filter' => '[a-zA-Z][a-zA-Z0-9_-]*',
				                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/create',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminpage',
                                                'action'     => 'create'
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:pageId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminpage',
                                                'action'     => 'edit',
                                                'userId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:pageId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminpage',
                                                'action'     => 'remove',
                                                'userId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'blocks' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/blocks',
                                    'defaults' => array(
                                        'controller' => 'adfabcmsadminblock',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:p]',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminblock',
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/create',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminblock',
                                                'action'     => 'create'
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:blockId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminblock',
                                                'action'     => 'edit',
                                                'blockId'    => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:blockId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadminblock',
                                                'action'     => 'remove',
                                                'userId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'dynablocks' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/dynablocks',
                                    'defaults' => array(
                                        'controller' => 'adfabcmsadmindynablock',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:p]',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadmindynablock',
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:dynareaId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadmindynablock',
                                                'action'     => 'create',
                                                'dynareaId'=> 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:dynareaId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadmindynablock',
                                                'action'     => 'edit',
                                                'dynareaId'=> 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:dynareaId',
                                            'defaults' => array(
                                                'controller' => 'adfabcmsadmindynablock',
                                                'action'     => 'remove',
                                                'dynareaId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'core_layout' => array(
        'AdfabCms' => array(
            'default_layout' => 'layout/2columns-right',
            'children_views' => array(
                'col_right'  => 'application/common/column_right.phtml',
               ),
               'controllers' => array(
                   'adfabcms' => array(
                    'default_layout' => 'layout/2columns-right',
                    'actions' => array(
                        'index' => array(
                            'default_layout' => 'layout/2columns-left',
                            'children_views' => array(
                                'col_left'  => 'adfab-user/layout/col-user.phtml',
                            ),
                        ),
                        'winner' => array(
                            'default_layout' => 'layout/2columns-right',
                            'children_views' => array(
                                'col_right'  => 'application/common/column_right.phtml',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
        	'index' => array(
                'controller' => 'adfabcms',
                'action'     => 'index',
            ),
            'winner' => array(
                'label' => 'Les gagnants',
                'route' => 'les-gagnants',
                'controller' => 'adfabcms',
                'action'     => 'winnerList',
            ),
            'page' => array(
                'label' => 'Les gagnants',
                'route' => 'les-gagnants/:id',
                'controller' => 'adfabcms',
                'action'     => 'winnerPage',
            ),
        ),
        'admin' => array(
            'adfabcms' => array(
                'label' => 'Les articles',
                'route' => 'zfcadmin/adfabcmsadmin/pages/list',
                'resource' => 'cms',
                'privilege' => 'list',
                'pages' => array(
                    'list-pages' => array(
                        'label' => 'Liste des articles',
                        'route' => 'zfcadmin/adfabcmsadmin/pages/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                        'pages' => array(
		                    'edit-page' => array(
		                    	'label' => 'Editer un article',
		                        'route' => 'zfcadmin/adfabcmsadmin/pages/edit',
		                        'resource' => 'cms',
		                        'privilege' => 'edit',
		                    ),
						),
                    ),
                    'create-page' => array(
                        'label' => 'Créer un article',
                        'route' => 'zfcadmin/adfabcmsadmin/pages/create',
                        'resource' => 'cms',
                        'privilege' => 'add',
                    ),
                    'list-block' => array(
                        'label' => 'Liste des blocs de contenu',
                        'route' => 'zfcadmin/adfabcmsadmin/blocks/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                    'create-block' => array(
                        'label' => 'Créer un bloc de contenu',
                        'route' => 'zfcadmin/adfabcmsadmin/blocks/create',
                        'resource' => 'cms',
                        'privilege' => 'add',
                    ),
                    'list-dynablock' => array(
                        'label' => 'Liste des blocs dynamiques',
                        'route' => 'zfcadmin/adfabcmsadmin/dynablocks/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                ),
            ),
        ),
    )
);
