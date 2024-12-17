<?php
/**
 * @package   admintools
 * @copyright Copyright (c)2010-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\AdminTools\Admin\Controller;

use Joomla\CMS\Language\Text;

defined('_JEXEC') || die;

class HtaccessMaker extends ServerConfigMaker
{
	/**
	 * The prefix for the language strings of the information and error messages
	 *
	 * @var string
	 */
	protected $langKeyPrefix = 'COM_ADMINTOOLS_LBL_HTACCESSMAKER_';

	public function addphphandler()
	{
		$this->csrfProtection();

		/** @var \Akeeba\AdminTools\Admin\Model\HtaccessMaker $model */
		$model = $this->getModel();

		$msg  = Text::_('COM_ADMINTOOLS_HTACCESSMAKER_LBL_PHPHANDLERS_SAVED');
		$type = null;

		try
		{
			$model->includePhpHandlers();
		}
		catch (\Exception $e)
		{
			$msg  = $e->getMessage();
			$type = 'warning';
		}

		$this->setRedirect('index.php?option=com_admintools&view=HtaccessMaker', $msg, $type);
	}
}
