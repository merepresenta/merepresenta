<?php
  require_once realpath(dirname(__FILE__)."/database_con.php");

  class LeitorDados {
    private $sql = null;
    private $sqlContagem = null;
    private $first = 0;
    private $quant = 0;

    function __construct($sql, $sqlContagem = null, $first = 0, $quant=0) {
      $this->sql = $sql;
      $this->sqlContagem = $sqlContagem;
      $this->first = $first;
      $this->quant = $quant;
    }

    public function leDados($listaRetirar = []) {
      global $wpdb;

      $ret = array();

      $result = $wpdb->get_results($this->sql);
      foreach ($result as $reg) {
        foreach ($listaRetirar as $retirar) {
          eval("unset(\$reg->$retirar);");
        }
        
        $ret[] = $reg;
      }

      if ($this->sqlContagem) {
        $retorno = [];
        $paginacao = [];
        $retorno['data'] = $ret;
        $paginacao['first'] = $this->first;
        $paginacao['quantity'] = $this->quant;
        $paginacao['count'] = $wpdb->get_results($this->sqlContagem)[0]->contagem;
        $retorno['pagination'] = $paginacao;
        return $retorno;
      };
      return $ret;
    }
  }
?>