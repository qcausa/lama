<?php

namespace AgileStoreLocator\Admin;


if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}


use AgileStoreLocator\Admin\Base;


/**
 * The settings manager including UI, templates, cache etc functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Setting
 */

class Setting extends Base
{


  /**
   * [__construct description]
   */
  public function __construct()
  {

    parent::__construct();
  }


  /**
   * [backup_template Backup the Template into theme Root Directory]
   * @return [type] [description]
   */
  public function backup_template()
  {

    $template  = isset($_REQUEST['template']) ? sanitize_text_field($_REQUEST['template']) : null;
    $response  = \AgileStoreLocator\Helper::backup_template($template);

    return $this->send_response($response);
  }


  /**
   * [remove_template Remove the template file from the Theme Directory]
   * @return [type] [description]
   */
  public function remove_template()
  {

    $template  = isset($_REQUEST['template']) ? sanitize_text_field($_REQUEST['template']) : null;
    $response  = \AgileStoreLocator\Helper::remove_template($template);

    return $this->send_response($response);
  }


  /**
   * [expertise_level description]
   * @return [type] [description]
   */
  public function expertise_level()
  {

    $level_status = (isset($_REQUEST['status']) && $_REQUEST['status'] == '1') ? '1' : '0';

    //  Update the expertise level
    update_option('asl-expertise', $level_status);

    return $this->send_response(['success' => true, 'msg' => esc_attr__("Level has been changed.", 'asl_locator')]);
  }

  /**
   * [change_options Save the Settings in the Settings table]
   */
  public function change_options($json_return = false)
  {

    global $wpdb;
    $prefix = ASL_PREFIX;

    // Data
    $content = isset($_POST['content']) ? stripslashes_deep($_POST['content']) : null;
    $type    = isset($_POST['stype']) ? stripslashes_deep($_POST['stype']) : null;

    //  Response
    $response  = new \stdclass();
    $response->success = false;

    //  When type is hidden
    if (in_array($type, ['hidden', 'cache'])) {

      $c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}settings WHERE `type` = '{$type}'");

      $data_params = array('content' => json_encode($content), 'type' => $type);


      if ($c[0]->count  >= 1) {
        $wpdb->update($prefix . "settings", $data_params, array('type' => $type));
      } else {
        $wpdb->insert($prefix . "settings", $data_params);
      }

      $response->msg     = esc_attr__("Settings has been updated.", 'asl_locator');
      $response->success = true;
    }

    //  return as JSON
    if ($json_return) {
      return $response;
    }


    return $this->send_response($response);
  }




  /**
   * [save_setting save ASL Setting]
   * @return [type] [description]
   */
  public function save_setting()
  {

    global $wpdb;

    $response  = new \stdclass();

    //  Settings data
    $data_     = stripslashes_deep($_POST['data']);

    //  Remove Script tag will be saved in wp_options
    $remove_script_tag = $data_['remove_maps_script'];
    unset($data_['remove_maps_script']);


    //  Config keys
    $keys     =  array_keys($data_);

    // Hava a value?
    if (isset($data_['country_restrict']) && $data_['country_restrict']) {

      // Restrict the country validation
      $validation_result = $this->validate_country_restrictions($data_['country_restrict']);
      
      if (!$validation_result['valid']) {
        
        $response->msg = esc_attr__("Invalid value for country restriction. Only ISO 3166-1 alpha-2 country codes are supported.", 'asl_locator');
        return $this->send_response($response);
      }

      $data_['country_restrict'] = $validation_result['country_restrict'];
    }

    //  Loop over the setting items
    foreach ($keys as $key) {

      $wpdb->update(
        ASL_PREFIX . "configs",
        array('value' => $data_[$key]),
        array('key' => $key)
      );
    }

    //  register/de-register the lead cron job for the follow-ups
    if (isset($data_['lead_follow_up']))
      \AgileStoreLocator\Cron\LeadCron::schedule_cron($data_['lead_follow_up'], $data_);



    //  register/de-register the schedule post jobs
    if (isset($data_['store_schedule'])) {

      $schedule_status = ($data_['store_schedule'] == '1') ? true : false;

      //  Either enable or disable the job
      \AgileStoreLocator\Admin\Schedule::schedule_stores_job($schedule_status);
    }

    ///////////////////////////
    //  Save Custom Settings //
    ///////////////////////////
    $custom_map_style = $_POST['map_style'];

    //  Custom Map Style
    \AgileStoreLocator\Helper::set_setting(stripslashes($custom_map_style), 'map_style', 'map_style');


    $custom_slug_fields = $_POST['slug_attr_ddl'];

    //  Slug Attributes
    \AgileStoreLocator\Helper::set_setting(stripslashes($custom_slug_fields), 'slug_attr_ddl');


    update_option('asl-remove_maps_script', $remove_script_tag);

    $response->msg     = esc_attr__("Setting has been updated successfully.", 'asl_locator');
    $response->success = true;


    //  Valid the Default Coordinates
    $is_valid  = \AgileStoreLocator\Helper::validate_coordinate($data_['default_lat'], $data_['default_lng']);

    //  is invalid?
    if (!$is_valid) {

      $response->msg     .= '<br>' . esc_attr__("Error! Default Lat & Lng are invalid values, please try to swap them.", 'asl_locator');
      $response->success  = false;
    }

    return $this->send_response($response);
  }


  /**
   * [validate_country_restrictions Validate the country restriction]
   * @param  [type] $country_restrict [description]
   * @return [type]                   [description]
   */
  private function validate_country_restrictions($country_restrict) {
      
      // List of valid ISO 3166-1 alpha-2 country codes
      $valid_countries = [
          'AF', 'AX', 'AL', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ',
          'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BQ', 'BA', 'BW', 'BV', 'BR',
          'IO', 'BN', 'BG', 'BF', 'BI', 'CV', 'KH', 'CM', 'CA', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC',
          'CO', 'KM', 'CG', 'CD', 'CK', 'CR', 'HR', 'CU', 'CW', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'EC',
          'EG', 'SV', 'GQ', 'ER', 'EE', 'SZ', 'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'GF', 'PF', 'TF', 'GA',
          'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT',
          'HM', 'VA', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IM', 'IL', 'IT', 'JM', 'JP',
          'JE', 'JO', 'KZ', 'KE', 'KI', 'KP', 'KR', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI',
          'LT', 'LU', 'MO', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'FM',
          'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'NC', 'NZ', 'NI', 'NE',
          'NG', 'NU', 'NF', 'MK', 'MP', 'NO', 'OM', 'PK', 'PW', 'PS', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN',
          'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'BL', 'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS',
          'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SX', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'SS',
          'ES', 'LK', 'SD', 'SR', 'SJ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TK', 'TO',
          'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'US', 'UM', 'UY', 'UZ', 'VU', 'VE',
          'VN', 'VG', 'VI', 'WF', 'EH', 'YE', 'ZM', 'ZW'
      ];

      $countries = explode(',', $country_restrict);
      $valid = true;
      $countries_to_restrict = [];

      foreach ($countries as $country) {
          $country = strtoupper(trim($country));
          $countries_to_restrict[] = $country;
          if (!in_array($country, $valid_countries)) {
              $valid = false;
              break;
          }
      }

      return [
          'valid' => $valid,
          'country_restrict' => implode(',', $countries_to_restrict)
      ];
  }


  /**
   * [manage_cache Refresh the JSON]
   * @return [type] [description]
   */
  public function manage_cache()
  {

    global $wpdb;

    $response = new \stdclass();
    $response->success = false;


    $cache_status = (isset($_REQUEST['status']) && $_REQUEST['status'] == '1') ? '1' : '0';
    $cache_lang   = (isset($_REQUEST['asl-lang'])) ? sanitize_text_field($_REQUEST['asl-lang']) : null;

    //  Todo, Make sure the folder exist?
    if (!file_exists(ASL_UPLOAD_DIR)) {
      mkdir(ASL_UPLOAD_DIR, 0775, true);
    }

    if (!$cache_lang) {
      $response->error = esc_attr__('Error! Lang is not defined.', 'asl_locator');;
    }

    //  en_US is default
    if ($cache_lang == 'en_US')
      $cache_lang = '';


    //  JSON file
    $json_file = 'locator-data' . (($cache_lang) ? '-' . $cache_lang : '') . '.json';

    //  Generate the JSON file when enabled
    if ($cache_status == '1') {

      //  Generate the Output
      $public_request = new \AgileStoreLocator\Frontend\Request();
      $output_result  = $public_request->load_stores(true, $cache_lang);

      //  Save the output
      $response->output   = file_put_contents(ASL_UPLOAD_DIR . $json_file, json_encode($output_result));

      //  When fails
      if (!$response->output) {
        $response->path   = ASL_UPLOAD_DIR . $json_file;
      }

      $response->msg      = esc_attr__('Cache JSON has been generated successfully for language ' . $cache_lang, 'asl_locator');
    } else
      $response->msg      = esc_attr__('Cache JSON is disabled for language ' . $cache_lang, 'asl_locator');


    //  Save the cache settings
    $this->change_options(true);

    //  Show as success
    $response->success  = true;

    return $this->send_response($response);
  }



  /**
   * [load_custom_template Load ASL Custom Template]
   * @return [type] [description]
   */
  public function load_custom_template()
  {

    global $wpdb;

    $response          = new \stdclass();
    $response->success = false;

    $data_ = stripslashes_deep($_POST);


    //  List template doesn't have any infobox
    if (in_array($data_['template'], ['template-list', 'template-list-2', 'store-grid']) && $data_['section'] == 'infobox') {

      $response->error = esc_attr__("Template has no infobox.", 'asl_locator');
      return $this->send_response($response);
    }

    $html  = '';

    if ($data_['template'] != 'cards-templates') {
      $count = $wpdb->get_results($wpdb->prepare("SELECT COUNT('name') as 'count' FROM " . ASL_PREFIX . "settings WHERE `name` = %s AND `type` = %s", $data_['template'], $data_['section']));

      if ($count[0]->count  >= 1) {

        //  Template Query 
        $results = $wpdb->get_results($wpdb->prepare("SELECT `content` FROM " . ASL_PREFIX . "settings WHERE `name` = %s AND `type` = %s", $data_['template'], $data_['section']), ARRAY_A);

        if ($results)
          $html = $results[0]['content'];
      } else {

        // include simple products HTML
        $view_file_path = ASL_PLUGIN_PATH . 'public/views/' . (($data_['template'] == 'advanced_marker') ? 'markers/' : '') . $data_['template'] . '-' . $data_['section'] . '.html';

        $html = file_get_contents($view_file_path);
      }
    } elseif ($data_['template'] == 'cards-templates') {

      if (file_exists(STYLESHEETPATH . '/' . $data_['section'] . '.php')) {
        $view_file_path = STYLESHEETPATH . '/' . $data_['section'] . '.php';
      } else {
        $view_file_path = ASL_PLUGIN_PATH . 'public/partials/' .  $data_['section'] . '.php';
      }

      $html = file_get_contents($view_file_path);
    }


    if (!empty($html)) {

      $response->html = $html;

      $response->msg     = esc_attr__("HTML added in TextEditor", 'asl_locator');
      $response->success = true;
    }


    return $this->send_response($response);
  }


  /**
   * [save_custom_template Load ASL Custom Template]
   * @return [type] [description]
   */
  public function save_custom_template()
  {

    global $wpdb;

    $response   = new \stdclass();
    $response->success = false;

    $data_ = stripslashes_deep($_POST);

    // $ext = substr($data_['template'], strrpos($data_['template'], '.') + 1);

    if (!empty($data_['html'])) {
      if ($data_['template'] != 'cards-templates') {
        //  get previous quantity
        $count = $wpdb->get_results($wpdb->prepare("SELECT COUNT('name') as 'count' FROM " . ASL_PREFIX . "settings WHERE `name` = %s AND `type` = %s", $data_['template'],  $data_['section']));


        $data_params = array('name' =>  $data_['template'], 'type' => $data_['section'], 'content' => $data_['html']);

        if ($count[0]->count  >= 1) {

          //  Execute the Update Query
          $wpdb->update(ASL_PREFIX . "settings", $data_params, array('name' => $data_['template'], 'type' => $data_['section']));
        } else {
          //  Execute the Insert Query
          $wpdb->insert(ASL_PREFIX . "settings", $data_params);
        }

        $response->msg     = esc_attr__("Template Updated", 'asl_locator');
        $response->success = true;
      } elseif ($data_['template'] == 'cards-templates') {
        $file_path = STYLESHEETPATH . '/' . $data_['section'] . '.php';

        file_put_contents($file_path, $data_['html']);

        $response->msg     = esc_attr__("Cards Template Updated", 'asl_locator');
        $response->success = true;
      }
    }




    return $this->send_response($response);
  }

  /**
   * [reset_custom_template Load ASL Custom Template]
   * @return [type] [description]
   */
  public function reset_custom_template()
  {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $data_ = stripslashes_deep($_POST);

    if ($data_['template'] != 'cards-templates') {
      $view_file_path = ASL_PLUGIN_PATH . 'public/views/' . (($data_['template'] == 'advanced_marker') ? 'markers/' : '') . $data_['template'] . '-' . $data_['section'] . '.html';
    } elseif ($data_['template'] == 'cards-templates') {
      $view_file_path = ASL_PLUGIN_PATH . 'public/partials/' . $data_['section'] . '.php';
    }

    // include simple products HTML
    $html = file_get_contents($view_file_path);

    $response->html    = $html;
    $response->msg     = esc_attr__("Default template is loaded", 'asl_locator');
    $response->success = true;


    return $this->send_response($response);
  }


  /**
   * [add_cards_shortcode_presets Save Shortcode Presets]
   * @return [type] [description]
   */
  public function cards_shortcode_presets()
  {

    $response          = new \stdclass();
    $response->success = false;
    $db_action         = $_POST['db_action'];
    $shortcode_preset  = $_POST['shortcode'];

    // Retrive Shortcodes from DB
    $cards_shortcode_presets = \AgileStoreLocator\Helper::get_setting('cards_shortcode_presets', 'cards_shortcode_presets');
    $cards_shortcode_presets = $cards_shortcode_presets ? maybe_unserialize($cards_shortcode_presets) : [];

    $target_key = array_search($shortcode_preset, $cards_shortcode_presets);
    $shortcode_exist = $target_key !== false ? true : false;

    switch ($db_action) {
      case 'add':
        $cards_shortcode_presets[] = $shortcode_preset;
        $succss_msg = "Shortcode Presets have been Successfully Saved!";
        break;


      case 'edit':
        if ($shortcode_exist) {
          $cards_shortcode_presets[$target_key] = $_POST['updated_shortcode'];
          $succss_msg = "Shortcode Presets have been Successfully Updated!";
        } else {
          $cards_shortcode_presets[] = $_POST['updated_shortcode'];
          $succss_msg = "Shortcode have not been found to edit!, added instead";
          // return $response;
        }
        break;


      case 'delete':
        if ($shortcode_exist) {
          unset($cards_shortcode_presets[$target_key]);
          $succss_msg = "Shortcode Presets have been Successfully Deleted!";
        } else {
          $succss_msg    = "Shortcode have not been found!";
          $response->msg = esc_attr__($succss_msg, 'asl_locator');
          return $response;
        }
        break;
    }

    // Remove Duplicate Items from Array
    $cards_shortcode_presets = array_unique($cards_shortcode_presets);
    // Reset Index Keys
    $cards_shortcode_presets = array_values($cards_shortcode_presets);

    $db_response = \AgileStoreLocator\Helper::set_setting(maybe_serialize($cards_shortcode_presets), 'cards_shortcode_presets', 'cards_shortcode_presets');

    if ($db_response) {
      $response->data    = $cards_shortcode_presets[$target_key];
      $response->success = true;
      $response->msg     = esc_attr__($succss_msg, 'asl_locator');
    }

    return $response;
  }

  /**
   * [load_ui_settings Load ASL Custom Template]
   * @return [type] [description]
   */
  public function load_ui_settings()
  {

    global $wpdb;

    $response          = new \stdclass();
    $response->success = false;

    $template = $_POST['template'];

    $colors   = array(
      'template-0'  => array(
        'primary'   => 'clr-primary',
        'header'    => '',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => '',
        'list-sub-title'  => '',
        'highlighted'     => ''
      ),
      'template-1' => array(
        'primary'   => 'clr-primary',
        'secondary' => '',
        'header'    => 'clr-copy',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => 'clr-copy',
        'list-sub-title'  => '',
        'highlighted'     => ''
      ),
      'template-2' => array(
        'primary'   => 'clr-primary',
        'header'    => 'clr-copy',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => 'clr-copy',
        'list-sub-title'  => '',
        'highlighted'     => '',
        'highlighted-list-color' => 'clr-copy'
      ),
      'template-3' => array(
        'primary'   => 'clr-primary',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'              => '',
        'list-sub-title'          => '',
        'highlighted'             => ''
      ),
      'template-4'  => array(
        'primary'   => 'clr-primary',
        'header'    => '',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => '',
        'list-sub-title'  => '',
        'highlighted'     => ''
      ),
      'template-list' => array(
        'primary'   => 'clr-primary',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'              => ''
      ),
      'template-list-2' => array(
        'primary'   => 'clr-primary',
        'card-background'   => '',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'        => ''
      ),
      'template-wc'  => array(
        'primary'        => 'clr-primary',
        'button-color'   => '',
        'button-background'   => 'clr-copy',
        'label-color'   => '',
        'control-color'   => '',
        'control-background'   => '',
        'control-border-color'   => '',
        'input-color'   => '',
        'input-background'   => ''
      ),
    );


    $white                  = '#FFFFFF';
    $black                  = '#000000';

    $tmpl_0_primary         = '#cb2800';
    $tmpl_0_title_color     = '#32373c';
    $tmpl_0_sub_title_color = '#6a6a6a';
    $tmpl_0_list_color      = '#555d66';
    $tmpl_0_header_bg       = '#F7F7F7';
    $tmpl_0_header_color    = '#32373c';
    $tmpl_0_highlighted     = '#F7F7F7';

    $tmpl_1_primary         = '#000000';
    $tmpl_1_secondary       = '#EF5A28';
    $tmpl_1_title_color     = '#32373c';
    $tmpl_1_sub_title_color = '#6a6a6a';
    $tmpl_1_list_color      = '#555d66';
    $tmpl_1_header_bg       = $tmpl_1_primary;
    $tmpl_1_highlighted     = '#F7F7F7';

    $tmpl_2_primary         = '#cb2800';
    $tmpl_2_secondary       = '#cb2800';
    $tmpl_2_title_color     = '#32373c';
    $tmpl_2_sub_title_color = '#6a6a6a';
    $tmpl_2_list_color      = '#555d66';
    $tmpl_2_header_bg       = '#F7F7F7';
    $tmpl_2_highlighted     = '#F7F7F7';

    $tmpl_3_primary         = '#cb2800';
    $tmpl_3_title_color     = '#32373c';
    $tmpl_3_sub_title_color = '#6a6a6a';
    $tmpl_3_list_color      = '#555d66';
    $tmpl_3_header_bg       = '#F7F7F7';
    $tmpl_3_highlighted     = '#F7F7F7';

    //  the default colors that will load with the customizer
    $default_colors = array(
      'template-0'  => array(
        'primary'   => $tmpl_0_primary,
        'header'    => $tmpl_0_header_bg,
        'header-color'  => $tmpl_0_header_color,
        'infobox-color' => $tmpl_0_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_0_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_0_primary,
        'color'   => $tmpl_0_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_0_title_color,
        'list-sub-title'  => $tmpl_0_sub_title_color,
        'highlighted'     => $tmpl_0_highlighted,
        'highlighted-list-color' => $tmpl_0_primary
      ),
      'template-1' => array(
        'primary'   => $tmpl_1_primary,
        'secondary' => $tmpl_1_secondary,
        'header'    => $tmpl_1_header_bg,
        'header-color'  => $tmpl_1_list_color,
        'infobox-color' => $tmpl_1_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_1_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_1_primary,
        'color'   => $tmpl_1_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_1_title_color,
        'list-sub-title'  => $tmpl_1_sub_title_color,
        'highlighted'     => $tmpl_1_highlighted,
        'highlighted-list-color' => $tmpl_1_primary
      ),
      'template-2' => array(
        'primary'   => $tmpl_2_primary,
        'header'    => $tmpl_2_header_bg,
        'header-color'  => $tmpl_2_list_color,
        'infobox-color' => $tmpl_2_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_2_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_2_primary,
        'color'   => $tmpl_2_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_2_title_color,
        'list-sub-title'  => $tmpl_2_sub_title_color,
        'highlighted'     => $tmpl_2_highlighted,
        'highlighted-list-color' => $tmpl_2_primary
      ),
      'template-3'  => array(
        'primary'   => $tmpl_3_primary,
        'header'    => $tmpl_3_header_bg,
        'header-color'  => $tmpl_3_primary,
        'infobox-color' => $tmpl_0_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_3_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_3_primary,
        'color'   => $tmpl_3_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_3_title_color,
        'list-sub-title'  => $tmpl_3_sub_title_color,
        'highlighted'     => $tmpl_3_highlighted,
        'highlighted-list-color' => $tmpl_3_primary
      ),
      'template-4'  => array(
        'primary'   => $tmpl_0_primary,
        'header'    => $tmpl_0_header_bg,
        'header-color'  => $tmpl_0_header_color,
        'infobox-color' => $tmpl_0_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_0_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_0_primary,
        'color'   => $tmpl_0_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_0_title_color,
        'list-sub-title'  => $tmpl_0_sub_title_color,
        'highlighted'     => $tmpl_0_highlighted,
        'highlighted-list-color' => $tmpl_0_primary
      ),
      'template-list'  => array(
        'primary'   => $tmpl_3_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_3_primary,
        'color'   => $tmpl_3_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_3_title_color,
        'highlighted'     => $tmpl_3_highlighted
      ),
      'template-list-2'  => array(
        'primary'   => $tmpl_3_primary,
        'card-background'  => '#f2f5f7',
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_3_primary,
        'color'   => $tmpl_3_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_3_title_color,
        'highlighted'     => $tmpl_3_highlighted
      ),
      'template-wc'  => array(
        'primary'   => $tmpl_0_primary,
        'button-color'   => $white,
        'button-background'   => $tmpl_0_primary,
        'label-color'   => '#010a10',
        'control-color'   => '#010a10',
        'control-background'   => $white,
        'control-border-color'   => '#dee2e6',
        'input-color'   => $black,
        'input-background'   => $white
      )
    );


    $default_fonts  = array(
      'template-0'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-1'  => array(
        'font-size'   => 13,
        'title-size'  => 18,
        'btn-size'  => 13
      ),
      'template-2'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-3'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-4'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-list'  => array(
        'font-size'   => 13,
        'title-size'  => 18,
        'list-title-size'  => 18,
        'btn-size'  => 13
      ),
      'template-list-2'  => array(
        'font-size'   => 13,
        'title-size'  => 18,
        'list-title-size'  => 18,
        'btn-size'  => 13
      ),
      'template-wc'  => array(
        'font-size'   => 13,
        'font-small-size'   => 10,
        'title-size'  => 16,
        'btn-size'  => 13
      )
    );

    $font_labels = [
      'font-size'   => 'Content Font',
      'title-size'  => 'Title Font',
      'list-title-size'  => 'List Title Font',
      'btn-size'    => 'Button Font'
    ];


    $html     = '';
    $fields   = '';


    //  Only get the array of active default color
    $default_colors  = $default_colors[$template];
    $default_fonts   = $default_fonts[$template];

    $fields_settings = \AgileStoreLocator\Helper::get_setting('ui-template', $template);

    if ($fields_settings) {

      $fields = json_decode($fields_settings);
    }

    //  Start Stream
    ob_start();

    // include ui customizer fields products HTML
    include ASL_PLUGIN_PATH . 'admin/partials/ui-customizer-fields.php';

    $html = ob_get_contents();

    //  Clean it
    ob_end_clean();

    $response->html     = $html;
    $response->msg      = esc_attr__("Template UI settings updated", 'asl_locator');
    $response->success  = true;

    return $this->send_response($response);
  }

  /**
   * [sl_theme_ui_save Save ASL UI Settings]
   * @return [type] [description]
   */
  public function sl_theme_ui_save()
  {

    global $wpdb;


    $response  = new \stdclass();
    $response->success = false;

    $data_    = stripslashes_deep($_POST['sl_formData']);
    $template = sanitize_text_field($_POST['sl_template']);

    $data     = json_encode($data_);

    \AgileStoreLocator\Helper::set_setting($data, 'ui-template', $template);

    $response->msg     = esc_attr__("Template updated", 'asl_locator');
    $response->success = true;

    return $this->send_response($response);
  }



  /**
   * [save_custom_fields Save Custom Fields AJAX]
   * @return [type] [description]
   */
  public function save_custom_fields()
  {

    global $wpdb;
    $prefix = ASL_PREFIX;

    $response  = new \stdclass();
    $response->success = false;

    $fields = isset($_POST['fields']) ? ($_POST['fields']) : [];

    //  Filter the JSON for XSS
    $filter_fields = [];

    foreach ($fields as $field_key => $field) {

      $field_key = strip_tags($field_key);

      $field['type']  = strip_tags(sanitize_text_field($field['type']));
      $field['name']  = strip_tags(sanitize_text_field($field['name']));
      $field['label'] = strip_tags(sanitize_text_field($field['label']));

      $filter_fields[$field_key] = $field;
    }

    $c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}settings WHERE `type` = 'fields'");

    $data_params = array('content' => json_encode($filter_fields), 'type' => 'fields');


    if ($c[0]->count  >= 1) {
      $wpdb->update($prefix . "settings", $data_params, array('type' => 'fields'));
    } else {
      $wpdb->insert($prefix . "settings", $data_params);
    }

    /*$wpdb->show_errors = true;
    $response->error = $wpdb->print_error();
    $response->error1 = $wpdb->last_error;*/



    $response->msg     = esc_attr__("Fields has been updated successfully.", 'asl_locator');
    $response->success = true;


    return $this->send_response($response);
  }


  /**
   * \AgileStoreLocator\Admin\Setting::reset_all_slugs();
   * [Reset all store slug AJAX]
   * @return [type] [description]
   */
  public function reset_all_slugs()
  {

    $response  = new \stdclass();

    $counter = \AgileStoreLocator\Schema\Slug::regenerate_all_slugs();

    $response->msg     = esc_attr__(" $counter Slugs has been updated.", 'asl_locator');
    $response->success = ($counter) ? true : false;

    return $this->send_response($response);
  }


  /**
   * [import_configs Import the Configs]
   * @return [type] [description]
   */
  public function import_configs()
  {

    $response          = new \stdclass();
    $response->title   = esc_attr__("Configuration Import", 'asl_locator');

    // Must be an administrator
    if (current_user_can('administrator')) {

      $jsonText          = isset($_POST['configs']) ? stripslashes_deep($_POST['configs']) : null;
      $import_results    = ($jsonText) ? (\AgileStoreLocator\Model\Config::import_configuration($jsonText)) : false;

      //  Has imported or not?
      if ($import_results > 0) {

        $response->message = esc_attr__("Config has been imported successfully", 'asl_locator');
        $response->success = true;
      } else {
        $response->message = esc_attr__("Failed to import configuration, contact support for help.", 'asl_locator');
      }
    } else {
      $response->message = esc_attr__('Administrator permissions are required.', 'asl_locator');
    }


    return $this->send_response($response);
  }


  /**
   * [export_configs Export the Configs]
   * @return [type] [description]
   */
  public function export_configs()
  {

    $response  = new \stdclass();

    $response->configs = \AgileStoreLocator\Model\Config::export_config();

    $response->message              = esc_attr__("Config exported successfully", 'asl_locator');
    $response->copy_message         = esc_attr__("Copied successfully", 'asl_locator');
    $response->export_text_content  = esc_attr__("Warning! Export includes all configuration including API keys, labels, customizations and maps related settings.", 'asl_locator');
    $response->success      = true;

    return $this->send_response($response);
  }
}
