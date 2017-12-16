<?php
  /**
  * 
  */
  class SaidaDadosCSV
  {
    private $campos_resposta = array(
      'resposta_1'=>'Adoção por famílias LGBTs',
      'resposta_2'=>'Respeito à identidade de gênero de pessoas trans',
      'resposta_3'=>'Cota para mulheres no legislativo',
      'resposta_4'=>'Igualdade de gênero e raça nas escolas',
      'resposta_5'=>'Ações afirmativas raciais',
      'resposta_6'=>'Estado Laico',
      'resposta_7'=>'Combate à violência contra mulher',
      'resposta_8'=>'Descriminalização do aborto',
      'resposta_9'=>'Criminalização da LGBTfobia',
      'resposta_10'=>'Desmilitarização da polícia',
      'resposta_11'=>'Desapropriação por interesse social',
      'resposta_12'=>'Defesa do meio ambiente',
      'resposta_13'=>'Orçamento participativo',
      'resposta_14'=>'Transparência'
      );

    public function exporta($dados) {
      $f = fopen('php://temp', 'wt');
      $first = true;
      $campos = array_keys(get_object_vars($dados[0]));
      if (sizeof($dados) > 0) {
        // Nome de todos os campos
        foreach ($campos as $key => $value) {
          if (array_key_exists($value, $this->campos_resposta)) {
            $campos[$key] = $this->campos_resposta[$value];
          }
         }  
        fputcsv($f, $campos);

        // Dados
        foreach ($dados as $key => $value) {
          fputcsv($f, get_object_vars($value));
        }
      }
      $tamanho = ftell($f);
      rewind($f);        

      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Length: $tamanho");
      header("Content-Type: application/csv; charset=utf-8");
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
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($dados);
    }
  }
?>
