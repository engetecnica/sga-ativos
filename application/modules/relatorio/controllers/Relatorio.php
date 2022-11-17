<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Xls;

/**
 * Description of site
 *
 * @author André Baill | https://github.com/srandrebaill
 */
class Relatorio extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('relatorio_model');
		$this->load->model('empresa/empresa_model');
		$this->load->model('obra/obra_model');
		$this->load->model('anexo/anexo_model');
		$this->load->model('funcionario/funcionario_model');
		$this->load->model('usuario/usuario_model');
		$this->load->model('notificacoes_model');
	}

	function index()
	{
		$data['relatorios'] = $this->relatorio_model->relatorios;
		$data['relatorios_permitidos'] = $this->permissoes;
		$data['periodos'] = $this->relatorio_model->periodos;
		$data['tipos_veiculos'] = $this->relatorio_model->tipos_veiculos;
		$data['empresas'] = $this->empresa_model->get_empresas();
		$data['obras'] = $this->obra_model->get_obras();
		$data['funcionarios'] = $this->funcionario_model->get_lista();
		$data['usuarios'] = $this->usuario_model->get_lista_simples();
		$data['modulos'] = $this->modulos_permitidos();
		$this->get_template('relatorio_gerar', $data);
	}

	public function get_relatorio_pdf($relatorio_nome, $relatorio_data)
	{
		$data = [
			'css' =>  file_get_contents(__DIR__ . "/../../../../assets/css/relatorios.css", true, null),
			'logo' => $this->base64(__DIR__ . "/../../../../assets/images/icon/logo.png"),
			'header' => $this->base64(__DIR__ . "/../../../../assets/images/docs/termo_header.png"),
			'footer' => $this->base64(__DIR__ . "/../../../../assets/images/docs/termo_footer.png"),
			'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
			'relatorio' => $relatorio_data
		];

		$filename = "relatorio_{$relatorio_nome}_" . date('YmdHis', strtotime('now')) . ".pdf";
		$html = $this->load->view("/../views/relatorio_top", $data, true);
		$html .= $this->load->view("/../views/relatorio_{$relatorio_nome}", $data, true);
		$html .= $this->load->view("/../views/relatorio_footer", $data, true);

		$upload_path = "assets/uploads/relatorio";
		$path = __DIR__ . "/../../../../{$upload_path}";

		if (!is_dir($path)) {
			mkdir($path, 0775, true);
		}

		$file = "{$path}/{$filename}";
		if (!file_exists($file)) {
			$this->gerar_pdf($file, $html);
			return base_url("{$upload_path}/{$filename}");
		}
		return null;
	}

	private function get_relatorio_excel($relatorio, $data, $tipo = 'xls')
	{
		$store_path = "assets/uploads/relatorio";
		$path = APPPATH . "../{$store_path}";
		$filename = "relatorio_{$relatorio}_" . date('YmdHis', strtotime('now')) . ".{$tipo}";
		$file = "{$path}/{$filename}";
		$return_file = null;

		//gerar arquivo
		$relatorio_file = __DIR__ . "/../views/relatorio_{$relatorio}_excel.php";

		if (file_exists($relatorio_file)) {
			//Cria arquivo
			$spreadsheet = new Spreadsheet();
			$spreadsheet->getProperties()
				->setCreator("Engetecnica APP")
				->setLastModifiedBy("Engetecnica APP")
				->setTitle("Office 2007 {$tipo}")
				->setSubject("Office 2007 {$tipo}")
				->setDescription("Documento Office 2007 {$tipo}, gerado por Engetecnica APP")
				->setKeywords("Office 2007 openxml php Excel spreadsheet")
				->setCategory($relatorio);
			$spreadsheet->removeSheetByIndex(0);

			//Usada dentro do arquivo do relatório, não remover
			$sheet = new Worksheet($spreadsheet, 'Planilha Padrão');

			if (require $relatorio_file) {
				$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
				(new Xlsx($spreadsheet))->save($file);
				$return_file = base_url("{$store_path}/{$filename}");
			}
		} else {
			echo "Não foi possível gerar o relatório neste formato";
			return false;
		}
		return $return_file;
	}

	function gerar_grafico($relatorio)
	{
		if ($this->input->method() == 'post') {
			return $this->json($this->relatorio_model->$relatorio($this->input->post(), 'grafico'));
		}
		return  $this->json(null);
	}

	function gerar_arquivo($relatorio)
	{
		if ($this->input->method() == 'post') {
			$data = $this->relatorio_model->$relatorio($this->input->post(), 'arquivo');
			switch ($this->input->post('tipo_arquivo')) {
				default:
				case 'pdf':
					return $this->json([
						'relatorio' =>  $this->get_relatorio_pdf($relatorio, $data),
						'validade' => 120
					]);
					break;
				case 'xlsx':
					
				case 'xls':
					return $this->json([
						'relatorio' =>  $this->get_relatorio_excel($relatorio, $data, $this->input->post('tipo_arquivo')),
						'validade' => 120
					]);
					break;
			}
		}
		return  $this->json(['relatorio' => null]);
	}

	public function crescimento_empresa()
	{
		return $this->json($this->relatorio_model->crescimento_empresa());
	}
}
