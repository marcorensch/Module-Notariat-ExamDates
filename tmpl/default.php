<?php
/**
 * @package    mod_examdates
 *
 * @author     marcorensch <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$tblClasses = $params->get('table-class','');
$lblt = $params->get('label-tag','h4');
$lblcolcls = $params->get('col-label-class','');
$deadt= $params->get('till-tag','span');
$deadcls= $params->get('deadline-class','');
$deadcolcls = $params->get('col-till-class','');

$datesListCls= $params->get('dates-list-class','');
$datesListElCls= $params->get('dates-listelement-class','');

$timezone = Factory::getUser()->getTimezone();

if($items):
?>

    <div class="<?php echo htmlspecialchars($params->get('module-class',''));?>">
        <div class="<?php echo htmlspecialchars($params->get('module-inner-class',''));?>">
            <?php if(strlen($params->get('text-before',''))):?>
                <div class="nx-description-text">
                    <?php echo HTMLHelper::_('content.prepare', $params->get('text-before'));?>
                </div>
            <?php endif; ?>
            <table class="<?php echo $tblClasses;?>">
                <thead>
                <tr>
                    <th class="nx-tbl-header header-for-label"><?php echo htmlspecialchars($params->get('label-label',''));?></th>
                    <th class="nx-tbl-header header-for-deadline"><?php echo htmlspecialchars($params->get('deadline-label',''));?></th>
                    <th class="nx-tbl-header header-for-dates"><?php echo htmlspecialchars($params->get('dates-label',''));?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item):
                    $deadlinetime = new Date($item->deadline);
                    $deadlinetime->setTimezone($timezone);
                    $deadline = $deadlinetime->format(Text::_(htmlspecialchars($params->get('format-deadline','d. F Y'))));
                    ?>

                    <tr>
                        <?php echo '<td class="'.$lblcolcls.'"><'.$lblt.'>'.$item->label.'</'.$lblt.'></td>';?>
                        <?php echo '<td class="'.$deadcolcls.'"><'.$deadt.' class="'.$deadcls.'">'.$deadline.'</'.$deadt.'></td>';?>
                        <td>
                            <?php
                            if(count($item->exams)) {
                                echo '<ul class="'.$datesListCls.'">';
                                foreach ($item->exams as $exam) {

                                    $date = new Date($exam['date']);
                                    $date->setTimezone($timezone);
                                    $user_date = $date->format(Text::_(htmlspecialchars($params->get('format-exam-date','d.m.Y H:i'))));

                                    switch($params->get('show-time','always')){
                                        case 'always':
                                            $dateHtml = $user_date;
                                            break;
                                        case 'never':
                                            $date = explode(' ', $user_date);
                                            $dateHtml = $date[0];
                                            break;
                                        case 'dedicated':
                                        default:
                                            $date = explode(' ', $user_date);
                                            if($date[1] !== '00:00'){
                                                $dateHtml = $user_date;
                                            }else{
                                                $dateHtml = $date[0];
                                            }
                                    }

                                    echo '<li class="'.$datesListElCls.'">' . $dateHtml . '</li>';
                                };
                                echo '</ul>';
                            }
                            if(strlen($item->examdatesnotice)){
                                echo HTMLHelper::_('content.prepare',$item->examdatesnotice);
                            }
                            ?>

                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>


<?php
endif;

// module debug
//echo '<pre>' . var_export($items,1) . '</pre>';
?>