<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documentos_model extends CI_Model {
        function __construct() {
                parent::__construct();
        }
        public function get_processos($id='', $pasta='') {
                if(strlen($id) > 0){
                        $this -> db -> where('p.pr_processo', $id);
                }
                if(strlen($pasta) > 0){
                        $this -> db -> where('p.es_pasta', $pasta);
                }
                $this -> db -> select ('*');
                $this -> db -> join ('tb_tipos_processo t', 'p.es_tipo_processo=t.pr_tipo_processo');
                $this -> db -> from ('tb_processos p');
                
                $this -> db -> order_by ('p.ch_sei', 'ASC');
                $query = $this -> db -> get();                    
                return $query -> result();
        }
        public function create_processo($dados){
                $data=array(
                        'ch_sei' => $dados -> ProcedimentoFormatado,
                        'es_tipo_processo' => $dados -> TipoProcedimento -> IdTipoProcedimento,
                        'in_codigo_sei' => $dados -> IdProcedimento,
                        'vc_especificacao' => utf8_encode($dados -> Especificacao),
                        'vc_link_processo' => $dados -> LinkAcesso
                );
                $this -> db -> insert ('tb_processos', $data);
                return $this -> db -> insert_id();
        }
        public function update_processo($campo, $valor, $sei){
                if(strlen($sei)==0){
                        return FALSE;
                }
                if(strlen($campo)==0){
                        return FALSE;
                }
                $this -> db -> set ($campo, $valor);
                $this -> db -> where('ch_sei', $sei);
                $this -> db -> update ('tb_processos');
                return $this -> db -> affected_rows();
        }
        public function get_documentos($id='', $pasta='') {
                if(strlen($id) > 0){
                        $this -> db -> where('d.pr_documento', $id);
                }
                if(strlen($pasta) > 0){
                        $this -> db -> join ('rl_documentos_pastas r', 'd.pr_documento=r.es_documento');
                        $this -> db -> join ('tb_instituicoes2 i', 'r.es_instituicao=i.pr_instituicao', 'left');
                        $this -> db -> join ('tb_pastas a', 'a.pr_pasta=r.es_pasta');
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
        public function create_documento($dados, $processo, $unidade, $mime){
                $query = $this -> db -> query("SELECT * from tb_documentos where in_documento = ".$dados -> DocumentoFormatado);
                $num = $query -> num_rows();
                if($num == 0){
                        $data=array(
                                'es_processo' => $processo,
                                'in_documento' => $dados -> DocumentoFormatado,
                                'in_unidade_sei' => $unidade,
                                'vc_documento' => utf8_encode($dados -> Serie -> Nome),
                                'vc_link' => $dados -> LinkAcesso,
                                'vc_mime' => $mime,
                                'dt_sei' => show_sql_date($dados -> Data)
                        );
                        $this -> db -> insert ('tb_documentos', $data);
                        return $this -> db -> insert_id();
                }
        }
        public function associa_documento_pasta($documento, $pasta){
                $data=array(
                        'es_pasta' => $pasta,
                        'es_documento' => $documento,
                        'es_instituicao' => $this -> session -> instituicao,
                        'es_usuario' => $this -> session -> uid,
                        'dt_cadastro' => date('Y-m-d H:i:s'),
                );
                $this -> db -> replace ('rl_documentos_pastas', $data);
                return $this -> db -> insert_id();
        }
        
        public function get_documento_pasta($documento='', $pasta=''){
                if(strlen($documento) > 0){
                        $this -> db -> where('r.es_documento', $documento);
                }
                if(strlen($pasta) > 0){
                        $this -> db -> where('r.es_pasta', $pasta);
                }
                $this -> db -> select ('*');
                $this -> db -> join ('tb_pastas p', 'r.es_pasta=p.pr_pasta');
                $this -> db -> from ('rl_documentos_pastas r');
                
                
                $query = $this -> db -> get();                    
                return $query -> result();
        }
        
        public function exclui_associacao_documento_pasta($documento, $pasta){
                $this -> db -> query("delete from rl_documentos_pastas where es_documento = {$documento} and es_pasta={$pasta}");
                $query = $this -> db -> query("SELECT * from rl_documentos_pastas where es_documento = {$documento}");
                $num = $query -> num_rows();
                if($num == 0){
                        $this -> Documentos_model -> update_documento('bl_ativo', '0', $documento);
                        $this -> Documentos_model -> update_documento('dt_desativacao', date('Y-m-d'), $documento);
                        $this -> Documentos_model -> update_documento('es_desativador', $this -> session -> uid, $documento);
                }
                return $this -> db -> affected_rows();
        }
        public function update_documento($campo, $valor, $primaria){
                if(strlen($primaria)==0){
                        return FALSE;
                }
                if(strlen($campo)==0){
                        return FALSE;
                }
                $this -> db -> set ($campo, $valor);
                $this -> db -> where('pr_documento', $primaria);
                $this -> db -> update ('tb_documentos');
                return $this -> db -> affected_rows();
        }
        public function get_tipos_processos($id='') {
                if(strlen($id) > 0){
                        $this -> db -> where('pr_tipo_processo', $id);
                }
                $this -> db -> select ('*');
                $this -> db -> from ('tb_tipos_processo');
                $this -> db -> where("vc_tipo_processo like 'RH:%' or vc_tipo_processo like 'Pessoal:%'");
                
                $this -> db -> order_by ('vc_tipo_processo', 'ASC');
                $query = $this -> db -> get();                    
                return $query -> result();
        }
        public function update_tipo_processo($campo, $valor, $sei){
                if(strlen($sei)==0){
                        return FALSE;
                }
                if(strlen($campo)==0){
                        return FALSE;
                }
                $this -> db -> set ($campo, $valor);
                $this -> db -> where('pr_tipo_processo', $sei);
                $this -> db -> update ('tb_tipos_processo');
                return $this -> db -> affected_rows();
        }
}
