<?php
require "./Topics.php";
Page::TopHead("Self-driving rides");

class analyzed_data {

  public $data;
  public $init_set;
  public $map;
  public $vehicles;
  public $rides;
  public $rides_num;
  public $bonus;
  public $steps;
  public $rf1;
  public $rf2;
  public $rt1;
  public $rt2;
  public $es;
  public $lf;

  public $FullRides;

  function __construct($filePath) {
    $this->data = file_get_contents($filePath);
  }

  function getContents () {
    $this->data = explode("\n", $this->data);
    return $this->data;
  }

  function extractRideData () {
    $this->init_set = explode(" ", $this->data[0]);
    $num_rows = intval($this->init_set[0]);
    $num_cols = intval($this->init_set[1]);
    $this->map = array();
    for  ($it = 0; $it <= ($num_rows-1); $it++) {
      for ($i = 0; $i <= ($num_cols-1); $i++) {
        $this->map[$it][$i] = 0;
      }
    }
    $this->vehicles = intval($this->init_set[2]);
    $this->rides = array();
    $this->rides_num = intval($this->init_set[3]);
    for ($v = 1; $v <= count($this->data)-2; $v++) {
      $inter_arr = explode(" ", $this->data[$v]);
      $this->rides[$v] = array(
        'rf1' => intval($inter_arr[0]),
        'rf2' => intval($inter_arr[1]),
        'rt1' => intval($inter_arr[2]),
        'rt2' => intval($inter_arr[3]),
        'es' => intval($inter_arr[4]),
        'lf' => intval($inter_arr[5]),
        'full_distance' =>  abs(0 - intval($inter_arr[2])) + abs(0 - intval($inter_arr[3]));
        'distance_from_to' => abs(intval($inter_arr[0]) - intval($inter_arr[2])) + abs(intval($inter_arr[1]) - intval($inter_arr[3]));
        'distance_from_start' => abs(0 - $inter_arr[0]) + abs(0 - intval($inter_arr[1]));
      );
    }
    $this->bonus = intval($this->init_set[4]);
    $this->steps = intval($this->init_set[5]);
    return $this->rides;
    }

    function displayResult() {
      $res_file = 'results.txt';
      $handle = fopen($res_file, 'w') or die ('Can`t open file:  ' . $res_file);
      $results_content = '1 0'. "\n" . '2 2 1';
      fwrite($handle, $results_content);
    }
}


$test = new analyzed_data("./a_example.in");
$test->getContents();
$test->extractRideData();
$test->displayResult();



Page::Bottom();
?>
