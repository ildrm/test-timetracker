<?php
namespace Classes;

include_once "Database.php";
use Classes\Database;

/**
 * Class Entity
 * Basic structure of Users, Time records, and Projects
 */
class Entity {
    private $entity_name;   // entity usage (users/times)
    private $db;
    function __construct($entity_name)
    {
        $this->entity_name = $entity_name;
        $this->db = (new Database())->connet();
    }

    /**
     * List the records
     * @param array $filters
     * @return array|false
     */
    function list($filters=array(), $order_by='id DESC')
    {
        try {
            $where = '';
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    $where .= " AND `$key` = '$value'";
                }
            }
            $query = "SELECT * FROM `{$this->entity_name}` WHERE 1 $where ORDER BY $order_by";
            $q = $this->db->query($query);
            $result = $q->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Find and get a record
     * @param array $filters
     * @return array
     */
    function getBy($filters=null)
    {
        try {
            if (!empty($filters)) {
                $where = '';
                foreach ($filters as $key => $value) {
                    $where .= " AND `$key` = '$value'";
                }
                $query = "SELECT * FROM `{$this->entity_name}` WHERE 1 $where;";
                $q = $this->db->query($query);
                $result = $q->fetch(\PDO::FETCH_ASSOC);
                return $result;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Add a record
     * @param array $data
     * @return integer|false
     */
    function add($data)
    {
        try {
            $set = '';
            foreach ($data as $key=>$value) {
                if ($key=='password') {
                    $set .= "`$key`='".sha1($value)."', ";
                } elseif ($value != 'now()') {
                    $set .= "`$key`='$value', ";
                } else {
                    $set .= "`$key`=now(), ";
                }
            }
            $set = substr($set,0,strlen($set)-2);

            $query = "INSERT INTO `{$this->entity_name}` SET $set;";
            $this->db->query($query);
            if ($this->db->lastInsertId()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update a record
     * @param integer $id
     * @param array $data
     * @return bool
     */
    function update($id, $data)
    {
        try {
            $set = '';
            foreach ($data as $key=>$value) {
                if ($value != 'now()') {
                    $set .= "`$key`='$value', ";
                } else {
                    $set .= "`$key`=now(), ";
                }
            }
            $set = substr($set,0,strlen($set)-2);

            $query = "UPDATE `{$this->entity_name}` SET $set WHERE `id`='$id';";
            $this->db->query($query);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete a record
     * @param integer $id
     * @return bool
     */
    function delete($id)
    {
        try {
            $query = "DELETE FROM `{$this->entity_name}` WHERE `id`='$id';";
            $this->db->query($query);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}