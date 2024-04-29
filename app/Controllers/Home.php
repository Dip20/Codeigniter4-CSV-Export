<?php

namespace App\Controllers;

use App\Libraries\Export;

class Home extends BaseController
{
    public function index()
    {
        /**
         * create instance
         */
        $export = new Export();

        /**
         * set config
         */
        $export->filename = time() . '.csv';
        $export->save_path = getcwd() .  '/Exports/' . date('Ymd');

        $export->header = ['parent_id', 'expence_type', 'is_expence', 'taxability', 'type', 'item_id', 'hsn', 'uom', 'qty', 'rate', 'igst', 'igst_amt', 'cgst', 'cgst_amt', 'sgst', 'sgst_amt', 'total', 'item_disc', 'discount'];

        $export->footer = ['parent_id', 'expence_type', 'is_expence', 'taxability', 'type', 'item_id', 'hsn', 'uom', 'qty', 'rate', 'igst', 'igst_amt', 'cgst', 'cgst_amt', 'sgst', 'sgst_amt', 'total', 'item_disc', 'discount'];


        $db = db_connect();

        /**
         * Retrive data
         */
        $get_data = $db->table('sales_item_old')
            ->select('parent_id, expence_type, is_expence, taxability, type, item_id, hsn, uom, qty, rate, igst, igst_amt, cgst, cgst_amt, sgst, sgst_amt, total, item_disc, discount')
            ->where(['is_delete' => 0])
            ->get()->getResultArray();

        if (!empty($get_data)) {

            /**
             * 1. create_file() create blank csv.
             * 2. set_header() write header data you set in header property.
             * 3. write_csv() accept array(), write the body, you can use as you want, 
             *    can use with loop or after loop bulk data. can call multiple times 
             *    as per your data
             * 4. set_footer() -- optional (work as same as header method)
             */

            $export->create_file()->set_header()
                ->write_csv($get_data)
                ->write_csv($get_data)
                ->set_footer();
        }


        // download link example
        $filename = $export->save_path . '/' . $export->filename;

        if (file_exists($filename)) {
            echo '<a href="' . base_url('/Exports/' . date('Ymd') . '/' . $export->filename) . '" download>Download csv</a>';
        }
    }
}
