<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://github.com/srandrebaill
 */
class App extends MY_Controller
{

	protected $path, $erro_enviroment;

	function __construct()
	{
		parent::__construct(false);
		$this->load->model('relatorio/relatorio_model');
		$this->load->model('relatorio/notificacoes_model');
		$this->load->helper('download');
		$this->path = __DIR__ . "/../../../../assets/exports";
		$this->erro_enviroment = "Atenção! Essa ação tem uso restrito ao modo 'Desenvolvimento' (development), há riscos ao ser executada
        em modo produção (production).";
	}

	public function automacoes($type = "day")
	{
		$status = [];
		switch ($type) {
			case "day":
				$status = [
					'limpar_exports' => $this->db_export_clear(),
					'limpar_uploads' => $this->relatorio_model->limpar_uploads(),
					'informe_vencimentos' => $this->relatorio_model->enviar_informe_vencimentos(),
					'informe_retiradas_pendentes' => $this->relatorio_model->enviar_informe_retiradas_pendentes(),
					'veiculos_depreciacao' => $this->relatorio_model->atualiza_veiculos_depreciacao(1),
				];
				break;

			case "test":
				$status = [
					'limpar_exports' => $this->db_export_clear(),
					'limpar_uploads' => $this->relatorio_model->limpar_uploads(),
					'informe_retiradas_pendentes' => $this->relatorio_model->enviar_informe_retiradas_pendentes("now", true),
					'informe_vencimentos' => $this->relatorio_model->enviar_informe_vencimentos(30, true),
					'veiculos_depreciacao' => $this->relatorio_model->atualiza_veiculos_depreciacao((int) date("d"), true),
				];
				break;
		}

		$this->json($status);
	}

	public function test_email()
	{
		$success = $this->erro_enviroment;
		if (getenv('CI_ENV') == 'development') {

			$styles = $this->notificacoes_model->getEmailStyles();
			$top = $this->load->view('relatorio/email_top', [
				"ilustration" => false,
				"assunto" => "Test email",
				"styles" => $this->notificacoes_model->getEmailStyles()
			], true);

			$email = "
          <p style=\"{$styles['p']}\">
            Essa é uma mensagem de teste, caso esteja lendo isso, significa que tudo está funcionando como o esperado.
          </p>
        ";
			$footer = $this->load->view('relatorio/email_footer', null, true);

			$success = $this->notificacoes_model->enviar_email(
				"Test Email",
				"{$top}{$email}{$footer}",
				$this->config->item("notifications_email_to"),
				[
					"ilustration" => "images/ilustrations/welcome.png",
				],
				[
					"anexo_test" => "images/ilustrations/welcome.png",
				]
			);
		}
		$this->json(['success' => $success]);
	}

	public function test_push()
	{
		$return = $this->erro_enviroment;
		if (getenv('CI_ENV') == 'development') {
			$return = $this->notificacoes_model->enviar_push("Test Push", "Test Push Notications ok!", [
				"filters" => [
					["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
					["operator" => "AND"],
					["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "2"],
				],
				"url" => "/"
			]);
			$return->success = $return->status == 200;
		}
		$this->json($return);
	}

	public function export()
	{
		if (getenv('CI_ENV') == 'development') {
			if ($this->user && $this->user->nivel == 1) {
				$filename = "{$this->path}/" . date("Ymdhis") . ".json";

				$tables = array_map(function ($table) {
					return array_values((array) $table)[0];
				}, $this->db->query('show tables')->result());

				$data = [];
				foreach ($tables as $table) {
					$data[$table] = $this->db->get($table)->result();
				}

				file_put_contents($filename, json_encode($data));
				return force_download($filename, null);
			}
			echo "Ocorreu um erro ao gerar arquivo!";
			return;
		}

		echo $this->erro_enviroment;
	}

	private function db_export_clear()
	{
		if (getenv('CI_ENV') == 'development') {
			foreach (glob("{$this->path}/*.json") as $filename) {
				unlink($filename);
			}
			return true;
		}
		return false;
	}

	public function rotas()
	{
		$this->dd($this->uri->uri_string());
		// return app/rotas 
	}
}
