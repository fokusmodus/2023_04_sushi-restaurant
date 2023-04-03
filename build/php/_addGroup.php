<?php

function addGroup($input) {

  $json = json_decode(file_get_contents('database.json'));

  $emptySeatsAvailable = $input->groupSize <= $json->seatsEmpty;


  if (!$emptySeatsAvailable) {
    echo json_encode(['fail' => 'not enough seats']);
    return FALSE;
  }

  // Helper variables

  $perfectGap       = FALSE;
  $biggerGap        = FALSE;
  $nearlyPerfectGap = (object)[
    'position'      => FALSE,
    'positionEmpty' => FALSE,
    'groupSize'     => $json->seatsCap,
    'difference'    => $json->seatsCap,
  ];


  foreach ($json->groupEmptyPosition as $key => $groupPosition) {
    $perfectGap = $input->groupSize === $json->groups[$groupPosition]->groupSize;
    $biggerGap  = $input->groupSize < $json->groups[$groupPosition]->groupSize;


    if ($perfectGap) {

      // if there is a perfect matching gap, we use the first occurring one

      unset($json->groupEmptyPosition[$key]);
      $json->groupEmptyPosition = array_values($json->groupEmptyPosition);
      sort($json->groupEmptyPosition);


      $addGroup         = (object)[
        "groupSize"  => $input->groupSize,
        "groupColor" => randColor(),
        "occupied"   => TRUE,
      ];
      $json->seatsEmpty -= $input->groupSize;

      $json->groups[$groupPosition] = $addGroup;

      file_put_contents('database.json', json_encode($json));
      echo json_encode($json->groups);
      $perfectGap = TRUE;
      return TRUE;
      //break;
    }

    if ($biggerGap) {

      // - if there is a bigger gap, we might use this one
      // - we search for a bigger gap, that is almost as small as the inputted group size
      // - by that we keep the biggest gaps free as long as possible


      $dif = $json->groups[$groupPosition]->groupSize - $input->groupSize;


      if ($nearlyPerfectGap->difference > $dif) {
        $nearlyPerfectGap->position      = $groupPosition;
        $nearlyPerfectGap->positionEmpty = $key;
        $nearlyPerfectGap->groupSize     = $json->groups[$groupPosition]->groupSize;
        $nearlyPerfectGap->difference    = $json->groups[$groupPosition]->groupSize - $input->groupSize;
      }
    }
  }

  if ($nearlyPerfectGap->position !== FALSE) {

    // - a perfect matching gap size wasn't found
    // - at this point we should have found a bigger gap than the input group size to use
    // - out of all bigger gaps, it's the smallest one to don't waste seats

    $json->seatsEmpty -= $input->groupSize;
    $addGroup         = (object)[
      "groupSize"  => $input->groupSize,
      "groupColor" => randColor(),
      "occupied"   => TRUE,
    ];


    $json->groups[$nearlyPerfectGap->position] = $addGroup;

    $dif       = $nearlyPerfectGap->groupSize - $input->groupSize;
    $nullGroup = (object)[
      "groupSize"  => $dif,
      "groupColor" => '#ffffff',
      "occupied"   => FALSE,
    ];


    array_splice($json->groups, $nearlyPerfectGap->position + 1, 0, 'object');
    $json->groups[$nearlyPerfectGap->position + 1] = $nullGroup;

    $json = reSortEmptyPositions($json);

    file_put_contents('database.json', json_encode($json));
    echo json_encode($json->groups);
    return TRUE;
  }


  if (!$perfectGap && !$biggerGap) {

    echo json_encode(['fail' => 'not enough seats']);
    return FALSE;
  }

  return NULL;
}