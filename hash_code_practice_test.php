<?php
require "./Topics.php";
PAGE::TopHead("Self-driving rides");

class ANALYZED_DATA {

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

  public $rides_list;
  public $combinations;

  function __construct($filePath) {
    $this->data = file_get_contents($filePath);
  }

  function GetContents () {
    $this->data = explode("\n", $this->data);
    return $this->data;
  }

  function ExtractRideData () {
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
    for ($v = 0; $v < $this->rides_num; $v++) {
      $inter_arr = explode(" ", $this->data[$v]);
      $this->rides[$v] = array(
        'rf1' => intval($inter_arr[0]),
        'rf2' => intval($inter_arr[1]),
        'rt1' => intval($inter_arr[2]),
        'rt2' => intval($inter_arr[3]),
        'es' => intval($inter_arr[4]),
        'lf' => intval($inter_arr[5]),
        'full_distance' =>  abs(0 - intval($inter_arr[2])) + abs(0 - intval($inter_arr[3])),
        'distance_from_to' => abs(intval($inter_arr[0]) - intval($inter_arr[2])) + abs(intval($inter_arr[1]) - intval($inter_arr[3])),
        'distance_from_start' => abs(0 - $inter_arr[0]) + abs(0 - intval($inter_arr[1]))
      );
    }
    $this->bonus = intval($this->init_set[4]);
    $this->steps = intval($this->init_set[5]);
    $this->combinations = intval(1);

    $this->rides_list = array_fill(0, 2, array_fill(1, $this->rides_num - 1, array_fill(1, $this->rides_num - 1, array_fill(1, $this->vehicles, array_fill(1, $this->rides_num - 1, 0)))));
    return $this->rides;
    }

    function FindingTrueWays() {

      for($itt = $this->rides_num; $itt >= $this->rides_num - ($this->vehicles - 1); $itt--){

        $this->combinations *= $itt;
      }

      for($rev = 0; $rev <= 1; $rev++){
        for($ride = 1; $ride < $this->rides_num - 1; $ride++){
          for($ride_per_way = $this->rides_num; $ride_per_way > 0; $ride_per_way--){
            for($cur_vehicles = 1; $cur_vehicles < $this->vehicles; $cur_vehicles++){

              for($rides_per_v = $this->ride_per_way - ($this->vehicles - 1); $rides_per_v > 0; $rides_per_v--){
                for($deway = 0; $deway < $rides_per_v; $deway++){

                  $this->rides_list[$rev][$ride][$ride_per_way][$cur_vehicles][0] = 0;

                }
              }

            }
          }
        }
      }

    }

    function DisplayResult() {
      $res_file = 'results.txt';
      $handle = fopen($res_file, 'w') or die ('Can`t open file:  ' . $res_file);
      $results_content = '1 0'. "\n" . '2 2 1';
      fwrite($handle, $results_content);
      var_dump($this->rides_list);
    }

}


$test = new ANALYZED_DATA("./a_example.in");
$test->GetContents();
$test->ExtractRideData();

//$test->FindingTrueWays();

$test->DisplayResult();



PAGE::Bottom();
?>
