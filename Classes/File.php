<?php
namespace Classes;

/**
 * Class File
 */
class File {
    /**
     * Format bytes
     * @param integer $bytes
     * @param integer $precision
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 

        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }

    /**
     * Upload a file
     * @param string $param
     * @param string $path_folder
     * @param string $file_name
     * @param boolean $return_content
     * @param integer $skip_rows
     * @return array|false|string[]
     */
    public static function upload($param, $path_folder, $file_name=null, $return_content=false, $skip_rows=1) {
        $file_name = $file_name ?? substr(uniqid('', true), -5).'.csv';
        $uploadfile = $path_folder . (microtime(true)*1000) . $file_name;
        $file_size = $_FILES[$param]['size'];
        if (move_uploaded_file($_FILES[$param]['tmp_name'], $uploadfile)) {
            if ($return_content) {
                $csvFile = file($uploadfile);
                $data = [];
                foreach ($csvFile as $line) {
                    $data[] = str_getcsv($line);
                }
                for ($i=0;$i<$skip_rows;$i++) {
                    unset($data[$i]);
                }
                return array('rows'=>$data, 'size'=>self::formatBytes($file_size));
            } else {
                return array('size'=>self::formatBytes($file_size));
            }
        } else {
            return false;
        }
    }
}