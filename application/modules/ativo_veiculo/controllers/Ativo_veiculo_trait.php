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

    //protected $file_json_file_path = APPPATH."../assets/uploads/fipe/fipe.json";
    protected $file_json_file_path = "/tmp/fipe.json";

    # Testando tipos de veiculos pela FIPE
    function fipe_get_marcas($tipo = null, $returnArray = false)
    {
        if (!$tipo) $tipo = $this->input->post('tipo_veiculo');
        $marcas = [];   

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

        if ($returnArray) return $marcas;
        foreach ($marcas as $marca) echo "<option value=" . $marca['codigo'] . ">" . $marca['nome'] . "</option>";
    }

    # Modelos - Tabela FIPE
    public function fipe_get_modelos($tipo = null, $marca = null, $returnArray = false)
    {
        if (!$tipo) $tipo = $this->input->post('tipo_veiculo');
        if (!$marca) $marca = $this->input->post('id_marca');
        $modelos = [];

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

        if ($returnArray) return $modelos;
        foreach ($modelos['modelos'] as $modelo) echo "<option value=" . $modelo['codigo'] . ">" . $modelo['nome'] . "</option>";
    }


    # Anos - Tabela FIPE
    public function fipe_get_anos($returnArray = false)
    {
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');
        $anos = [];

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

        if ($returnArray) return $anos;
        foreach ($anos as $modelo) echo "<option value=" . $modelo['codigo'] . ">" . $modelo['nome'] . "</option>";
    }

    # Anos - Tabela FIPE
    public function fipe_get_veiculos($returnArray = false)
    {
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');
        $ano = $this->input->post('ano');
        $veiculos = [];

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

        if ($returnArray) return $veiculos;
        echo json_encode($veiculos);
    }

    # Modelos - Tabela FIPE
    public function fipe_veiculo($tipo, $id_marca, $id_modelo)
    {
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
                    return (object) [
                        'marca' => $marca,
                        'modelo' => $modelo['nome'],
                    ];
                }
            }
        }

        return (object) [
            'marca' => '-',
            'modelo' => '-',
        ];
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