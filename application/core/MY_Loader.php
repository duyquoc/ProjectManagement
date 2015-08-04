<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    /**
     * Load the Utilities Class
     *
     * @return	string
     */
    public function dbutil()
    {
        if ( ! class_exists('CI_DB'))
        {
            $this->database();
        }

        $CI =& get_instance();

        // for backwards compatibility, load dbforge so we can extend dbutils off it
        // this use is deprecated and strongly discouraged
        $CI->load->dbforge();

        require_once(BASEPATH.'database/DB_utility.php');

        // START custom >>

        // path of default db utility file
        $default_utility = BASEPATH . 'database/drivers/' . $CI->db->dbdriver . '/' . $CI->db->dbdriver . '_utility.php';

        // path of my custom db utility file
        $my_utility = APPPATH . 'libraries/MY_DB_' . $CI->db->dbdriver . '_utility.php';

        // set custom db utility file if it exists
        if (file_exists($my_utility))
        {
            $utility = $my_utility;
            $extend = 'MY_DB_';
        }
        else
        {
            $utility = $default_utility;
            $extend = 'CI_DB_';
        }

        // load db utility file
        require_once($utility);

        // set the class
        $class = $extend . $CI->db->dbdriver . '_utility';

        // << END custom

        $CI->dbutil = new $class();
    }

}