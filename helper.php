<?php

/**
 * @package     mod_db_data_changer
 *
 * @copyright   Copyright (C) 2019 - 2020 nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Date\Date;

class ModExamDatesHelper{
    public static function getExams($params){

        $dir = $params->get('ordering-dir','ASC');
        $date = new Date();
        try{
            // Get a db connection.
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from($db->quoteName('#__notarmanager_exam_day'));
            $query->where($db->quoteName('published') . ' = 1 ');
            $query->order('ordering '.$dir);

            $db->setQuery($query);

            $results = $db->loadObjectList();

            // echo '<pre>' . var_export($date->toSql(),1) . '</pre>';

            if($results){
                foreach($results as $k => $result){
                    if($result->publish_up !== '0000-00-00 00:00:00' && $result->publish_up > $date->toSql()){
                        unset($results[$k]);
                        continue;
                    }
                    //echo '<pre>' . var_export($result->publish_down,1) . '</pre>';
                    if($result->publish_down !== '0000-00-00 00:00:00' && $result->publish_down < $date->toSql()){
                        unset($results[$k]);
                        continue;
                    }
                    $result->exams = json_decode($result->examdates,1);
                }
            }
            return $results;
        }catch (Exception $e){

            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error'); // commonly to still display that error

        }
    }
}