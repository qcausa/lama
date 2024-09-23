<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * The Import/Export Manager functionality of the admin
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/ImportExport
 */

class ImportExport extends Base {


  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }


  /**
   * [validate_api_key Validateyour Google API Key]
   * @return [type] [description]
   */
  public function validate_api_key() {

    global $wpdb;

    $response = new \stdclass();
    $response->success = false;

    //Get the API KEY
    $sql      = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'server_key'";
    $configs_result = $wpdb->get_results($sql);

    if(isset($configs_result) && isset($configs_result[0])) {

      $api_key    = $configs_result[0]->value;

      if($api_key) {

        //Test Address
        $street   = '1848 Provincial Road';
        $city     = 'Winsdor';
        $state    = 'ON';
        $zip      = 'N8W 5W3';
        $country  = 'Canada';

        $_address = $street.', '.$zip.'  '.$city.' '.$state.' '.$country;

        $results = \AgileStoreLocator\Helper::getLnt($_address, $api_key, true);

        $response->result = $results;

        if($results && isset($results['body'])) {

          $results  = json_decode($results['body'], true);
          
          if(isset($results['error_message'])) {

            $response->msg    = $results['error_message'];
          }
          else {

            $response->msg     = esc_attr__('Valid API Key','asl_locator'); 
            $response->success = true;  
          }
        }

        //$response->msg    = esc_attr__('API Key is Valid','asl_locator');
        
      }
      else
        $response->msg = esc_attr__('Server Google API Key is Missing','asl_locator');
    }
    else
        $response->msg = esc_attr__('Server Google API Key is not saved.','asl_locator');

    return $this->send_response($response);
  }

  /**
   * [fill_missing_coords Fetch the Missing Coordinates]
   * @return [type] [description]
   */
  public function fill_missing_coords() {
  
    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', 0);
    
    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;
    $response->summary = array();

    //Get the API Key
    $sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'server_key'";
    $configs_result = $wpdb->get_results($sql);
    $api_key    = $configs_result[0]->value;

    if($api_key) {

      //Get the Stores
      $stores = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."stores WHERE (lat = '' OR lng = '') OR (lat = '0.0' OR lng = '0.0') OR (lat IS NULL OR lng IS NULL) OR !(lat BETWEEN -90.10 AND 90.10) OR !(lng BETWEEN -180.10 AND 180.10) OR !(lat REGEXP '^[+-]?[0-9]*([0-9]\\.|[0-9]|\\.[0-9])[0-9]*(e[+-]?[0-9]+)?$') OR !(lng REGEXP '^[+-]?[0-9]*([0-9]\\.|[0-9]|\\.[0-9])[0-9]*(e[+-]?[0-9]+)?$')");

      foreach($stores as $store) {

        $coordinates = \AgileStoreLocator\Helper::getCoordinates($store->street, $store->city, $store->state, $store->postal_code, $store->country, $api_key);

        if($coordinates) {

          if($wpdb->update( ASL_PREFIX.'stores', array('lat' => $coordinates['lat'], 'lng' => $coordinates['lng']),array('id'=> $store->id )))
          {
            $response->summary[] = 'Store ID: '.$store->id.", LAT/LNG Fetch Success, Address: ".implode(', ', array($store->street, $store->city, $store->state, $store->postal_code));
          }
          else
            $response->summary[] = '<span class="red">Store ID: '.$store->id.", LAT/LNG Error Save, Address: ".implode(', ', array($store->street, $store->city, $store->state, $store->postal_code)).'</span>';

        }
        else
          $response->summary[] = '<span class="red">Store ID: '.$store->id.", LAT/LNG Fetch Failed, Address: ".implode(', ', array($store->street, $store->city, $store->state, $store->postal_code)).'</span>';
        
      }

      if(!$stores || count($stores) == 0) {

        $response->summary[] = esc_attr__('Missing Coordinates are not Found in Store Listing','asl_locator');
      }

      $response->msg      = esc_attr__('Missing Coordinates Request Completed','asl_locator');
      $response->success  = true;
    }
    else
      $response->msg    = esc_attr__('Google Server API Key is Missing.','asl_locator');

  
    return $this->send_response($response);
  }

  /**
   * [delete_import_file Delete the Import file]
   * @return [type] [description]
   */
  public function delete_import_file() {

    $file_name  = sanitize_text_field($_REQUEST['data_']);
    $response   = \AgileStoreLocator\Helper::removeFile($file_name, ASL_PLUGIN_PATH.'public/import/');

    return $this->send_response($response);
  }


  /**
   * [upload_store_import_file Upload Store Import File]
   * @return [type] [description]
   */
  public function upload_store_import_file() {

    $response = new \stdclass();
    $response->success = false;

    $target_dir  = ASL_PLUGIN_PATH."public/import/";
    $date        = new \DateTime();

    $target_name = $target_dir . strtolower($_FILES["files"]["name"]);
    //$namefile    = substr(strtolower($_FILES["files"]["name"]), 0, strpos(strtolower($_FILES["files"]["name"]), '.'));
    
    $imageFileType  = pathinfo($target_name,PATHINFO_EXTENSION);

    //  real file name
    $real_name      = preg_replace('/[^a-zA-Z0-9]/','-', pathinfo($_FILES['files']['name'], PATHINFO_FILENAME));
    
    //  file target path
    $target_name    = $target_dir.sanitize_file_name($real_name.'_'.uniqid().'.'.$imageFileType);

    //  If file not found
    if (file_exists($target_name)) {
        $response->msg = esc_attr__("Sorry, file already exists.",'asl_locator');
    }
    //  Not a valid format
    else if($imageFileType != 'csv') {
        $response->msg = esc_attr__("Sorry, only CSV files are allowed.",'asl_locator');
    }
    //  Upload 
    else if(move_uploaded_file($_FILES["files"]["tmp_name"], $target_name)) {

          $response->msg = esc_attr__("The file has been uploaded.",'asl_locator');
          $response->success = true;
    }
    //error
    else {

      $response->msg = esc_attr__('Some error occured','asl_locator');
    }

    return $this->send_response($response);
  }


  /**
   * [import_store Import the Stores of CSV/EXCEL]
   * @return [type] [description]
   */
  public function import_store($_file_to_import = null, $cron_job = false) {

    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', 0);
    
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', '1');
    
    global $wpdb;
    
    //$_REQUEST['data_']      = 'demo-import.csv';
    //$_REQUEST['duplicates'] = 'lat_lng';

    
    $response  = new \stdclass();
    $response->success = false;

    //  The file which will be imported
    $import_file        = ($_file_to_import)? $_file_to_import: sanitize_text_field($_REQUEST['data_']);
    $avoid_dupl_column  = (isset($_REQUEST['duplicates']) && $_REQUEST['duplicates'])?sanitize_text_field($_REQUEST['duplicates']): null;
    $duplicate_count    = 0;
    $wrong_coords_count = 0;

    //  Validate the dupl for limited columns
    if(!in_array($avoid_dupl_column, ['title', 'phone', 'email', 'lat_lng'])) {
      $avoid_dupl_column = null;
    }



    $countries     = $wpdb->get_results("SELECT id,country FROM ".ASL_PREFIX."countries");
    $all_countries = array();

    foreach($countries as $_country) {
      $all_countries[$_country->country] = $_country->id;
    }


    if(!get_option('asl-compatible')) {

      $response->summary        = array('Please provide your purchase code to proceed through purchase dialog or contact us at support@agilelogix.com');
      $response->imported_rows  = 0;
      $response->success        = true;
    
      return $this->send_response($response);  
    }

    //  16-JUNE-2023
    //$wpdb->query("SET NAMES utf8");

    //  ddl controls
    $ddl_controls = \AgileStoreLocator\Model\Attribute::get_controls();
    

    // Get the API KEY
    $api_key = null;

    $sql     = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'server_key'";
    $configs_result = $wpdb->get_results($sql);
    
    if($configs_result && isset($configs_result[0]))
      $api_key = $configs_result[0]->value;
    
    $response->summary = array();

    //  Input File Name
    $inputFileName  = (($cron_job)? (ASL_UPLOAD_DIR.'cron/'):ASL_PLUGIN_PATH.'public/import/').$import_file;

    //  Don't let it go when fil is missing
    if(!file_exists($inputFileName)) {
      $response->error = 'Import Error! File is missing';
      return $this->send_response($response);
    }


    //  import column headers
    $header_columns = ['title', 'description', 'street', 'city', 'state', 'postal_code', 'country', 'lat', 'lng', 'phone', 'fax', 'email', 'website', 'is_disabled', 'logo', 'categories', 'marker_id', 'description_2', 'open_hours', 'order'];

    // ddl fields
    $ddl_fields     = array_column($ddl_controls, 'field');

    //  Include the ddl columns
    $header_columns = array_merge($header_columns, $ddl_fields);

    
    $rows = null;

    try {
      
      $csv = new \AgileStoreLocator\Admin\CSV\Reader();

      $csv->getData($inputFileName);

      // Allow plugins to parse the CSV, ticket #6933
      $csv = apply_filters( 'asl_parse_csv', $csv);

      //  Make it associative array, and skip first row
      $csv->fillKeys($header_columns);

      //  Get the Rows
      $rows = $csv->getRows();
      //echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);die;
    }
    catch (\Exception $e) {
      
      $response->not_working = true;
      $response->success     = false;
      $response->summary     = [$e->getMessage()];
       
      //  When not a Cronjob import request
      if($_file_to_import) {
        return $this->send_response($response);
      }
    }

    //  Get the Custom Fields
    $fields    = $this->_get_custom_fields();

    $index          = 2;
    $imported       = 0;
    $store_deleted  = 0;



    //  Default language
    //$default_language = get_locale();

    // 
    do_action( 'asl_before_stores_import', $inputFileName );
    
    //  Let user change it via functions.php
    $update_key = apply_filters('asl_csv_update_key', 'id');

    $update_key = $this->clean_input($update_key);


    //  Either it will be update_id or some other default field
    $update_field = ($update_key == 'id')? 'update_id': $update_key; 
    
    foreach($rows as $t) {
      
      $logoid        = '0';
      $categoryerror = '';

      //  lang field
      $lang  = (isset($t['lang']) && $t['lang'])? $t['lang']: '';

      if($lang == 'en' || $lang == 'en_US' || strlen($lang) >= 13) {
        $lang = '';
      }
      

      //  Either Zip or the postal_code
      if(isset($t['zip'])) {

        $t['postal_code'] = $t['zip'];
      }


      //  Check if the Logo Name already exist, just use it
      if(isset($t['logo_name']) && trim($t['logo_name']) != '') {

        $t['logo_name'] = trim($t['logo_name']);
        
        $logoresult = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ASL_PREFIX."storelogos WHERE `name` = %s", $t['logo_name']));
        
        if(count($logoresult) != 0) {

          $logoid = $logoresult[0]->id;
        }
      }

      //  When Logo is missing and we have a Logo URL, Fetch it
      if($logoid == '0' && isset($t['logo_image']) && !empty(trim($t['logo_image'])) && filter_var(filter_var($t['logo_image'], FILTER_SANITIZE_URL), FILTER_VALIDATE_URL) !== false) {

        $target_dir  = ASL_UPLOAD_DIR."Logo/";

        //  Must have a logo name
        if(empty($t['logo_name'])) {
          $t['logo_name'] = sanitize_file_name($t['title']).'-'.$index;
        }

        $extension = explode('.', $t['logo_image']);
        $extension = $extension[count($extension) - 1];

        if(in_array($extension, ['jpg', 'png', 'gif', 'svg', 'jpeg'])) {

          $logo_url  = str_replace(' ', '%20', $t['logo_image']);
          $file_name = uniqid().'.'.$extension;
          $file_path = $target_dir.$file_name;
   
          file_put_contents($file_path, file_get_contents($logo_url));  

          $t['logo_image'] = $file_name;
        }
      }
      

     
      ///////////////////////////////////
      /// CREATE CATEGORY IF NOT FOUND //
      ///////////////////////////////////
      $store_categories = [];

      if($t['categories'] != '') {
        
        $categorys = explode("|", $t['categories']);
        
        foreach ($categorys as $_cat) {
          $hierarchical_categories = explode(">", $_cat);
    
          $category = [];

          for ($i=0; $i < count($hierarchical_categories); $i++) {
            $_h_cat = trim($hierarchical_categories[$i]);

            if(!$_h_cat || $i > 1)
              continue;
            
            try {
                
              // If Parent
              if ($i == 0) {
                $parent_id = 0;
                // If Child
              } else {
                $parent_id = $category[0]->id;
                $parent_store_category_key = array_search($parent_id, $store_categories);

                if ($parent_store_category_key !== false) unset($store_categories[$parent_store_category_key]);
              }

              $category[$i] = \AgileStoreLocator\Model\Category::get_category_by_name($_h_cat, $parent_id, $lang);

              // If not category already exist
              if (!$category[$i]) {
                $data_insert = array(
                  'category_name' => $this->clean_input($_h_cat),     
                  'is_active'     => 1,
                  'icon'          => 'default.png',
                  'lang'          => $lang,
                  'parent_id'     => $parent_id,
                );

                $insert_id = \AgileStoreLocator\Model\Category::add_category($data_insert); 
                $response->summary[] = 'Row: '.$index.', Category created: '.$_h_cat;
                
                if ($i == 0) {
                  $category[0] = (object)[];
                  $category[0]->id = $insert_id;
                }
                $store_categories[] = $insert_id;
              } else {
                $store_categories[] = $category[$i]->id;
              }


            }
            catch (\Exception $e) {
                
                $response->summary[] = 'Error: '.$index.', Category error: '.$_h_cat.', Message:'.$e->getMessage();
            }


          }
        }
      }


      ///////////////////////
      //  Delete the store //
      ///////////////////////
      if(isset($t['delete_id']) && is_numeric($t['delete_id'])) {

        //  When a store is deleted!
        if(\AgileStoreLocator\Model\Store::delete_store($t['delete_id'])) {

          $store_deleted++;
        }
        else {

          $response->summary[] = 'Error: Row '.$index.', Store not found to delete, Store ID: '.$t['delete_id'];
        }
        continue;
      }


      //  When we have a title
      if($t['title'] != '') {

        //  DDL data to save
        $ddl_data = [];

        ////////////////////////////////
        ///// CREATE Custom Dropdowns //
        ////////////////////////////////
        
        foreach($ddl_controls as $control_key => $ddl_control) {

          $attr_values = \AgileStoreLocator\Model\Attribute::get_id_with_insert($control_key, $t[$ddl_control['field']], $lang);
          
          //  add in the store variable
          $ddl_data[$ddl_control['field']]   = $attr_values[0];

          //  Save the summary
          if($attr_values[1]) {

            $response->summary[] = 'Row: '.$index.', '.$ddl_control['label'].' created: '.$attr_values[1];
          }
        }




        //  Is an Update operation or Add?
        $is_update        = (isset($t[$update_field]) && $t[$update_field])? true: false;

        //  Value of the update field to match
        $update_field_val = ($is_update)? $this->clean_input($t[$update_field]): null;

        
        ///////////////////////////
        // Check for duplication //
        ///////////////////////////
        if (!$is_update && $avoid_dupl_column) {

          //  variables for the duplicates validation
          $dupl_sql; $dupl_param_1; $dupl_param_2;

          //  For the coordinates
          if($avoid_dupl_column == 'lat_lng') {

            $dupl_sql     = "SELECT COUNT('name') as 'count' FROM ".ASL_PREFIX."stores WHERE lat = %s AND lng = %s;";

            $dupl_param_1 = esc_sql($t['lat']);
            $dupl_param_2 = esc_sql($t['lng']);
          }
          //  For rest of the columns
          else {

            $dupl_sql = "SELECT COUNT('name') as 'count' FROM ".ASL_PREFIX."stores WHERE `%1s` = %s;";

            $dupl_param_1 = $avoid_dupl_column;
            $dupl_param_2 = sanitize_text_field($t[$avoid_dupl_column]);
          }

          //  Get count
          $dupl_results = $wpdb->get_results($wpdb->prepare($dupl_sql, $dupl_param_1, $dupl_param_2));

          //  check if the duplicate exist?
          if($dupl_results && $dupl_results[0]->count  >= 1) {

            $duplicate_count++;
            continue;
          }          
        }


        //Check if Lat/Lng is missing and we have address
        if(!trim($t['lat']) || !trim($t['lng'])) {
          
          // API key should be available
          if($api_key) {

            $coordinates = \AgileStoreLocator\Helper::getCoordinates($t['street'],$t['city'],$t['state'],$t['postal_code'],$t['country'], $api_key);
          }
          
          if($coordinates) {

            $t['lat'] = $coordinates['lat'];
            $t['lng'] = $coordinates['lng'];
          }
          else
            $response->summary[] = 'Row: '.$index.", LAT/LNG Fetch Failed";
        }
        //  Validate the coordinates
        else if(!\AgileStoreLocator\Helper::validate_coordinate($t['lat'], $t['lng'])) {

          $wrong_coords_count++; 
        }
        
        
        $store_id  = null;

        ///////////////
        //Open Hours //
        ///////////////
        $hours_n_days = explode('|', $t['open_hours']);
        $days         = array('mon' => '0','tue'=> '0','wed'=> '0','thu'=> '0','fri'=> '0','sat'=> '0','sun'=> '0');

        foreach($hours_n_days as $_day) {

          $day_hours = explode('=', $_day);

          //is Valid Day
          if(isset($days[$day_hours[0]]) && isset($day_hours[1])) {

            $day_      = $day_hours[0];
            $dhours    = $day_hours[1];


            if($dhours === '1') {

              $days[$day_] = '1';
            }
            else if($dhours === '0') {

              $days[$day_] = '0';
            }
            //For Hours of every day
            else {

              $durations = explode(',', $dhours);

              if(count($durations) > 0) {

                //make it array
                $days[$day_] = array();

                foreach($durations as $hours) {

                  $timings = explode('-', $hours);

                  if(count($timings) == 2)
                    $days[$day_][] = trim($timings[0]).' - '.trim($timings[1]);
                }
              }
            } 
          }
        }

        $days = json_encode($days);



        //////////////////////////////
        //Compile the Custom Fields //
        //////////////////////////////
        $custom_field_data = [];
        
        foreach ($fields as $field => $f_value) {
          
          if(isset($t[$field])) {

            $custom_field_data[$field] =  $this->clean_input($t[$field]);
          }
        }



        //  Validating the DATA
        $marker_id   = (isset($t['marker_id']) && is_numeric($t['marker_id']))? $t['marker_id']: '1';
        $is_disabled = (isset($t['is_disabled']) && $t['is_disabled'] == '1')? '1': '0';
        $order_id    = (isset($t['order']) && is_numeric($t['order']))? $t['order']: '0';

        $phone       = substr(trim($t['phone']), 0, 50);
        $fax         = substr(trim($t['fax']), 0, 50);
        $email       = substr(trim($t['email']), 0, 100);
        $postal_code = substr(trim($t['postal_code']), 0, 100);
        

        ////////////////////////
        //Prepare Store Array //
        ////////////////////////
        $store_row = array(
            'title'       => $this->clean_input($t['title']),
            'description' => $this->clean_input_html($t['description']),
            'street'      => $this->clean_input($t['street']),
            'city'        => $this->clean_input(trim($t['city'])),
            'state'       => $this->clean_input(trim($t['state'])),
            'postal_code' => $this->clean_input($postal_code),
            'country'     => (isset($all_countries[$t['country']]))? $all_countries[$t['country']]:'223', //for united states
            'lat'         => $t['lat'],
            'lng'         => $t['lng'],
            'phone'       => $this->clean_input($phone),
            'fax'         => $this->clean_input($fax),
            'email'       => $this->clean_input($email),
            'website'     => $this->fixURL($this->clean_input($t['website'])),
            'is_disabled' => $is_disabled,
            'logo_id'     => $logoid,
            'marker_id'   => $marker_id,
            'open_hours'  => $days,
            'description_2' => $this->clean_input_html($t['description_2']),
            'ordr'        => $order_id,
            'custom'      => json_encode($custom_field_data)
          );

        //  final data to save
        $store_row = array_merge($store_row, $ddl_data);

        /////////////////////////////////////////////////////////
        // Check if the record exist, else it will be a insert //
        /////////////////////////////////////////////////////////
        if($is_update) {
          
          if(defined( 'ASL_INSERT_ON_UPDATE' )) {

            // Execute the SQL query and get the count
            $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . ASL_PREFIX . "stores WHERE $update_key = %s;", $update_field_val));

            //  If not found? make it an insertion operation
            if(!$count) {
              $is_update = false;
            }
          }
        }


        //// Validate if It's Insert or Update by Columns //////
        if($is_update) {

          if($wpdb->update( ASL_PREFIX.'stores', $store_row, array($update_key => $update_field_val ))) {
            $imported++;
          }
        }
        ////  Insertion
        else {

          // Prevent duplication of slug (update function)
          $slug  = \AgileStoreLocator\Schema\Slug::slugify($t, $custom_field_data);

          //  Add lang parameter
          $store_row['lang'] = $lang;

          //  Add the slug
          $store_row['slug'] = $slug;


          if($wpdb->insert( ASL_PREFIX.'stores', $store_row)) {
            $imported++;
          }
          //  Error
          else {
            $has_error            = true;
            $wpdb->show_errors    = true;
            $response->summary[]  = 'Row: '.$index.', Error: '.$wpdb->print_error();
          }
        }

        /////////////////
        //  Get the ID //
        /////////////////

        //  When it is update operation, we will get the 'update_id'
        if($is_update) {

          //  Simply the 'update_id' is the value
          if($update_key == 'id' && is_numeric($update_field_val)) {
            $store_id = $update_field_val;
          }
          //  We need to fetch the `id`
          else {

            $store_id = $wpdb->get_var( $wpdb->prepare("SELECT id FROM ".ASL_PREFIX."stores WHERE $update_key = %s LIMIT 1", $update_field_val) );
          }
        }
        else {
            // Otherwise, use the last insert ID from the recent insert operation
            $store_id = $wpdb->insert_id;
        }


        ///////////////////
        //Store Schedule //
        ///////////////////

        // Check Schedule store feature is enable  or not
        $store_schedule   = \AgileStoreLocator\Helper::get_configs('store_schedule');

        if ($store_schedule && $store_schedule == '1'){

          //  For the Schedule start date
          if( !empty($t['start_date']) || !empty($t['end_date']) ) {
            
            // Get the  data
            $start_date = $this->clean_input($t['start_date']);
            $end_date   = $this->clean_input($t['end_date']);

            // Set data into store meta table
            \AgileStoreLocator\Helper::set_option_alter($store_id, 's_date', $start_date, 0);
            \AgileStoreLocator\Helper::set_option_alter($store_id, 'e_date', $end_date, 0);
          }

        }
        
        ///////////////////////
        //ADD THE CATEGORIES //
        ///////////////////////
        if($store_id && count($store_categories)) {
          
          //  If is Update? Delete Prev Categories
          if($is_update) {
            $wpdb->query("DELETE FROM ".ASL_PREFIX."stores_categories WHERE store_id = ".$store_id);            
          }

          foreach ($store_categories as $categoryid) {
            
              $wpdb->insert(ASL_PREFIX.'stores_categories', 
              array('store_id' => $store_id,'category_id' =>  $categoryid),
              array('%d','%d'));
          }
        }
     

        //If No Logo is found and have image create a new Logo
        if($logoid == '0') {

          //check if logo image is provided and that exist in folder
          if(isset($t['logo_image']) && !empty(trim($t['logo_image']))) {

            $t['logo_name']    = trim($t['logo_name']);
            $target_file = $t['logo_image'];
            $target_name = $t['logo_name'];

            $wpdb->insert(ASL_PREFIX.'storelogos', 
                  array('path'=>$target_file,'name'=>$target_name),
                  array('%s','%s'));

            $logo_id = $wpdb->insert_id;

            //update the logo id to store table
            $wpdb->update(ASL_PREFIX."stores",
              array('logo_id' => $logo_id),
              array('id' => $store_id)
            );

            $response->summary[] = 'Row: '.$index.", logo created ".$t['logo_name'];
          }
          //else $response->summary[] = 'Row: '.$index.", logo ".$t['logo_name']." not found";
        }

        
        //  Add the Branches to the Stores
        if($t['branches']) {

          \AgileStoreLocator\Model\Store::assignBranches($store_id, $t['branches']);
        }
      }

      $index++;
    }
    // END OF THE LOOP

    $response->success     = true;
    

    //  Add duplicate count in the summary
    if($duplicate_count) {

      $response->error     = esc_attr__('Duplicate rows skipped: ','asl_locator').$duplicate_count;
      $response->summary[] = $response->error;
      $response->success   = false;
    }

    //  Rows Deleted
    if($store_deleted) {

      $response->stores_deleted = $store_deleted;
    }


    //  Wrong coordinates count
    if($wrong_coords_count) {

      $response->success   = false;
      $response->error    .= esc_attr__('Error! Wrong coordinates, invalid stores: ','asl_locator').$wrong_coords_count;
    }

    
    $response->imported_rows = $imported;

    //  It is done via the cronjob return the response
    if($_file_to_import) {
      return $response;
    }
    
    do_action( 'asl_after_stores_import' );

    return $this->send_response($response);  
  }

  /**
   * [export_store export Excel fo Stores]
   * @return [type] [description]
   */
  public function export_store($as_array = false) {

    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 0);
    
    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    //  With Store Id for Update?
    $with_update_id  = (isset($_REQUEST['with_id']) && $_REQUEST['with_id'] == '1')? true: false;
    $with_logo_image = (isset($_REQUEST['logo_image']) && $_REQUEST['logo_image'] == '1')? true: false;

    // Check Schedule store feature is enable  or not
    $store_schedule   = \AgileStoreLocator\Helper::get_configs('store_schedule');
    $stores = null;
    

    try {

      //  Stores Data
      $stores = $wpdb->get_results("SELECT `s`.*,  `c`.`country` FROM ".ASL_PREFIX."stores s LEFT JOIN ".ASL_PREFIX."countries c ON s.country = c.id ORDER BY s.`id`");
  
    }
    catch (\Exception $e) {
        
      echo $e->getMessage();die;
    }   



    //  CSV Instance
    $csv = new \AgileStoreLocator\Admin\CSV\Reader();
    
    //  Get the dropdown controls  
    $ddl_controls    = \AgileStoreLocator\Model\Attribute::get_controls();
      
    $ddl_fields = array_column($ddl_controls, 'field');

    
    //  Header titles
    $headers = ['title', 'description', 'street', 'city', 'state', 'postal_code', 'country', 'lat', 'lng', 'phone', 'fax', 'email', 'website', 'is_disabled', 'logo', 'categories', 'marker_id', 'description_2', 'open_hours', 'order', 'lang'];

    //  Include the ddl columns
    $headers = array_merge($headers, $ddl_fields);
    
    //  With Update ID?
    if($with_update_id) {
      
      $headers[] = 'update_id';
    }

    // If Schedule store feature is enable 
    if ($store_schedule && $store_schedule == '1'){

        $headers[] = 'start_date';
        $headers[] = 'end_date';

    }
    
    //  Rows to be exported
    $all_rows = [];

    //  Get the Custom Field Schema
    $fields   = $this->_get_custom_fields();



    ///////////////////////
    //  Get the DDL LIST //
    ///////////////////////
    
    $ddl_values_list = [];

    //  loop over the ddl controls
    foreach($ddl_controls as $control_key => $ddl_control) {
    
      $ddl_values_list[$ddl_control['field']] = \AgileStoreLocator\Model\Attribute::get_all_by_id($control_key); 
    }


    //  All the categories
    $categories = \AgileStoreLocator\Model\Category::categories_child_array();


    //  Loop over the stores data
    foreach ($stores as $value) {

      $logo_name  = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ASL_PREFIX."storelogos WHERE id = %d", $value->logo_id) ); 


      //  Get the Categories of that Store
      $store_categories = $wpdb->get_results($wpdb->prepare("SELECT `category_id` FROM ".ASL_PREFIX."stores_categories WHERE store_id = %d", $value->id) );

      //  Convert Categories into String with | JOIN
      $_cats = [];
      
      foreach($store_categories as $c) {

        //  Add the category via ID
        if(isset($categories[$c->category_id])) {
          $_cats[] = $categories[$c->category_id];
        }
      }

      $_cats = implode('|', $_cats);


      /// Add the dropdowns values
      foreach($ddl_controls as $control_key => $ddl_control) {
        
        //  ddl field name
        $field_name = $ddl_control['field'];

        //  Convert the values
        if($value->$field_name) {

          $store_ddl = explode(',', $value->$field_name);

          $store_ddl_list = [];
          
          foreach($store_ddl as $ddl_opt_value) {

            if(isset($ddl_values_list[$ddl_control['field']][$ddl_opt_value])) {

              $store_ddl_list[] =  $ddl_values_list[$ddl_control['field']][$ddl_opt_value]->name;
            }
          }

          $value->$field_name = implode('|', $store_ddl_list);
        }
      }
      
      //  Make Open Hours Importable String
      $open_hours_value = '';
      
      if($value->open_hours) {

        $open_hours = json_decode($value->open_hours);

        if(is_object($open_hours)) {

          $open_details = array();
          foreach($open_hours as $key => $_day) {


            $key_value = '';

            if($_day && is_array($_day)) {

              $key_value = implode(',', $_day);
            }
            else if($_day == '1') {

              $key_value = $_day;
            }
            else  {

              $key_value = '0';
            }

            $open_details[] = $key.'='.$key_value;
          }

          $open_hours_value = implode('|', $open_details);
        }
      }


      // If Schedule store feature is enable 
      // if ($store_schedule && $store_schedule == '1'){

      //     $value->start_date = (isset($start_date)) ? $start_date :'';
      //     $value->end_date   = (isset($end_date)) ? $end_date : '';
      
      // }

      //  Logo
      $value->logo_name  = (isset($logo_name) && isset($logo_name[0]))?$logo_name[0]->name:'';

      //  Logo image
      if($with_logo_image)
        $value->logo_image  = ($logo_name && isset($logo_name[0]))? ASL_UPLOAD_URL.'Logo/'.$logo_name[0]->path: '';

      
      //  Categories
      $value->categories = $_cats;

      $value->order      = $value->ordr;
      unset($value->ordr);

      //  Open hours
      $value->open_hours = $open_hours_value;

      //  With Update Id?
      if($with_update_id) {
        
        $value->update_id = $value->id;
      }

      // If Schedule store feature is enable 
      if ($store_schedule && $store_schedule == '1'){

          // Get value from store meta
          $start_date  =  \AgileStoreLocator\Helper::get_option($value->id, 's_date');
          $end_date    = \AgileStoreLocator\Helper::get_option($value->id, 'e_date');

          // Set value into row
          $value->start_date = (isset($start_date)) ? $start_date :'';
          $value->end_date   = (isset($end_date)) ? $end_date : '';

      }


      // Custom Values
      if(isset($value->custom) && $value->custom) {

        $custom_fields_data     = json_decode($value->custom, true);
        

        foreach ($fields as $field => $f_value) {
          
          $value->$field = (isset($custom_fields_data[$field]))? $custom_fields_data[$field]: '';
        }
      }

      unset($value->id);
      unset($value->created_on);
      unset($value->logo_id);
      unset($value->custom);
      
      //  Push into rows collection
      $all_rows[] = $value;
    }

    
    try {

      //  To filterout the data fields
      $all_rows = apply_filters( 'asl_export_csv_data', $all_rows);

      //  Return as array
      if($as_array) {
        return $all_rows;
      }

      $csv->setRows($all_rows);
      
      $download_file = 'public/export/stores-data-export-'.time().'.csv';
      $path_to_save  = ASL_PLUGIN_PATH.$download_file;
      $csv_file_name = apply_filters( 'asl_export_csv_file_name', 'stores-data-export.csv');

      $csv->write(\AgileStoreLocator\Admin\CSV\Reader::DOWNLOAD, $csv_file_name);
    }
    catch (\Exception $e) {
        
      echo $e->getMessage();die;
    }

    
    //$csv->write(\AgileStoreLocator\Admin\CSV\Reader::FILE, $path_to_save);
    //$response->success  = true;
    //$response->msg      = ASL_URL_PATH.$download_file;
    die;
  }
}