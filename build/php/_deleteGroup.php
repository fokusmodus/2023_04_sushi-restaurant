<?php

function deleteGroup($input) {

  $json = json_decode(file_get_contents('database.json'));

  if (!$input->occupied) {
    file_put_contents('database.json', json_encode($json));
    echo json_encode($json->groups);
    return;
  }


  // replace an occupied group with an empty group
  $nullGroup                        = (object)[
    "groupSize"  => $input->groupSize,
    "groupColor" => '#ffffff',
    "occupied"   => FALSE,
  ];
  $json->seatsEmpty                 += $input->groupSize;
  $json->groups[$input->groupIndex] = $nullGroup;
  //array_push($json->groupEmptyPosition, $input->groupIndex);
  //sort($json->groupEmptyPosition);
  $json = reSortEmptyPositions($json);


  $circleGap = !$json->groups[0]->occupied && !$json->groups[array_key_last($json->groups)]->occupied;
  $oneGroup  = count($json->groups) === 1;
  if ($circleGap && !$oneGroup) {

    // - the table is a circle
    // - if the first and the last key of the group array have empty seats, they will do one empty group

    $seatSum    = 0;
    $unsetter = [];


    if (!$json->groups[0]->occupied) {
      $seatSum += $json->groups[0]->groupSize;
      array_push($unsetter, 0);
    }
    if (!$json->groups[1]->occupied) {
      $seatSum += $json->groups[1]->groupSize;
      array_push($unsetter, 1);
    }

    if (count($json->groups) >= 3 && !$json->groups[array_key_last($json->groups)]->occupied) {
      $seatSum += $json->groups[array_key_last($json->groups)]->groupSize;
      array_push($unsetter, array_key_last($json->groups));
    }
    if (count($json->groups) >= 4 && !$json->groups[array_key_last($json->groups) - 1]->occupied) {
      $seatSum += $json->groups[array_key_last($json->groups) - 1]->groupSize;
      array_push($unsetter, array_key_last($json->groups) - 1);
    }


    foreach ($unsetter as $item) {
      unset($json->groups[$item]);
    }


    $nullGroup = (object)[
      "groupSize"  => $seatSum,
      "groupColor" => '#ffffff',
      "occupied"   => FALSE,
    ];

    $json->groups[0] = $nullGroup;
    ksort($json->groups);



    $json->groups = array_values($json->groups);
    $json = reSortEmptyPositions($json);



    file_put_contents('database.json', json_encode($json));
    echo json_encode($json->groups);
    return TRUE;
  }


  // logic for empty gaps and its direct predecessors and successors

  $consecutiveGapAfter  = FALSE;
  $consecutiveGapBefore = FALSE;
  $emptyGroupKey        = array_search($input->groupIndex, $json->groupEmptyPosition);

  $loopSpaceAfter = $emptyGroupKey < count($json->groupEmptyPosition) - 1 && count($json->groupEmptyPosition) > 1;
  if ($loopSpaceAfter) {
    $consecutiveGapAfter = $json->groupEmptyPosition[$emptyGroupKey + 1] - $json->groupEmptyPosition[$emptyGroupKey] === 1;
  }
  $mergeAfter = $loopSpaceAfter && $consecutiveGapAfter;


  $loopSpaceBefore = $emptyGroupKey > 0 && count($json->groupEmptyPosition) > 1;
  if ($loopSpaceBefore) {
    $consecutiveGapBefore = $json->groupEmptyPosition[$emptyGroupKey] - $json->groupEmptyPosition[$emptyGroupKey - 1] === 1;
  }
  $mergeBefore = $loopSpaceBefore && $consecutiveGapBefore;


  if ($mergeAfter && !$mergeBefore) {

    // - empty gaps that have direct empty followers will be merged to create a bigger gap

    $emptyGroupAIndex = $json->groupEmptyPosition[$emptyGroupKey];
    $emptyGroupBIndex = $json->groupEmptyPosition[$emptyGroupKey + 1];

    $nullGroupSize = $json->groups[$emptyGroupAIndex]->groupSize + $json->groups[$emptyGroupBIndex]->groupSize;

    $nullGroup = (object)[
      "groupSize"  => $nullGroupSize,
      "groupColor" => '#ffffff',
      "occupied"   => FALSE,
    ];


    unset($json->groups[$emptyGroupBIndex]);
    $json->groups[$emptyGroupAIndex] = $nullGroup;
    $json->groups                    = array_values($json->groups);
  }

  if (!$mergeAfter && $mergeBefore) {

    // - empty gaps that have direct empty predecessors will be merged to create a bigger gap

    $emptyGroupAIndex = $json->groupEmptyPosition[$emptyGroupKey];
    $emptyGroupBIndex = $json->groupEmptyPosition[$emptyGroupKey - 1];

    $nullGroupSize = $json->groups[$emptyGroupAIndex]->groupSize + $json->groups[$emptyGroupBIndex]->groupSize;

    $nullGroup = (object)[
      "groupSize"  => $nullGroupSize,
      "groupColor" => '#ffffff',
      "occupied"   => FALSE,
    ];


    unset($json->groups[$emptyGroupBIndex]);
    $json->groups[$emptyGroupAIndex] = $nullGroup;
    $json->groups                    = array_values($json->groups);
  }

  if ($mergeAfter && $mergeBefore) {

    // - empty gaps that have direct empty predecessors AND successors will be merged to create a bigger gap

    $emptyGroupAIndex = $json->groupEmptyPosition[$emptyGroupKey - 1];
    $emptyGroupBIndex = $json->groupEmptyPosition[$emptyGroupKey];
    $emptyGroupCIndex = $json->groupEmptyPosition[$emptyGroupKey + 1];

    $nullGroupSize = $json->groups[$emptyGroupAIndex]->groupSize
      + $json->groups[$emptyGroupBIndex]->groupSize
      + $json->groups[$emptyGroupCIndex]->groupSize;

    $nullGroup = (object)[
      "groupSize"  => $nullGroupSize,
      "groupColor" => '#ffffff',
      "occupied"   => FALSE,
    ];


    unset($json->groups[$emptyGroupAIndex]);
    unset($json->groups[$emptyGroupCIndex]);
    $json->groups[$emptyGroupBIndex] = $nullGroup;
    $json->groups                    = array_values($json->groups);
  }

  $json = reSortEmptyPositions($json);

  file_put_contents('database.json', json_encode($json));
  echo json_encode($json->groups);
}