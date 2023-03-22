<?php
  namespace iberezansky\fb3d;

  require_once('update-checker/load-v4p11.php');

  function update_checker_query_arg_filter($arg) {
    global $fb3d;
    $license = $fb3d['options']['license'];
    if($license['domain_name']==='') {
      $license['domain_name'] = str_replace('https://', '', home_url('','https'));
    }
    $arg['license_key'] = $license['license_key'];
    $arg['domain_name'] = $license['domain_name'];
    $arg['product_id'] = $license['product_id'];
    return $arg;
  }

  function init_update_checker() {
    $uc = \Puc_v4_Factory::buildUpdateChecker(
      UPDATES_URL.'check.php',
      MAIN,
      DIR_NAME
    );
    $uc->addQueryArgFilter('iberezansky\fb3d\update_checker_query_arg_filter');
  }

  add_action('init', '\iberezansky\fb3d\init_update_checker');

  function license_key_link($links) {
    global $fb3d;
    if($fb3d['options']['license']['license_key']==='') {
      array_push($links, '<a href="'.admin_url('edit.php?post_type='.POST_ID.'&page='.POST_ID.'-settings').'">'.__('License Key', POST_ID).'</a>');
    }
    return $links;
  }

  add_filter('plugin_action_links_'.plugin_basename(MAIN), 'iberezansky\fb3d\license_key_link');

?>
