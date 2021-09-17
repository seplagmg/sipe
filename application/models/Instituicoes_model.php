<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instituicoes_model extends CI_Model {
        function __construct() {
                parent::__construct();
        }
        public function get_instituicoes($id='', $pasta='', $tipo='array'){
                $inst = array();
                if($id == '1371, 2101, 2241, 2091'){
                        $this -> db -> where('i.pr_instituicao in ('.$id.')');
                }
                else if(strlen($id) > 0){
                        $this -> db -> where('i.pr_instituicao', $id);
                }
                if(strlen($pasta) > 0){
                        $this -> db -> where("(i.pr_instituicao in (select es_instituicao from rl_instituicoes_pastas where es_pasta={$pasta}) or i.pr_instituicao in (select es_instituicao_lotacao from tb_pastas where pr_pasta={$pasta}) or i.pr_instituicao in (select es_instituicao_exercicio from tb_pastas where pr_pasta={$pasta}))");
                }
                $this -> db -> where('i.bl_extinto', '0');
                $this -> db -> select('i.*');
                $this -> db -> from('tb_instituicoes2 i');
                $this -> db -> order_by ('i.vc_sigla', 'ASC');
                $query = $this -> db -> get();
                if($tipo == 'array'){
                        if ($query -> num_rows() > 0) {
                                $results = $query -> result_array();
                                $inst = array_column($results, 'vc_sigla', 'pr_instituicao');
                        }
                        return $inst;
                }
                else{
                        return $query -> result();
                }
        }
}
