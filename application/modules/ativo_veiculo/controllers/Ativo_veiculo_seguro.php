<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_seguro {
    function seguro($id_ativo_veiculo = null, $id_ativo_veiculo_seguro = null) {
        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $this->merger_anexo_data('seguro', $data, $id_ativo_veiculo, $id_ativo_veiculo_seguro);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->seguro_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_seguro) {
            return $this->seguro_deletar($id_ativo_veiculo, $id_ativo_veiculo_seguro);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_seguro) &&
            $id_ativo_veiculo_seguro !== 'paginate' &&
            $id_ativo_veiculo_seguro !== 'adicionar'
        ) {
            $data['seguro'] = $this->ativo_veiculo_model
                ->seguro_query($id_ativo_veiculo, $id_ativo_veiculo_seguro)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_seguro && $id_ativo_veiculo_seguro === 'paginate')
        ) {
            return $this->seguro_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_seguro && $id_ativo_veiculo_seguro !== 'paginate')
        ) {
            return $this->get_template('seguro/form', $data); 
        }
        
        $this->get_template('seguro/index', $data);
    }

    private function seguro_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;

        return $this->paginate_json([
            "query" => $this->ativo_veiculo_model->seguro_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row) {
                $row->seguro_custo_html = $this->formata_moeda($row->seguro_custo); 
                $row->carencia_inicio_html = $this->formata_data($row->carencia_inicio);
                $row->carencia_fim_html = $this->formata_data($row->carencia_fim); 
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "seguro/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_seguro,
                            'link' => base_url('ativo_veiculo')."/seguro/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_seguro}", 
                        ]);
                    }
                ],
                [                       
                    "name" => "actions",
                    "view" => "seguro/actions"
                ]
            ]
        ]);
    }

    public function seguro_salvar()
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_ativo_veiculo_seguro'] = $this->input->post('id_ativo_veiculo_seguro');
            $data['seguro_custo'] = $this->remocao_pontuacao($this->input->post('seguro_custo'));
            $data['carencia_inicio'] = $this->input->post('carencia_inicio');
            $data['carencia_fim'] = $this->input->post('carencia_fim');

            if ($data['id_ativo_veiculo_seguro'] == '' || !$data['id_ativo_veiculo_seguro']) {

                // Salvar LOG
                $this->salvar_log(9, $data['id_ativo_veiculo_seguro'], 'adicionar', $data);

                $this->db->insert('ativo_veiculo_seguro', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_seguro', $data['id_ativo_veiculo_seguro'])
                    ->update('ativo_veiculo_seguro', $data);

                    // Salvar LOG
                    $this->salvar_log(9, $data['id_ativo_veiculo_seguro'], 'editar', $data);

                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            $last_id = $data['id_ativo_veiculo_seguro'] ? $data['id_ativo_veiculo_seguro'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/seguro/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function seguro_deletar($id_ativo_veiculo, $id_ativo_veiculo_seguro)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $seguro = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_seguro = {$id_ativo_veiculo_seguro}")
            ->get('ativo_veiculo_seguro')->num_rows() == 1;

        if (!$seguro) {
            $this->session->set_flashdata('msg_erro', "Lançamento seguro não encontrado!");
            echo redirect(base_url("ativo_veiculo/seguro/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/seguro/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro)) {
            $this->session->set_flashdata('msg_erro', "Lançamento seguro não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/seguro/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'seguro', $id_ativo_veiculo_seguro);
        $this->db->where("id_ativo_veiculo_seguro = {$id_ativo_veiculo_seguro}")->delete('ativo_veiculo_seguro');

        // Salvar LOG
        $data["id_ativo_veiculo"] = $id_ativo_veiculo;
        $data["id_ativo_veiculo_seguro"] = $id_ativo_veiculo_seguro;
        $this->salvar_log(9, $id_ativo_veiculo_seguro, 'excluir', $data);

        echo redirect(base_url("ativo_veiculo/seguro/{$id_ativo_veiculo}"));
        return true;
    }
}