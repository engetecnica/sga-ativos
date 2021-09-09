<?php 
class Anexo_model extends MY_Model {

  public $tipos, $modulos;

  public function __construct()
  { 
    parent::__construct();

      $this->modulos = [
        [
          "nome" => "Ativos Externos - Ferramentas",
          "rota" => "ativo_externo",
        ],
        [
          "nome" => "Ativos Internos - Equipamentos",
          "rota" => "ativo_interno",
        ],
        [
          "nome" => "Ativos Veículos - Veículos",
          "rota" => "ativo_veiculo",
        ]
      ];

       $this->tipos = [
          [
              "nome" => "Nota/Recibo Compra",
              "slug" => "compra",
              "modulos" =>  ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
          ],
          [
              "nome" => "Nota/Recibo Manutenção",
              "slug" => "manutencao",
              "modulos" =>  ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
          ],
          [
              "nome" => "Foto/Declaração de Descarte",
              "slug" => "descarte",
              "modulos" =>  ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
          ],
          [
            "nome" => "Certificado de Calibação/Aferiação",
            "slug" => "certificado_de_calibacao",
            "modulos" =>  ['ativo_externo']
          ],
          [
            "nome" => "Nota/Recibo de Abastecimento",
            "slug" => "kilometragem",
            "modulos" =>  ['ativo_veiculo']
          ],
          [
              "nome" => "Nota/Recibo de IPVA",
              "slug" => "ipva",
              "modulos" =>  ['ativo_veiculo']
          ],
          [
              "nome" => "Nota/Recibo de Seguro",
              "slug" => "seguro",
              "modulos" =>  ['ativo_veiculo']
          ],
          [
              "nome" => "Outro",
              "slug" => "outro",
              "modulos" => ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
          ]
        ];
  }

	public function salvar_formulario($data){
		if(!isset($data['id_anexo'])){
      $this->db->insert('anexo', $data);
		} else {
      $this->db
        ->where('id_anexo', $data['id_anexo'])
        ->update('anexo', $data);
    }
    return $this->db->affected_rows();
	}


  public function query_anexos(){
    $this->db->reset_query();
    return $this->db
              ->from('anexo')
              ->select('anexo.*')
              ->join("modulo md", "md.id_modulo = anexo.id_modulo", "left")
              ->select('md.titulo as modulo_titulo, md.rota as modulo_rota')
              ->group_by('id_anexo')->order_by('id_anexo', 'desc');
  }

	public function anexos($id_modulo = null, $id_modulo_item = null){
    $anexos = $this->query_anexos();

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
		return $anexos;
  }
  

  public function get_anexos(
    $id_modulo = null, 
    $id_modulo_item = null, 
    $id_modulo_subitem = null, 
    $pagina = null, 
    $limite = 50
  ){
    $anexos = $this->anexos($id_modulo,$id_modulo_item, $id_modulo_subitem);
    if (($limite && $pagina) && ($pagina >= 1)) {
      $anexos->limit($limite, (($limite * $pagina) - 1));
    }
    return $anexos->get()->result();
  }

  public function get_anexo($id_modulo = null, $id_modulo_item = null){
    return $this->anexos($id_modulo, $id_modulo_item)->get()->row();
  }

  public function get_anexo_by_name($anexo){
    return $this->query_anexos()
                ->like('anexo', $anexo)        
                ->get()
                ->row();
  }

  public function deletar($id_anexo){
    if ($this->db->where('id_anexo', $id_anexo)->get('anexo')->num_rows() == 1) {
      return $this->db->where('id_anexo', $id_anexo)->delete('anexo');
    }
    return false;  
  }

  public function get_anexo_tipo($slug){
    foreach($this->tipos as $tipo) {
      if ($tipo['slug'] === $slug) {
        return $tipo;
      }
    }

    return null;
  }

}
