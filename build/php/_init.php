<?php

function initSeats($initSeatCount) {

  $json = (object)[
    'seatsCap'           => $initSeatCount,
    'seatsEmpty'         => $initSeatCount,
    'groupEmptyPosition' => [0],
    'groups'             => [],
  ];

  $nullGroup = (object)[
    "groupSize"  => $initSeatCount,
    "groupColor" => '#ffffff',
    "occupied"   => FALSE,
  ];

  $json->groups = [$nullGroup];


  file_put_contents('database.json', json_encode($json));

  echo json_encode($json->groups);
}

function update() {
  $json = json_decode(file_get_contents('database.json'));
  echo json_encode($json->groups);
}