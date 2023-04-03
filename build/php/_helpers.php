<?php

function randColor() {
  $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
  return '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];
}

function reSortEmptyPositions($json){
  $json->groupEmptyPosition = [];
  foreach ($json->groups as $groupIndex => $group) {

    if (!$group->occupied) {
      array_push($json->groupEmptyPosition, $groupIndex);
    }
  }
  sort($json->groupEmptyPosition);
  return $json;
}

function addData($json, $data) {

  $addGroup         = (object)[
    "groupSize"  => $data->groupSize,
    "groupColor" => randColor(),
    "occupied"   => TRUE,
  ];
  $json->seatsEmpty -= $data->groupSize;

  return $data;
}