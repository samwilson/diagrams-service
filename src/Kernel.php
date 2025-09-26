<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel {

	use MicroKernelTrait;

	protected function configureContainer( ContainerConfigurator $container ): void {
		$container->import( '../config/{packages}/*.yaml' );
		$container->import( '../config/{packages}/' . $this->environment . '/*.yaml' );
		$container->import( '../config/{services}.yaml' );
		$container->import( '../config/{services}_' . $this->environment . '.yaml' );
	}

	// protected function configureRoutes( RoutingConfigurator $routes ): void {
	// 	$routes->import( '../config/{routes}/' . $this->environment . '/*.yaml' );
	// 	$routes->import( '../config/{routes}/*.yaml' );
	// }
}
