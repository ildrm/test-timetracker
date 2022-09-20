<?php
namespace Models;

include_once "Classes/Entity.php";
include_once "Classes/Database.php";
use Classes\Entity;
use Classes\Database;

/**
 * Project Model
 */
class Project extends Entity {
    private $db;
    private $project;
    function __construct()
    {
        $this->db = (new Database())->connet();
        parent::__construct('projects');
        $this->project = new Entity('projects');
    }

    /**
     * Format the time to HH:MM:SS
     * @param integer $seconds
     * @return string
     */
    public static function format_time($seconds) {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }

    /**
     * List the projects
     * @param array $filter
     * @return array
     */
    function list_project($filters, $order_by='id DESC', $group_by=null)
    {
        try {
            $where = '';
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    $key = str_replace('.','`.`',$key);
                    $where .= " AND `$key` = '$value'";
                }
            }
            $group_by = $group_by ? "GROUP BY $group_by" : '';
            /**
             * This query returns the Projects with time spent in seconds.
             */
            $query = "  SELECT p.*, TIMESTAMPDIFF(SECOND, start_time, end_time) duration FROM projects p
                        LEFT JOIN times t ON t.project_id = p.id WHERE 1 $where $group_by ORDER BY $order_by";
            $q = $this->db->query($query);
            $records = $q->fetchAll(\PDO::FETCH_ASSOC);
            $sum_duration = 0;
            foreach ($records as $key => $record) {
                $sum_duration += $record['duration'];
                if ($record['duration']==null) $records[$key]['duration'] = 0;
            }
            $records['total_time'] = self::format_time($sum_duration);
            return $records;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add a project
     * @param array $data
     * @return array
     */
    function add_project($data)
    {
        $project_id = parent::add($data);
        return $this->project->getBy(array(
            'id'=>$project_id
        ));
    }

    /**
     * Update a project
     * @param integer $id
     * @param array $data
     * @return array
     */
    function update_project($id, $data)
    {
        parent::update($id, $data);
        return $this->project->getBy(array(
            'id'=>$id
        ));
    }

    /**
     * Store the imported data
     * @param array $array
     * @return 
     */
    function store_data($array)
    {
        try {
            $query = 'INSERT INTO `projects` (`name`,`user_id`,`created`,`status`) VALUES ';
            foreach ($array as $row) {
                $query .= "('".$row[0]."','".$row[1]."',now(),1),";
            }
            $query = substr($query,0,strlen($query)-1);
    
            $res = $this->db->query($query);
            return $res ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }
}