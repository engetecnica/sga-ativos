<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

# Iniciando a FIPE - Carros, motos e caminhões
require_once APPPATH . "../vendor/deividfortuna/fipe/src/IFipe.php";
require_once APPPATH . "../vendor/deividfortuna/fipe/src/FipeCarros.php";
require_once APPPATH . "../vendor/deividfortuna/fipe/src/FipeMotos.php";
require_once APPPATH . "../vendor/deividfortuna/fipe/src/FipeCaminhoes.php";

use DeividFortuna\Fipe\IFipe;
use DeividFortuna\Fipe\FipeCarros;
use DeividFortuna\Fipe\FipeMotos;
use DeividFortuna\Fipe\FipeCaminhoes;

trait Ativo_veiculo_trait {

    protected $file_json_file_path = APPPATH."../assets/uploads/fipe/fipe.json";
    //protected $file_json_file_path = "/tmp/fipe.json"; 

    # Testando tipos de veiculos pela FIPE
    function fipe_get_marcas($tipo = null, $returnArray = false)
    {
        $marcas = [];   

        if (!$tipo) $tipo = $this->input->post('tipo_veiculo');

        if (in_array($tipo, $this->tipos_vetor) || $tipo != "maquina") {
            if ($this->existsInLocalFipe($tipo, 'marcas')) {
                $marcas = $this->readLocalFipe()[$tipo]['marcas'];
            } else {
                switch ($tipo) {
                    case 'carro':
                        $marcas = FipeCarros::getMarcas();
                        break;

                    case 'moto':
                        $marcas = FipeMotos::getMarcas();
                        break;

                    case 'caminhao':
                        $marcas = FipeCaminhoes::getMarcas();
                    break;
                }
                $this->saveLocalFipe(["{$tipo}" => ["marcas" => is_array($marcas) ? $marcas : []]]);
            } 

        }

        if (in_array($tipo, ['maquina', 'machine'])) {
            $marcas = $this->get_maquinas_custom_marcas();
        }

        if ($returnArray) return $marcas;
        foreach ($marcas as $marca) echo "<option value=" . $marca['codigo'] . ">" . $marca['nome'] . "</option>";
    }

    # Modelos - Tabela FIPE
    public function fipe_get_modelos($tipo = null, $marca = null, $returnArray = false)
    {
        $modelos = [];

        if (!$tipo) $tipo = $this->input->post('tipo_veiculo');
        if (!$marca) $marca = $this->input->post('id_marca');

        if (in_array($tipo, $this->tipos_vetor)) {
            if ($this->existsInLocalFipe($tipo, 'marcas', $marca, 'modelos')) {
                $modelos = $this->readLocalFipe()[$tipo]['marcas'][$marca]['modelos'];
            } else {
                switch ($tipo) {
                    case 'carro':
                        $modelos = FipeCarros::getModelos($marca);
                        break;

                    case 'moto':
                        $modelos = FipeMotos::getModelos($marca);
                        break;

                    case 'caminhao':
                        $modelos = FipeCaminhoes::getModelos($marca);
                        break;
                }
                $this->saveLocalFipe(["{$tipo}" => ['marcas' => ["{$marca}" => ["modelos" => $modelos]]]]);
            }
        } 

        if (in_array($tipo, ['maquina', 'machine'])) {
            $modelos = $this->get_maquinas_custom_modelos();
        }

        if ($returnArray) return $modelos;
        foreach ($modelos['modelos'] as $modelo) echo "<option value=" . $modelo['codigo'] . ">" . $modelo['nome'] . "</option>";
    }


    # Anos - Tabela FIPE
    public function fipe_get_anos($returnArray = false)
    {
        $anos = [];
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');

        if (in_array($tipo, $this->tipos_vetor)) {
            if ($this->existsInLocalFipe($tipo, 'marcas', $marca, $modelo, 'anos')) {
                $anos = $this->readLocalFipe()[$tipo]['marcas'][$marca][$modelo]['anos'];
            } else {
                switch ($tipo) {
                    case 'carro':
                        $anos = FipeCarros::getAnos($marca, $modelo);
                    break;

                    case 'moto':
                        $anos = FipeMotos::getAnos($marca, $modelo);
                    break;

                    case 'caminhao':
                        $anos = FipeCaminhoes::getAnos($marca, $modelo);
                    break;
                }
                $this->saveLocalFipe(["{$tipo}" => ['marcas' => ["{$marca}" => ["{$modelo}" => ["anos" => $anos]]]]]);
            }
        } 
        
        if (in_array($tipo, ['maquina', 'machine'])) {
            $anos = $this->get_maquinas_custom_modelos()['anos'];
        }

        if ($returnArray) return $anos;
        foreach ($anos as $modelo) echo "<option value=" . $modelo['codigo'] . ">" . $modelo['nome'] . "</option>";
    }

    # Anos - Tabela FIPE
    public function fipe_get_veiculos($returnArray = false)
    {
        $veiculos = [];
        
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');
        $ano = $this->input->post('ano');

        if (in_array($tipo, $this->tipos_vetor)) {
            if ($this->existsInLocalFipe($tipo, $marca, $modelo, $ano ,'veiculos')) {
                $veiculos = $this->readLocalFipe()[$tipo]['marcas'][$marca][$modelo][$ano]['veiculos'];
            } else {
                switch ($tipo) {
                    case 'carro':
                        $veiculos = FipeCarros::getVeiculo($marca, $modelo, $ano);
                    break;

                    case 'moto':
                        $veiculos = FipeMotos::getVeiculo($marca, $modelo, $ano);
                    break;

                    case 'caminhao':
                        $veiculos = FipeCaminhoes::getVeiculo($marca, $modelo, $ano);
                    break;
                }
                $this->saveLocalFipe(["{$tipo}" => ['marcas' =>  ["{$marca}" => ["{$modelo}" => ["{$ano}" => [
                    "veiculos" => $veiculos,
                ]]]]]]);
            }
        }
        
        if (in_array($tipo, ['maquina', 'machine'])) {
            $veiculos = $this->get_maquinas_custom_veiculos($marca, $modelo, $ano);    
        }

        if ($returnArray) return $veiculos;
        echo json_encode($veiculos);
    }

    public function fipe_get_veiculo($tipo, $codigo, $ano) {
        $success = false;
        $data = $message = $response = null; 

        if($tipo == 'machine') {
            $tipo = 'trucks';
        }

        if($codigo) {
            try {
                $response = $this->httpClient("https://parallelum.com.br/fipe/api/v2/{$tipo}/{$codigo}/years/{$ano}");
            } catch (\Exception $e) {
                if(strpos('unsupported vehicle type', $e->getMessage()) >= 0) {
                    $response = null;
                    $message = "Tipo de veículo não suportado, código FIPE ou Ano de fabricação não encontrado!";
                }
            }
        }

        $data = [
            'id_veiculo_tipo' => null,
            'codigo' => null,
            'fipe_valor' => 0,
            'fipe_mes_referencia' => null,
            'fipe_ano_referencia' => null,
            'marca' => null,
            'modelo' => null,
            'combustivel' => null,
            'combustivel_sigla'=> null,
            'ano' => null,
        ];

        if($response && $response->getStatusCode() === 200) {
            $fipe = json_decode($response->getBody()->getContents());
            $referencia = $this->ativo_veiculo_model->get_mes_referecia($fipe->referenceMonth);
            
            $data = (object) [
                'id_veiculo_tipo' => null,
                'codigo' => $fipe->codeFipe,
                'fipe_valor' => $this->formata_moeda_float($fipe->price),
                'fipe_mes_referencia' => $referencia->id,
                'fipe_ano_referencia' =>  $referencia->ano,
                'marca' => $fipe->brand,
                'modelo' => $fipe->model,
                'combustivel' => $fipe->fuel,
                'combustivel_sigla'=> $fipe->fuelAcronym,
                'ano' => $fipe->modelYear,
            ];

            $success = true;
            $message = "Novo registro inserido com sucesso!";
        }
    
        if(!$success && !$message) $message = "Ocorreu um erro ao buscar dados FIPE do veículo!";

        return (object) ([
            "success" => $success, 
            "data" => $data, 
            "message" => $message
        ]);
    }
    

    # Modelos - Tabela FIPE
    public function fipe_veiculo($tipo, $id_marca, $id_modelo)
    {
        $marca = (object) ['marca' => '-', 'modelo' => '-'];
        if (in_array($tipo, $this->tipos_vetor)) {
            $marca = null;
            $marcas = $this->fipe_get_marcas($tipo, true); 
            if (is_array($marcas)) {
                foreach ($marcas as $mar) {
                    if ($mar['codigo'] == $id_marca) {
                        $marca = $mar['nome'];
                        break;
                    }
                }
            }

            $modelos = $this->fipe_get_modelos($tipo, $id_marca, true);
            if (is_array($modelos)) {
                foreach ($modelos['modelos'] as $modelo) {
                    if (!is_bool($modelo) && $modelo['codigo'] == $id_modelo) {
                        $marca = (object) [
                            'marca' => $marca, 
                            'modelo' => $modelo['nome']
                        ];
                    }
                }
            }
        } else {
            $marca = (object) [
                'marca' => array_values(array_filter($this->get_maquinas_custom_marcas(),function($mrc) use ($id_marca) {return $mrc['codigo'] == $id_marca;}))[0]['nome'],
                'modelo' => array_values(array_filter($this->get_maquinas_custom_modelos()['modelos'],function($mdl) use ($id_modelo) {return $mdl['codigo'] == $id_modelo;}))[0]['nome'],
            ];
        }
        
        return $marca;
    }


    public function get_maquinas_custom_modelos(){
        return [ 
            "modelos" => $this->config->item('veiculos_modelos'),
            "anos" => array_map(function($ano){return ["nome" => $ano, "codigo" => $ano];}, range(1987, (int) date('Y') + 1)),
        ];
    }

    public function get_maquinas_custom_marcas(){
        return $this->config->item('veiculos_marcas');
    }

    public function get_maquinas_custom_veiculos($marca, $modelo, $ano){
        if($marca && $modelo && $ano) {
        $marca = array_values(array_filter($this->get_maquinas_custom_marcas(),function($mrc) use ($marca) {return $mrc['codigo'] == $marca;}))[0];
        $modelo = array_values(array_filter($this->get_maquinas_custom_modelos()['modelos'],function($mdl) use ($modelo) {return $mdl['codigo'] == $modelo;}))[0];
            return [
                "Valor" => "",
                "Marca" => $marca['nome'],
                "Modelo" => "{$modelo['nome']} {$ano}",
                "AnoModelo" => $ano,
                "Combustivel" => "Diesel",
                "CodigoFipe" => "",
                "MesReferencia" => "",
                "TipoVeiculo" => 3,
                "SiglaCombustivel" => "D"
            ];
        }
        return [];
    }

    private function existsInLocalFipe($tipo, $key, $subkey = null, $subkey2 = null, $subkey3 = null) : bool 
    {
        $localFipe = $this->readLocalFipe();
        if (isset($localFipe[$tipo][$key]) && !empty($localFipe[$tipo][$key])) {
            if ($subkey && $subkey2 && $subkey3) {return isset($localFipe[$tipo][$key][$subkey][$subkey2][$subkey3]) && !empty($localFipe[$tipo][$key][$subkey][$subkey2][$subkey3]);}
            if ($subkey && $subkey2 && !$subkey3) {return isset($localFipe[$tipo][$key][$subkey][$subkey2]) && !empty($localFipe[$tipo][$key][$subkey][$subkey2]);}
            if ($subkey && !$subkey2 && !$subkey3) {return isset($localFipe[$tipo][$key][$subkey]) && !empty($localFipe[$tipo][$key][$subkey]);}
            return true;
        }
        return false;
    }

    private function filePermission() {
        try {
            @fileperms($this->file_json_file_path);
        } catch (\Exception $e) {
            $this->file_json_file_path = "/tmp/fipe.json";
        }
    }

    private function readLocalFipe(): array 
    {
        $this->filePermission();
        if (file_exists($this->file_json_file_path) ) {
            $localFipe = json_decode(file_get_contents($this->file_json_file_path), true);
            return is_array($localFipe) ? $localFipe : [];
        }
        return [];
    }

    private function saveLocalFipe(array $content = []) : int
    {
        $this->filePermission();
        if (!file_exists($this->file_json_file_path)) touch($this->file_json_file_path);
        return file_put_contents($this->file_json_file_path, json_encode(array_merge($content, $this->readLocalFipe())));
    }
}