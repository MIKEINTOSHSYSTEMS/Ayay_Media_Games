<?php
  namespace iberezansky\fb3d;

  function get_questions() {
    $qs = [];
    $push = function($id, $html='', $js='') use(&$qs) {
      array_push($qs, ['id'=> $id, 'html'=> $html, 'js'=> $js]);
    };



    return $qs;
  }

?>
