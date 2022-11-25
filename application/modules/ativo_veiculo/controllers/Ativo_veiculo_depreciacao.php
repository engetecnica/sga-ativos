<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_depreciacao {
    function depreciacao($id_ativo_veiculo = null, $id_ativo_veiculo_depreciacao = null) {
        $data = [
            "id_ativo_veiculo" => $id_ativo_veiculo,
            "meses_ano" => $this->meses_ano
        ];
        $this->merger_anexo_data('depreciacao', $data, $id_ativo_veiculo, $id_ativo_veiculo_depreciacao);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->depreciacao_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_depreciacao) {
            return $this->depreciacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_depreciacao);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_depreciacao) &&
            $id_ativo_veiculo_depreciacao !== 'paginate' &&
            $id_ativo_veiculo_depreciacao !== 'adicionar'
        ) {
            $data['depreciacao'] = $this->ativo_veiculo_model
                ->depreciacao_query($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_depreciacao && $id_ativo_veiculo_depreciacao === 'paginate')
        ) {
            return $this->depreciacao_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_depreciacao && $id_ativo_veiculo_depreciacao !== 'paginate')
        ) {
            return $this->get_template('depreciacao/form', $data); 
        }
        
        $this->get_template('depreciacao/index', $data);
    }

    private function depreciacao_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;
        $total = 0;
        $total_direcao = "up";
        $calc_ativo_veiculo_depreciacao_values = function(...$args) {
            return $this->ativo_veiculo_model->calc_ativo_veiculo_depreciacao_values(...$args);
        };

        return $this->paginate_json([
            "query" => $this->ativo_veiculo_model->depreciacao_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row, $rows, &$data) use(&$total, &$total_direcao, $calc_ativo_veiculo_depreciacao_values) {
                $total = isset($total) ? $total++ : 1;
                $total_direcao = "up";
    
                $row->valores = $calc_ativo_veiculo_depreciacao_values($rows, $data['row_index']);
                if($row->valores->direcao === "up") $total += $row->valores->valor;
                else $total -= $row->valores->valor;

                $row->depreciacao_valor = $row->valores->valor;
                $row->depreciacao_porcentagem = $row->valores->porcentagem;
                $row->fipe_valor_html = $this->formata_moeda($row->fipe_valor);
                $row->fipe_mes_referencia_html = $this->formata_mes_referecia($row->fipe_mes_referencia, $row->fipe_ano_referencia);
                $row->data_html = $this->formata_data($row->data); 

                if($row->id_ativo_veiculo) $row->permit_edit = $this->ativo_veiculo_model->permit_edit_depreciacao($row->id_ativo_veiculo, $row->id_ativo_veiculo_depreciacao);
                if($row->id_ativo_veiculo) $row->permit_delete = $this->ativo_veiculo_model->permit_delete_depreciacao($row->id_ativo_veiculo, $row->id_ativo_veiculo_depreciacao);
                if($total < 0) $total_direcao = "down";
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "depreciacao/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_depreciacao,
                            'link' => base_url('ativo_veiculo')."/depreciacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_depreciacao}", 
                        ]);
                    }
                ],
                [
                    "name" => "depreciacao_valor_html",
                    "view" => "depreciacao/direction",
                    "data" => function($row, $data) {
                        return array_merge($data, ['value' => $this->formata_moeda($row->depreciacao_valor)]);
                    }
                ],
                [
                    "name" => "depreciacao_porcentagem_html",
                    "view" => "depreciacao/direction",
                    "data" => function($row, $data) {
                        return array_merge($data, ['value' => "{$row->depreciacao_porcentagem}%"]);
                    }
                ],
                [                       
                    "name" => "actions",
                    "view" => "depreciacao/actions"
                ]
            ]
        ]);
    }

    private function depreciacao_salvar()
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';
        $data['id_ativo_veiculo_depreciacao'] = $this->input->post('id_ativo_veiculo_depreciacao');

        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);
        $depreciacao = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao($data['id_ativo_veiculo'], $data['id_ativo_veiculo_depreciacao']);

        if (
            (!$data['id_ativo_veiculo_depreciacao'] && $veiculo) || 
            ($data['id_ativo_veiculo_depreciacao'] && ($veiculo && $depreciacao))
        ) {
            $referencia = $this->get_mes_referecia( $this->formata_mes_referecia(
                $this->input->post('fipe_mes_referencia'), 
                $this->input->post('fipe_ano_referencia'), 
            ));

            if(!$referencia || $referencia->ano > (int) date("Y")) {
                $this->session->set_flashdata('msg_erro', "Dados de referência inválidos ou futuro não permitido!");
                echo redirect(base_url("ativo_veiculo/depreciacao/{$data['id_ativo_veiculo']}"));
                return;
            }

            if(
                ($this->ativo_veiculo_model->permit_update_depreciacao($data['id_ativo_veiculo'], $referencia->id, $referencia->ano) && 
                !$data['id_ativo_veiculo_depreciacao']) ||
                $depreciacao 
            ) {
                $data['fipe_mes_referencia'] = $referencia->id;
                $data['fipe_ano_referencia'] = $referencia->ano;
                $data['fipe_valor'] = $this->formata_moeda_float($this->input->post('fipe_valor'));

                if ($data['id_ativo_veiculo_depreciacao'] == '' || !$data['id_ativo_veiculo_depreciacao']) {

                    //Salvar LOG
                    $this->salvar_log(9, null, 'adicionar', $data);    

                    $this->db->insert('ativo_veiculo_depreciacao', $data);
                    $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
                } else {
                    $this->db->where('id_ativo_veiculo_depreciacao', $data['id_ativo_veiculo_depreciacao'])
                        ->update('ativo_veiculo_depreciacao', $data);
                    $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
                }
                echo redirect(base_url("ativo_veiculo/depreciacao/{$data['id_ativo_veiculo']}"));
                return;
            }

            $this->session->set_flashdata('msg_erro', "Já existe um registro para o mês referência!");
            echo redirect(base_url("ativo_veiculo/depreciacao/{$data['id_ativo_veiculo']}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo ou registro de depreciação não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function fipe_get_veiculo_from_model($veiculo) {
        return $this->fipe_get_veiculo($this->tipos[$veiculo->tipo_veiculo], $veiculo->codigo_fipe, $veiculo->ano);
    }

    function depreciacao_atualizar($id_ativo_veiculo = null, $returnJson = false, $automation = false)
    {
        if ($this->user->nivel != 1 || ($this->user->nivel != 1 && !$automation)) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = 
        !is_null($this->input->post('id_ativo_veiculo')) ? 
        $this->input->post('id_ativo_veiculo') : 
        $id_ativo_veiculo;

        $message = "Veiculo não encontrado!";
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $message = null;
            if($this->ativo_veiculo_model->permit_update_depreciacao($id_ativo_veiculo, )) {
                $fipe = $this->fipe_get_veiculo_from_model($veiculo);
                if ($fipe->success) {
                    $data = [
                        'id_ativo_veiculo' => $id_ativo_veiculo,
                        'fipe_valor' => $fipe->data->fipe_valor,
                        'fipe_mes_referencia' =>  (int) date("m"),
                        'fipe_ano_referencia' =>  (int) date("Y"),
                    ];

                    $this->db->insert('ativo_veiculo_depreciacao',  $data);
                }

                $message = $fipe->message;

                if ($returnJson) return $this->json(['success' => $fipe->success , 'message' => $message]);
                $this->session->set_flashdata($fipe->success ? 'msg_success' : 'msg_erro', $message);
                echo redirect(base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"));
                return;
            }

            $message =  "Já existe um registro para o mês atual!";
            if ($returnJson) return $this->json(['success' => true, 'message' => $message]);
            $this->session->set_flashdata('msg_info', $message);
            echo redirect(base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"));
            return;
        }

        if ($returnJson) return $this->json(['success' => false, 'message' => $message]);
        $this->session->set_flashdata('msg_erro', $message);
        echo redirect(base_url("ativo_veiculo"));
        return;
    }

    private function depreciacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $depreciacao = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_depreciacao = {$id_ativo_veiculo_depreciacao}")
            ->get('ativo_veiculo_depreciacao')->num_rows() == 1;

        if (!$depreciacao) {
            $this->session->set_flashdata('msg_erro', "Lançamento depreciacao não encontrado!");
            echo redirect(base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)) {
            $this->session->set_flashdata('msg_erro', "Lançamento depreciacao não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"));
            return false;
        }

        $this->db->where("id_ativo_veiculo_depreciacao = {$id_ativo_veiculo_depreciacao}")->delete('ativo_veiculo_depreciacao');
        echo redirect(base_url("ativo_veiculo/depreciacao/{$id_ativo_veiculo}"));
        return true;
    }
}