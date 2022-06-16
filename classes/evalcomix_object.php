<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

class block_evalcomix_object {
    public $table;

    /**
     * The PK.
     * @var int $id
     */
    public $id;


    /**
     * Records this object in the Database, sets its id to the returned value, and returns that value.
     * If successful this function also fetches the new object data from database and stores it
     * in object properties.
     * @return int PK ID if successful, false otherwise
     */
    public function insert() {
        global $DB;

        if (!empty($this->id)) {
            debugging("Tool object already exists!");
            return false;
        }

        $data = $this->get_record_data();

        $this->id = $DB->insert_record($this->table, $data);

        // Set all object properties from real db data.
        $this->update_from_db();

        $this->notify_changed(false);
        return $this->id;
    }

    /**
     * Updates this object in the Database, based on its object variables. ID must be set.
     * @param string $source from where was the object updated (mod/forum, manual, etc.)
     * @return boolean success
     */
    public function update() {
        global $DB;

        if (empty($this->id)) {
            debugging('Can not update tool object, no id!');
            return false;
        }

        $data = $this->get_record_data();

        $DB->update_record($this->table, $data);

        $this->notify_changed(false);
        return true;
    }

    /**
     * Deletes this object from the database.
     * @param string $source from where was the object deleted
     * @return boolean success
     */
    public function delete() {
        global $DB;

        if (empty($this->id)) {
            debugging('Can not delete grade object, no id!');
            return false;
        }

        $data = $this->get_record_data();

        if ($DB->delete_records($this->table, array('id' => $this->id))) {
            $this->notify_changed(true);
            return true;

        } else {
            return false;
        }
    }

    /**
     * Using this object's id field, fetches the matching record in the DB, and looks at
     * each variable in turn. If the DB has different data, the db's data is used to update
     * the object. This is different from the update() function, which acts on the DB record
     * based on the object.
     */
    public function update_from_db() {
        if (empty($this->id)) {
            debugging("The object could not be used in its state to retrieve a matching
            record from the DB, because its id field is not set.");
            return false;
        }
        global $DB;
        if (!$params = $DB->get_record($this->table, array('id' => $this->id))) {
            debugging("Object with this id:{$this->id} does not exist in table:{$this->table}, can not update from db!");
            return false;
        }

        self::set_properties($this, $params);

        return true;
    }

    /**
     * Given an associated array or object, cycles through each key/variable
     * and assigns the value to the corresponding variable in this object.
     * @static final
     */
    public static function set_properties(&$instance, $params) {
        $params = (array) $params;
        foreach ($params as $var => $value) {
            if (in_array($var, $instance->requiredfields) or array_key_exists($var, $instance->optionalfields)) {
                $instance->$var = $value;
            }
        }
    }

    /**
     * Returns object with fields and values that are defined in database
     */
    public function get_record_data() {
        $data = new stdClass();

        foreach ($this as $var => $value) {
            if (in_array($var, $this->requiredfields) or array_key_exists($var, $this->optionalfields)) {
                if (is_object($value) or is_array($value)) {
                    debugging("Incorrect property '$var' found when inserting grade object");
                } else {
                    $data->$var = $value;
                }
            }
        }
        return $data;
    }

    /**
     * Factory method - uses the parameters to retrieve matching instance from the DB.
     * @static final protected
     * @return mixed object instance or false if not found
     */
    protected static function fetch_helper($table, $classname, $params) {
        if ($instances = self::fetch_all_helper($table, $classname, $params)) {
            if (count($instances) > 1) {
                // We should not tolerate any errors here - problems might appear later.
                print_error('morethanonerecordinfetch', 'debug');
            }
            return reset($instances);
        } else {
            return false;
        }
    }

    /**
     * Factory method - uses the parameters to retrieve all matching instances from the DB.
     * @static final protected
     * @return mixed array of object instances or false if not found
     */
    public static function fetch_all_helper($table, $classname, $params) {

        $instance = new $classname();

        $classvars = (array)$instance;
        $params    = (array)$params;

        $wheresql = array();
        $newparams = array();

        foreach ($params as $var => $value) {
            if (!in_array($var, $instance->requiredfields) and !array_key_exists($var, $instance->optionalfields)) {
                continue;
            }
            if (is_null($value)) {
                $wheresql[] = " $var IS NULL ";
            } else {
                $wheresql[] = " $var = ? ";
                $newparams[] = $value;
            }
        }

        if (empty($wheresql)) {
            $wheresql = '';
        } else {
            $wheresql = implode("AND", $wheresql);
        }

        global $DB;

        $rs = $DB->get_recordset_select($table, $wheresql, $newparams);
        // Returning false rather than empty array if nothing found.
        if (!$rs->valid()) {
            $rs->close();
            return false;
        }

        $result = array();
        foreach ($rs as $data) {
            $instance = new $classname();
            self::set_properties($instance, $data);
            $result[$instance->id] = $instance;
        }
        $rs->close();

        return $result;
    }
}
