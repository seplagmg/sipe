<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Processos_model extends CI_Model {
        function __construct() {
                parent::__construct();
        }
        public function get_processos($id='', $pasta='') {
                if(strlen($id) > 0){
                        $this -> db -> where('pr_processo', $id);
                }
                if(strlen($pasta) > 0){
                        $this -> db -> where('es_pasta', $pasta);
                }
                $this -> db -> select ('*');
                $this -> db -> from ('tb_processos');
                
                $this -> db -> order_by ('ch_sei', 'ASC');
                $query = $this -> db -> get();                    
                return $query -> result();
        }
        public function get_documentos($id='', $pasta='') {
                if(strlen($id) > 0){
                        $this -> db -> where('d.pr_documento', $id);
                }
                if(strlen($pasta) > 0){
                        $this -> db -> join ('rl_documentos_pastas r', 'd.pr_documento=r.es_documento');
                        $this -> db -> join ('tb_instituicoes2 i', 'r.es_instituicao=i.pr_instituicao');
                        $this -> db -> where('r.es_pasta', $pasta);
                }
                $this -> db -> select ('*');
                $this -> db -> join ('tb_processos p', 'p.pr_processo=d.es_processo');
                $this -> db -> join ('tb_tipos_processo t', 'p.es_tipo_processo=t.pr_tipo_processo');
                $this -> db -> from ('tb_documentos d');
                
                $this -> db -> order_by ('p.ch_sei, d.in_documento');
                $query = $this -> db -> get();                    
                return $query -> result();
        }
        public function update_pasta($campo, $valor, $primaria){
                if(strlen($primaria)==0){
                        return FALSE;
                }
                if(strlen($campo)==0){
                        return FALSE;
                }
                $this -> db -> set ($campo, $valor);
                $this -> db -> set ('es_alterador', $this -> session -> uid);
                $this -> db -> set ('dt_alteracao', date('Y-m-d H:i:s'));
                $this -> db -> where('pr_pasta', $primaria);
                $this -> db -> update ('tb_pastas');
                return $this -> db -> affected_rows();
        }
        public function create_pasta($dados){
                $data=array(
                        'in_masp' => $dados['masp'],
                        'in_admissao' => $dados['admissao'],
                        'vc_nome' => $dados['nome'],
                        'ch_cpf' => $dados['cpf'],
                        'es_instituicao_lotacao' => $dados['lotacao'],
                        'es_instituicao_exercicio' => $dados['exercicio'],
                        'es_cadastrador' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s')
                );
                $this -> db -> insert ('tb_pastas', $data);
                return $this -> db -> insert_id();
        }
        public function add_viewer($dados){
                $data=array(
                        'es_pasta' => $dados['codigo'],
                        'es_instituicao' => $dados['novainst'],
                        'es_cadastrador' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s')
                );
                $this -> db -> replace ('rl_instituicoes_pastas', $data);
                return $this -> db -> insert_id();
        }
        public function delete_view($inst, $pasta){
                $this -> db -> where('es_pasta', $pasta);
                $this -> db -> where('es_instituicao', $inst);
                $this -> db -> delete ('rl_instituicoes_pastas');
                return $this -> db -> affected_rows();
        }
}
