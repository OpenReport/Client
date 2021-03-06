<?php
/**
 * OpenReport
 *
 * Copyright 2013, The Austin Conner Group
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 */



/**
 * Record Record Model
 */
class Record extends ActiveRecord\Model {
  static $table_name = 'records';
  static $belongs_to = array(array('form', 'class_name'=>'Form'));
  /**
   * Returns an object
   *
   *
   */
  public function get_meta(){
    return json_decode($this->read_attribute('meta'), true);
  }
  /**
   * Returns date for local time zone (when/where recorded)
   *
   * Note: all record dates are saved at GMT or UTC
   *
   */
  public function get_record_date(){

    $rDate = $this->read_attribute('record_date');
    $offset = (string)$this->read_attribute('record_time_offset');
    // Find the record's timezone
    $timezone = preg_replace('/[:]/', '', $offset) * 36;
    $timezone_name = timezone_name_from_abbr(null, $timezone, true);
    // TODO: some validation on $timezone_name
    $rDate->setTimeZone(new DateTimeZone($timezone_name));
    return $rDate;
  }

}
