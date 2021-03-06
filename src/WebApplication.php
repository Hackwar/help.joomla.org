<?php
/**
 * Joomla! Help Site
 *
 * @copyright  Copyright (C) 2016 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * Portions of this code are derived from the previous help screen proxy component,
 * please see https://github.com/joomla-projects/help-proxy for attribution
 */

namespace Joomla\Help;

use Joomla\Application\AbstractWebApplication;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\Web\WebClient;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A basic web application class for handing HTTP requests.
 *
 * @since  __DEPLOY_VERSION__
 */
class WebApplication extends AbstractWebApplication
{
	/**
	 * The application's controller resolver.
	 *
	 * @var    ControllerResolverInterface
	 * @since  __DEPLOY_VERSION__
	 */
	protected $controllerResolver;

	/**
	 * The application's router.
	 *
	 * @var    RouterInterface
	 * @since  __DEPLOY_VERSION__
	 */
	protected $router;

	/**
	 * Class constructor.
	 *
	 * @param   ControllerResolverInterface  $controllerResolver  The application's controller resolver
	 * @param   RouterInterface              $router              The application's router
	 * @param   Input                        $input               An optional argument to provide dependency injection for the application's
	 *                                                            input object.
	 * @param   Registry                     $config              An optional argument to provide dependency injection for the application's
	 *                                                            config object.
	 * @param   WebClient                    $client              An optional argument to provide dependency injection for the application's
	 *                                                            client object.
	 * @param   ResponseInterface            $response            An optional argument to provide dependency injection for the application's
	 *                                                            response object.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(
		ControllerResolverInterface $controllerResolver,
		RouterInterface $router,
		Input $input = null,
		Registry $config = null,
		WebClient $client = null,
		ResponseInterface $response = null
	)
	{
		$this->controllerResolver = $controllerResolver;
		$this->router             = $router;

		// Call the constructor as late as possible (it runs `initialise`).
		parent::__construct($input, $config, $client, $response);
	}

	/**
	 * Method to run the application routines.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function doExecute(): void
	{
		$route = $this->router->parseRoute($this->get('uri.route'), $this->input->getMethod());

		// Add variables to the input if not already set
		foreach ($route->getRouteVariables() as $key => $value)
		{
			$this->input->def($key, $value);
		}

		\call_user_func($this->controllerResolver->resolve($route));
	}
}
