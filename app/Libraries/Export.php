<?php

namespace App\Libraries;

/**
 * 1. create_file() create blank csv.
 * 2. set_header() write header data you set in header property.
 * 3. write_csv() accept array(), write the body, you can use as you want, 
 *    can use with loop or after loop bulk data. can call multiple times 
 *    as per your data
 * 4. set_footer() -- optional (work as same as header method)
 */

class Export
{
    public $filename = "Export.csv";
    public $save_path = FCPATH . "Exports";
    public $header = array();
    public $footer = array();
    private $fullname = '';


    /**
     * This method is used to create the csv file
     */

    public function create_file()
    {
        $this->fullname = $this->save_path . '/' . $this->filename;
        if (!file_exists($this->fullname)) {
            if (!file_exists($this->save_path)) {
                mkdir($this->save_path, 0777, true);
            }
            $permission = "find " . $this->save_path . " -type d -exec chmod 0777 {} +";
            exec($permission);

            file_put_contents($this->fullname, array());
        }

        return $this;
    }


    /**
     * Set header method
     * optional method
     */
    public function set_header()
    {
        $this->write_csv([$this->header], "a");
        return $this;
    }


    /**
     * Set footer data method
     * optional method
     */
    public function set_footer()
    {
        $this->write_csv([$this->footer], "a");
        return $this;
    }

    public function write_csv($data, $mode = "a")
    {
        $this->fullname = $this->save_path . '/' . $this->filename;

        if (!file_exists($this->fullname)) {
            throw new \Exception($this->fullname . " Not exist", 1);
        }

        // write result
        $file = fopen($this->fullname, $mode);

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        // Close the file
        fclose($file);
        return $this;
    }
}
