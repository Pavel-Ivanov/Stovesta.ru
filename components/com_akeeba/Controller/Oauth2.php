<?php
/**
 * @package   akeebabackup
 * @copyright Copyright (c)2006-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Backup\Site\Controller;

defined('_JEXEC') || die;

use Akeeba\Backup\Site\Model\Oauth2\OAuth2Exception;
use Akeeba\Backup\Site\Model\Oauth2\OAuth2UriException;
use Akeeba\Backup\Site\Model\Oauth2\ProviderInterface;
use FOF40\Container\Container;
use FOF40\Controller\Controller;
use FOF40\View\DataView\Raw;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') || die;

class Oauth2 extends Controller
{
	public function __construct(Container $container, array $config = [])
	{
		$config['default_task'] = $config['default_task'] ?? 'step1';

		parent::__construct($container, $config);
	}

	/**
	 * Handle the first step of authentication: open the consent page
	 *
	 * @return  void
	 * @since   8.4.0
	 */
	public function step1(): void
	{
		/** @var Raw $view */
		$view           = $this->getView();
		$view->provider = $this->getProvider();
		$view->step1url = $view->provider->getAuthenticationUrl();

		$view->setLayout('step1');

		$this->display();
	}

	/**
	 * Handle the second step of authentication: exchange the code for a set of tokens
	 *
	 * @return  void
	 * @since   8.4.0
	 */
	public function step2(): void
	{
		/** @var Raw $view */
		$view           = $this->getView();
		$provider       = $this->getProvider();
		$view->provider = $provider;

		try
		{
			$view->tokens = $provider->handleResponse($this->input);

			$view->setLayout('default');
		}
		catch (OAuth2Exception $e)
		{
			$view->exception = $e;

			$view->setLayout('error');
		}
		catch (OAuth2UriException $e)
		{
			/** @var \Akeeba\Backup\Site\Model\Oauth2 $model */
			$model = $this->getModel();

			Factory::getApplication()->redirect($e->getUrl());
		}

		$this->display(false);
	}

	/**
	 * Handle exchanging a refresh token for a new set of tokens
	 *
	 * @return  void
	 * @since   8.4.0
	 */
	public function refresh(): void
	{
		$provider = $this->getProvider();

		try
		{
			$tokens = $provider->handleRefresh($this->input);

			$ret = [
				'access_token'      => $tokens['accessToken'],
				'refresh_token'     => $tokens['refreshToken'],
				'error'             => null,
				'error_description' => null,
				'error_url'         => null,
			];
		}
		catch (OAuth2Exception $e)
		{
			$ret = [
				'access_token'      => null,
				'refresh_token'     => null,
				'error'             => 'error',
				'error_description' => $e->getMessage(),
				'error_url'         => null,
			];
		}
		catch (OAuth2UriException $e)
		{
			$ret = [
				'access_token'      => null,
				'refresh_token'     => null,
				'error'             => 'error',
				'error_description' => $e->getMessage(),
				'error_url'         => $e->getUrl(),
			];
		}

		@ob_end_clean();

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public", false);

		header('Content-type: application/json');
		header('Connection: close');

		echo json_encode($ret);

		Factory::getApplication()->close(200);
	}

	/**
	 * Returns the OAuth2 helper provider for the requested engine
	 *
	 * @return  ProviderInterface
	 * @since   8.4.0
	 */
	protected function getProvider(): ProviderInterface
	{
		$engine = $this->input->get->getCmd('engine', '');

		if (empty($engine))
		{
			throw new \RuntimeException(
				Text::_('JERROR_ALERTNOAUTHOR'), 403
			);
		}

		/** @var \Akeeba\Backup\Site\Model\Oauth2 $model */
		$model = $this->getModel();

		if (!$model->isEnabled($engine))
		{
			throw new \RuntimeException(
				Text::_('JERROR_ALERTNOAUTHOR'), 403
			);
		}

		try
		{
			return $model->getProvider($engine);
		}
		catch (\InvalidArgumentException $e)
		{
			throw new \RuntimeException(
				Text::_('JERROR_ALERTNOAUTHOR'), 403
			);
		}
	}
}