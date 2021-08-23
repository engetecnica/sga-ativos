<?php 
class Anexo_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_anexo'] == ''){
      $this->db->insert('anexo', $data);
		} else {
      $this->db
        ->where('id_anexo', $data['id_anexo'])
        ->update('anexo', $data);
    }
    return $this->db->affected_rows();
	}

	public function anexos($id_modulo = null, $id_modulo_item = null){
		$this->db->reset_query();
    $anexos = $this->db
              ->from('anexo')
              ->select('anexo.*')
              ->join("modulo md", "md.id_modulo = anexo.id_modulo", "left")
              ->select('md.titulo as modulo_titulo, md.rota as modulo_rota');

    if ($id_modulo) {
      if (is_array($id_modulo)) {
        $anexos->where("anexo.id_modulo IN (".implode(',',$id_modulo).")");
      } else {
        $anexos->where("anexo.id_modulo = {$id_modulo}");
      }
    }

    if ($id_modulo_item) {
      if (is_array($id_modulo_item)) {
        $anexos->where("anexo.id_modulo_item IN (".implode(',',$id_modulo_item).")");
      } else {
        $anexos->where("anexo.id_modulo_item = {$id_modulo_item}");
      }
    }

		return $anexos->group_by('id_anexo')->order_by('id_anexo', 'desc');
  }
  

  public function get_anexos($id_modulo = null, $limit = null, $pagina = null){
    $anexos = $this->anexos($id_modulo);
    if (($limite && $pagina) && ($pagina >= 1)) {
      $anexos->limit($limit, (($limit * $page) - 1));
    }
    return $anexos->get()->result();
  }

  public function get_anexo($id_modulo = null, $id_modulo_item = null){
    return $this->anexos($id_modulo, $id_modulo_item)->get()->row();
  }

  function deletar($id_modulo = null, $id_modulo_item = null){
    $anexo = $this->get_anexo($id_modulo, $id_modulo_item);
    if ($anexo) {
      return $this->db->where('id_anexo', $anexo->id_anexo)->delete('anexo');
    }
    return false;  
  }
}