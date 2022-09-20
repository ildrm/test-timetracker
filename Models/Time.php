<?php
namespace Models;

include_once "Classes/Entity.php";
include_once "Classes/Database.php";
use Classes\Entity;
use Classes\Database;

/**
 * Time Model
 */
class Time extends Entity {
    private $time;
    function __construct()
    {
        $this->db = (new Database())->connet();
        parent::__construct('times');
        $this->time = new Entity('times');
    }

    /**
     * List the time records
     * @param array $filter
     * @return array
     */
    function list_time($filters)
    {
        $list = $this->time->list($filters);
        foreach ($list as $key => $rec) {
            $end = new \DateTime($rec['end_time']);
            $start = new \DateTime($rec['start_time']);
            $duration = $end->diff($start);
            $list[$key]['duration']=$duration->format('%h')." Hours ".$duration->format('%i')." Minutes";
        }
        return $list;
    }

    /**
     * Add a project
     * @param array $data
     * @return array
     */
    function add_time($data)
    {
        $time_id = parent::add($data);
        return $this->time->getBy(array(
            'id'=>$time_id
        ));
    }

    /**
     * Update a time
     * @param integer $id
     * @param array $data
     * @return array
     */
    function update_time($id, $data)
    {
        parent::update($id, $data);
        return $this->time->getBy(array(
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
        $project = new Entity('projects');
        $projects = $project->list(array(
            'user_id'=>$array[0][1]
        ));
        $project_arr = array();
        foreach ($projects as $item) {
            $project_arr[$item['id']]=$item['name'];
        }

        $query = 'INSERT INTO `times` (`project_id`, `user_id`, `start_time`, `end_time`, `status`) VALUES ';
        foreach ($array as $row) {
            $start_time = date('Y-m-d H:i:s',strtotime($row[2]));
            $end_time = date('Y-m-d H:i:s',strtotime($row[3]));
            $query .= "('".array_search($row[0],$project_arr)."','".$row[1]."','".$start_time."','".$end_time."', 1),";
        }
        $query = substr($query,0,strlen($query)-1);

        $this->db->query($query);
    }

    /**
     * Get peaks of a day
     */
    function get_peak($date, $projec_id) {
        $query = '';
        for($i=0; $i<25; $i++) {
            $query .= "SELECT start_time, end_time, user_id, project_id, $i t FROM times
            WHERE (DATE(start_time) = '$date' OR DATE(end_time) = '$date')
            AND ($i BETWEEN HOUR(start_time) AND HOUR(end_time))
            AND project_id = '$projec_id' UNION ";
        }
        $query = substr($query,0,strlen($query)-strlen(" UNION "));

        $q1 = $this->db->query('SELECT tbl.t `hour`, COUNT(1) records FROM ('.$query.') tbl GROUP BY t ORDER BY records DESC LIMIT 10');
        $result1 = $q1->fetchAll(\PDO::FETCH_ASSOC);

        $q2 = $this->db->query('SELECT tbl.user_id `user_id`, COUNT(1) records FROM ('.$query.') tbl GROUP BY `user_id` ORDER BY records DESC LIMIT 10');
        $result2 = $q2->fetchAll(\PDO::FETCH_ASSOC);

        return array('peaks'=>$result1, 'users'=>$result2);
    }
}