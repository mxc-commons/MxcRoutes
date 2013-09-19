<?php

namespace MxcRoutes;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Mvc\Router\Http\Part;
use Zend\Mvc\Router\Http\Literal;
use Traversable;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\PriorityList;
use Zend\Stdlib\SplStack;

/**
 * 
 * @author frank.hein
 *
 */
class Route extends Part implements ServiceLocatorAwareInterface {

    protected $serviceLocator;
    protected $controller;
    protected $childRouteModel;
    protected $isInit = false;
    
    /**
     * Construct from ::factory options param
     * 
     * @param array $options
     */
    public function __construct(array $options = array()) 
    {
        $this->route = new Literal($options['route'], $options['defaults']);
        $this->controller = $options['defaults']['controller'];
        $this->childRouteModel = $options['defaults']['child_route_model'];
        $this->childRoutes = null;
        $this->prototypes = null;
        $this->mayTerminate = true;
    }

    /**
     * {@inheritDoc}
     */
    public static function factory($options = array())
    {
        if (!isset($options['route'])) {
            throw new Exception\InvalidArgumentException('Missing "route" in options array');
        }
        if (!isset($options['defaults'])) {
            throw new Exception\InvalidArgumentException('Missing "defaults" array in options array.');
        }
        if (!isset($options['defaults']['controller'])) {
            throw new Exception\InvalidArgumentException('Missing "controller" in defaults array.');
        }
        if (!isset($options['defaults']['action'])) {
            throw new Exception\InvalidArgumentException('Missing "action" in defaults array.');
        }
        if(!isset($options['defaults']['child_route_model']))
            $options['defaults']['child_route_model'] = 'default';
        
        return new Route($options);
    }

    /**
     * initialize child routes from RouteOptions
     * 
     */
    protected function init()
    {
        $childRouteModels = $this->getServiceLocator()   // routePluginManager
            ->getServiceLocator()                        // service Manager
            ->get('MxcRoutes\RouteOptions');        

        $rms = new SplStack;
        $current = $this->childRouteModel;

        do {
            //-- get the child route definitions
            $models = $childRouteModels->getChildRouteModels();
            if (!isset($models[$current])) {
                throw new Exception\InvalidArgumentException(sprintf('Invalid child_route_model specification (%s).',$this->childRouteModel));
            }
            $model = $models[$current];
            $rms->push($model);
            $current = isset($model['extends']) ? $model['extends'] : null;
        } while ($current);

        $this->childRoutes = array();

        do {
            $model = $rms->pop();
            $cr = isset($model['child_routes']) ? $model['child_routes'] : array();
            $this->childRoutes = array_merge($this->childRoutes, $cr);
            $this->mayTerminate = isset($model['may_terminate']) ? $model['may_terminate'] : true;
        } while(!$rms->isEmpty());
        
        //-- inject the base route's controller to each each child route
        foreach ($this->childRoutes as $cr)
            $cr['options']['defaults']['controller'] = $this->controller;
        
        $this->routes = new PriorityList();
        $this->isInit = true;
    }

    /** 
	 * {@inheritDoc}
	 */
    public function match(Request $request, $pathOffset = null, array $options = array()) {
       if (!$this->isInit) $this->init();
       return parent::match($request, $pathOffset, $options);
    }
    
    /** 
	 * {@inheritDoc}
	 */
    public function assemble(array $params = array(), array $options = array())
    {
       if (!$this->isInit) $this->init();
       return parent::assemble($params, $options);
    } 

    /** 
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
	    return $this->routePluginManager;
	}

	/** 
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
		$this->routePluginManager = $serviceLocator;
	}

}