<?php
  class LeitorDados {
    private $mysqli = null;
    private $sql = null;

    function __construct($sql) {
      $this->mysqli = new mysqli("172.17.0.1", "merepresenta", "12345678", "merepresenta");
      $this->mysqli->set_charset("utf8");
      $this->sql = $sql;
    }

    function __destruct () {
      $this->mysqli->close();
    }

    public function leDados() {
        $ret = array();

        $result = $this->mysqli->query($this->sql);
        while ($reg = $result->fetch_object()) {
          $ret[] = $reg;
        }

        $result->close();
        return $ret;
    }
  }

  $input = json_decode(file_get_contents('php://input'),true);

  $sql = "select * from all_data";
  $limits = array();
  $where = array();
  foreach ($input['query'] as $key => $value) {
    $where[] = $key . ' = "' . $value . '"';
  }

  if (sizeof($where)>0) {
    $sql = $sql . " where " . join(" and ", $where);
  }

  if ($input['limites']) {
    $limits[] = 1;
    $limits[] = 10;
    if ($input['limites']['primeiro']) $limits[0] = $input['limites']['primeiro'];
    if ($input['limites']['ultimo']) $limits[1] = $input['limites']['ultimo'];

  }
  if (sizeof($limits) > 0)
    $sql .= " limit " . join(",", $limits);

  error_log("======================================================================================");
  error_log($sql);
  error_log("--------------------------------------------------------------------------------------");

  $leitor = new LeitorDados($sql);

  header('Content-type: application/json');
  echo json_encode($leitor->leDados());

?>



