<?php
set_time_limit(60);

require __DIR__ . '/../vendor/autoload.php';

use CallMe\SocketHttpRequest;

$token = '94bf8b13d7f6c6d9c58b13b4029eb40b';
$telefone = '48984429946';

$httpRequest = new SocketHttpRequest();
$httpRequest->setHost('ssl://api.totalvoice.com.br');
$httpRequest->setPort(443);
$httpRequest->addHeader([
    'Host' => 'api.totalvoice.com.br',
    'Access-Token' => $token
]);

//inicia o envio do SMS
$content = [
    "numero_destino" => $telefone,
    "mensagem" => "Quer receber uma ligação? Digite SIM para aceitar",
    "resposta_usuario" => true,
    "multi_sms" => false,
    "data_criacao" => ""
];

$response = $httpRequest->post($content, '/sms');

sleep(20);

$response = $httpRequest->get("/sms/" . $response->dados->id);

//inicia a ligação telefonica
if (is_array($response->dados->respostas) && (strcasecmp($response->dados->respostas[0]->resposta, 'sim') === 0)) {
    $content = [
        'numero_destino' => $telefone,
        'dados' => [[
            'acao' => 'audio',
            'acao_dados' => [
                'url_audio' => 'http://projetoheadliner.com/audio1.mp3'
            ],
        ]],
        'bina' => '',
        'gravar_audio' => false
    ];

    $httpRequest->post($content, '/composto');
}

echo "that's all folks";