<?php
return array(
    'route_manager' => array(
        'invokables' => array(
            'mxc-route' => 'MxcRoutes\Route'
        )
    ),
    'service_manager' => array(
	   'factories' => array(
    	   'MxcRoutes\RouteOptions' => 'MxcRoutes\RouteOptionsFactory',
	   ),
    ),
);
