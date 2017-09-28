<?php
  class SaidaDadosFactory {
    public static function peloFormato($formato) {
      return ($formato === 'csv') ? new SaidaDadosCSV() : new SaidaDadosJSON();
    }
  }

  /**
  * 
  */
  class SaidaDadosCSV
  {
    public function exporta($dados) {
      $f = fopen('php://temp', 'wt');
      $first = true;
      
      if (sizeof($dados) > 0) {
        fputcsv($f, array_keys(get_object_vars($dados[0])));
        foreach ($dados as $key => $value) {
          fputcsv($f, get_object_vars($value));
        }
        
      }
      $tamanho = ftell($f);
      rewind($f);        

      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Length: $tamanho");
      header("Content-type: application/csv");
      header("Content-Disposition: attachment; filename=merepresenta.csv");
      
      fpassthru($f);
    }
  }

  /**
  * 
  */
  class SaidaDadosJSON
  {
    public function exporta($dados) {
      header('Content-type: application/json');
      echo json_encode($dados);
    }
  }
?>