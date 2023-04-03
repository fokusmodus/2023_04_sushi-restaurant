<?php
require "_helpers.php";
require "_addGroup.php";
require "_deleteGroup.php";
require "_init.php";


header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'));

if ($input->method === 'initSeats') {
  initSeats($input->data);
}
if ($input->method === 'addGroup') {
  addGroup($input->data);
}
if ($input->method === 'deleteGroup') {
  deleteGroup($input->data);
}
if ($input->method === 'update') {
  update();
}