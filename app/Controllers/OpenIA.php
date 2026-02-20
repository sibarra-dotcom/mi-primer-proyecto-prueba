<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\Cotizacion;
use App\Models\CotizArchivo;
use App\Models\CotizDetalle;
use App\Models\ArticuloComm;
use App\Models\MaquinariaModel;
use App\Models\MantenimientoModel;
use App\Models\MantAdjunto;

use CodeIgniter\HTTP\CURLRequest; 
use App\Models\OpeniaQueriesModel;


class OpenIA extends BaseController
{
		protected $session;
		private $httpClient;
		private $openiaTable;

    public function __construct()
    {
			$this->session = \Config\Services::session();
			$this->httpClient = \Config\Services::curlrequest();
			$this->openiaTable = new OpeniaQueriesModel();
    }

    public function index()
    {
        if ($this->request->getMethod() === 'GET')
        {
					$data['title'] = 'IA CHAT GIBANIBB';

					return view('openia/index', $data);
        }

				if ($this->request->getMethod() === 'POST')
				{
	
					// echo "<pre>";
					// print_r($_POST);
					// echo "</pre>";
					// exit;
	
					$question = $this->request->getPost('question');
	
					if (!$question || trim($question) === '') {
							return $this->response->setJSON(['error' => 'Pregunta vacía']);
					}
	
					$existing = $this->openiaTable->like('question', $question)->first();
	
					if ($existing) {
							return $this->response->setJSON(['answer' => $existing['answer'], 'source' => 'local']);
					}
	
					// $systemPrompt = "Solo debes responder preguntas en español sobre temas relacionados al medio ambiente. " .
					// 				"Si la pregunta no es en español o no es sobre medio ambiente, responde exactamente con: " .
					// 				"'No puedo responder esa pregunta por estar fuera del contexto de este chat.'";
					
					// $systemPrompt = "Only response to questions in spanish language. " . "You are a helpful assistant that explains concepts in a detailed, beginner-friendly way.";

					$systemPrompt = "Solo debes responder preguntas en español. " . "Eres un asistente útil que explica los conceptos de manera detallada y fácil de entender para principiantes.";

	
					$chatResponse = $this->askChatGPT($question, $systemPrompt);
	
					$this->openiaTable->insert([
							'userId' => 1,
							// 'userId' => $this->session->get('user')['id'],
							'question' => $question,
							'answer' => $chatResponse,
					]);
	
					return $this->response->setJSON(['answer' => $chatResponse, 'source' => 'api']);
				}

    }

		private function askChatGPT(string $userPrompt, string $systemPrompt): string
    {
        $apiKey = getenv('OPENAI_API_KEY');
        $endpoint = 'https://api.openai.com/v1/chat/completions';

        $payload = [
						'model' => 'gpt-4o',
						'messages' => [
								['role' => 'system', 'content' => $systemPrompt],
								['role' => 'user', 'content' => $userPrompt]
						],
						'temperature' => 0.7,
						'top_p' => 1,
						'max_tokens' => 1200
        ];

				try {
						$response = $this->httpClient->request('POST', $endpoint, [
								'headers' => [
										'Authorization' => "Bearer $apiKey",
										'Content-Type'  => 'application/json'
								],
								'body' => json_encode($payload)
						]);
		
						$decoded = json_decode($response->getBody(), true);
		
						return $decoded['choices'][0]['message']['content'] ?? 'Error al procesar la respuesta de ChatGPT.';
				} catch (\Exception $e) {
						return 'Error al comunicarse con ChatGPT: ' . $e->getMessage();
				}

    }

}
