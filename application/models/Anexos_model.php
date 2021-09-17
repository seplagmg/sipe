<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anexos_model extends CI_Model {
        function __construct() {
                parent::__construct();
        }
        public function salvar_anexo($dados, $tipo){
                $data=array(
                        'es_candidatura' => $dados['candidatura'],
                        'in_tipo' => $tipo,
                        'vc_mime' => $dados['file_type'],
                        'vc_arquivo' => $dados['orig_name'],
                        'in_tamanho' => $dados['file_size'],
                        'es_usuarioCadastro' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s')
                );
                $this -> db -> insert ('tb_anexos', $data);
                return $this -> db -> insert_id();
        }
        public function get_anexo($id='', $candidatura='', $tipo=''){
                if(strlen($id) > 0 && $id > 0){
                        $this -> db -> where('pr_anexo', $id);
                }
                if(strlen($candidatura) > 0 && $candidatura > 0){
                        $this -> db -> where('es_candidatura', $candidatura);
                }
                if(strlen($tipo) > 0 && $tipo > 0){
                        $this -> db -> where('in_tipo', $tipo);
                }
                $this -> db -> where('bl_removido', '0');
                $this -> db -> select('*');
                $this -> db -> from('tb_anexos');
                $query = $this -> db -> get();
                if($query -> num_rows() > 0){
                        return $query -> result();
                }
                else{
                        return NULL;
                }
        }
}