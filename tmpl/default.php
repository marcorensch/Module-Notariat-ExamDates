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

$deadline_format = $params->get('format-deadline','DATE_FORMAT_LC3');
$exam_date_format = $params->get('format-exam-date','DATE_FORMAT_LC3');

$timezone = Factory::getUser()->getTimezone();

$specialChars = array('ä','ö','ü','Ä','Ö','Ü');

if($items):
?>

    <div class="<?php echo htmlspecialchars($params->get('module-class',''));?>">
        <div class="<?php echo htmlspecialchars($params->get('module-inner-class',''));?>">
            <div class="uk-text-lead uk-margin nx-description-text">
                <?php echo Text::_('MOD_EXAMDATES_EXAM_TXT_BEFORE');?>
            </div>
            <table class="<?php echo $tblClasses;?>">
                <thead>
                <tr>
                    <th class="nx-tbl-header header-for-label"><?php echo Text::_('MOD_EXAMDATES_EXAM_LABEL');?></th>
                    <th class="nx-tbl-header header-for-deadline"><?php echo Text::_('MOD_EXAMDATES_REG_DEADLINE');?></th>
                    <th class="nx-tbl-header header-for-dates"><?php echo Text::_('MOD_EXAMDATES_EXAM_DATES');?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item):
                    $deadlinetime = new Date($item->deadline);
                    $deadlinetime->setTimezone($timezone);
                    //$deadline = $deadlinetime->format(Text::_(htmlspecialchars($params->get('format-deadline','d. F Y'))));
                    $deadline = HtmlHelper::date($deadlinetime, Text::_($deadline_format));
                    ?>

                    <tr>
                        <?php
                        $label = '';
                        $labelArr = explode(' ', $item->label);
                        $i=0;
                        foreach ($labelArr as $part){
                            $original = $part;
                            $partUpper = strtoupper($part);
                            $key = str_replace($specialChars, '', $partUpper);
                            $value = Text::_($key);
                            if($key !== $value){
                                $label .= $value;
                            }else{
                                $label .= $original;
                            }
                            if($i < count($labelArr)){ $label .= ' '; }
                            $i++;
                        }
                        ?>
                        <?php echo '<td class="'.$lblcolcls.'"><'.$lblt.'>'.$label.'</'.$lblt.'></td>';?>
                        <?php echo '<td class="'.$deadcolcls.'"><'.$deadt.' class="'.$deadcls.'">'.$deadline.'</'.$deadt.'></td>';?>
                        <td>
                            <?php
                            if(count($item->exams)) {
                                echo '<ul class="'.$datesListCls.'">';
                                foreach ($item->exams as $exam) {

                                    $date = new Date($exam['date']);
                                    $date->setTimezone($timezone);
                                    //$user_date = $date->format(Text::_(htmlspecialchars($params->get('format-exam-date','d.m.Y H:i'))));
                                    $exam_date = HtmlHelper::date($date, Text::_($exam_date_format));

                                    /* Old Way
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
                                    */

                                    echo '<li class="'.$datesListElCls.'">' . $exam_date . '</li>';
                                };
                                echo '</ul>';
                            }
                            if(strlen($item->examdatesnotice)){
                                $originalTxt = $item->examdatesnotice;
                                if(strpos($originalTxt,'_')){
                                    $possible = str_replace('_','',$originalTxt);
                                    if(ctype_upper($possible)){
                                        $text = Text::_($originalTxt);
                                    }else{
                                        $text = $originalTxt;
                                    }
                                }else{
                                        $text = $originalTxt;
                                }
                                echo HTMLHelper::_('content.prepare',$text);
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
