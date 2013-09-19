MxcRoutes
===============
Version 1.0.1 created by Frank Hein and the mxc-commons team.

MxcRoutes is part of the [maxence openBeee initiative](http://www.maxence.de/mxcweb/index.php/themen/open-business/)
by [maxence business consulting gmbh, Germany](http://www.maxence.de). 

Introduction
------------

Tired of writing down the same routing configuration for your standard controller classes again and again? Then MxcRoutes may be something you have been waiting for. 

MxcRoutes provides an additional route type which generates the child route structure automatically according to template you provide once.

Multiple child route models are supported and get selected through the route definition.

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Features / Goals
----------------

Main design goal of MxcRoutes is to encapsulate the controller child route definition via
template defintions. So module programmers can setup the complete child route scheme for
a controller be defining a single route. 


**1. 	Provide the capability to configure a controller routing models with a single route definition** 
  

**2. Allow to select among several child route models by name**

Set `'child_route_model' => 'myChildRouteModelName'` in the `defaults` section of the MxcRoute.

**3. Allow to extend child route models by inheritence**

Set `'extends' => 'myParentChildRouteName'` within the child route model definition.

Installation
------------

### Main Setup

#### By cloning project

1. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "mxc-commons/mxc-routes": "dev-master"
    }
    ```

2. Now tell composer to download MxcRoutes by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'MxcRoutes',
        ),
        // ...
    );
    ```

Options
-------

The MxcRoutes module provides a single option to setup and select child route schemes. After installing MxcRoutes, copy
`./vendor/mxc-commons/mxc-routes/config/mxcroutes.global.php.dist` to
`./config/autoload/mxcroutes.global.php` and setup the child route schemes as desired.

The following option is available:

- **`child_route_models`** - Array of child route models: `<modelName> => array(<child_route>, ...), ... )`, where `modelName`is an identifier of your choice and each 
`child_route` is a ZF2 route definition. 

See `mxcroutes.global.php.dist` for an example configuration including model inheritance.

Note: You can easily setup a child route scheme by copying and pasting an existing child route
structure from your router configuration.

Note: Child route models may even contain nested child route definitions.
  
	'may_terminate' => true,
	'child_routes'  => array(
		...
	)

Setting an MxcRoute
-------------------

You add an MxcRoute to your router configuration almost the same way as you add a Literal route. Just set the type key to 'mxc-route'.

	                'album' => array(
                        'type' => 'mxc-route',
                        'options' => array(
                            'route' => '/album',
                            'defaults' => array(
                                'controller' => 'MxcMedia\Controller\Album',
                                'action' => 'list'
                            )
                        ),
                     ),
  
Using the configuration above MxcRoute will default to the child route model named `default`.
You can select a particular child route model by specifying a `child_route_model` key within
the 'defaults' section of the route definition.

	                'album' => array(
                        'type' => 'mxc-route',
                        'options' => array(
                            'route' => '/album',
                            'defaults' => array(
                                'controller' => 'MxcMedia\Controller\Album',
                                'action' => 'list'
								'child_route_model' => 'myChildRouteModelName'
                            )
                        ),
                     ),

Note: `'controller'` and `'action'` keys are mandatory, `'child_route_model'` is optional.


How MxcRoutes works
-------------------------

1. On Startup MxcRoutes registers a route plugin for the route type `mxc-route` with the RoutePluginManager
2. Every time the router finds a route of type `mxc-route` the Router instantiates an MxcRoute to handle this route definition.
3. The MxcRoute itself is derived from `Zend\Mvc\Router\Http\Part` route. It creates a literal for its own route definition, loads the child route model, sets the `controller` key in all child routes to the controller specified in the `mxc-route`.



Use Cases
---------

Common use cases for MxcRoutes are 

1. CRUD routing configurations

Credits
-------

MxcRoutes was inspired by the CrudRoute implementation of [SpiffyCrud](https://github.com/spiffyjr/spiffy-crud) by Kyle Spraggs. SpiffyCrud is a ZF2 module to allow rapid development of CRUD for entities.

License
-------

MxcRoutes is released under the New BSD License. See `license.txt`. 