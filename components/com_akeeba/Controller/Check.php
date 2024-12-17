<?php
/**
 * @package   akeebabackup
 * @copyright Copyright (c)2006-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Backup\Site\Controller;

// Protect from unauthorized access
defined('_JEXEC') || die();

use Akeeba\Backup\Site\Controller\Mixin\FrontEndPermissions;
use Akeeba\Backup\Site\Model\Statistics;
use FOF40\Container\Container;
use FOF40\Controller\Controller;
use FOF40\Controller\Mixin\PredefinedTaskList;
use Joomla\CMS\Component\ComponentHelper;

/**
 * Controller for the front-end Check Backups features
 */
class Check extends Controller
{
	use PredefinedTaskList;
	use FrontEndPermissions;

	/** @var bool  */
	private $noFlush = false;

	/**
	 * Overridden constructor
	 *
	 * @param   Container  $container  The application container
	 * @param   array      $config     The configuration array
	 */
	public function __construct(Container $container, array $config)
	{
		parent::__construct($container, $config);

		$this->setPredefinedTaskList(['main']);

		$this->noFlush = ComponentHelper::getParams('com_akeeba')->get('no_flush', 0) == 1;
	}

	/**
	 * Checks for failed backups and sends out any notification emails
	 */
	public function main()
	{
		// Check permissions
		$this->checkPermissions();

		/** @var Statistics $model */
		$model  = $this->container->factory->model('Statistics')->tmpInstance();
		$result = $model->notifyFailed();

		$message = $result['result'] ? '200 ' : '500 ';
		$message .= implode(', ', $result['message']);

		@ob_end_clean();
		header('Content-type: text/plain');
		header('Connection: close');
		echo $message;

		if (!$this->noFlush)
		{
			flush();
		}

		$this->container->platform->closeApplication();
	}
}
