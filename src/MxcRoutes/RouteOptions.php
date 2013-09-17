<?php

namespace MxcRoutes;

use Zend\Stdlib\AbstractOptions;

class RouteOptions extends AbstractOptions {
      
    /**
     * @var array
     */
    protected $childRouteModels = array();        	

    /**
	 * @return the $childRouteModels
	 */
	public function getChildRouteModels() {
		return $this->childRouteModels;
	}

	/**
	 * @param multitype: $childRouteModels
	 */
	public function setChildRouteModels($childRouteModels) {
		$this->childRouteModels = $childRouteModels;
	}
}