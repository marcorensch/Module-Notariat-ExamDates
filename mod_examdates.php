<?php
/**
 * @package    mod_examdates
 *
 * @author     marcorensch <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;

require 'helper.php';

defined('_JEXEC') or die;

// Get exam dates from component
$items = ModExamDatesHelper::getExams($params);


//Include UIkit
if($params->get('load_uikit',1)){
    $document = Factory::getDocument();
    $document->addStyleSheet(JURI::base() . '/modules/mod_examdates/tmpl/assets/uikit/css/uikit.min.css');
    $document->addScript(JURI::base() . '/modules/mod_examdates/tmpl/assets/uikit/js/uikit.min.js');
    $document->addScript(JURI::base() . '/modules/mod_examdates/tmpl/assets/uikit/js/uikit-icons.min.js');
}

// The below line is no longer used in Joomla 4
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require ModuleHelper::getLayoutPath('mod_examdates', $params->get('layout', 'default'));
