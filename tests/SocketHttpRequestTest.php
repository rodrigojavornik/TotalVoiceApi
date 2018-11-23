<?php

namespace Tests;


use CallMe\SocketHttpRequest;
use PHPUnit\Framework\TestCase;

class SocketHttpRequestTest extends TestCase
{
    public function testHttpGet()
    {
        $httpRequest = new SocketHttpRequest();
        $httpRequest->setHost('ssl://api.totalvoice.com.br');
        $httpRequest->setPort(443);
        $httpRequest->addHeader([
            'Host' => 'api.totalvoice.com.br',
            'Access-Token' => '94bf8b13d7f6c6d9c58b13b4029eb40b'
        ]);
        $response = $httpRequest->get("/status");

        $this->assertEquals($response->status, 200);
        $this->assertEquals($response->sucesso, true);
    }

    public function testHttpPost()
    {
        $httpRequest = new SocketHttpRequest();
        $httpRequest->setHost('ssl://api.totalvoice.com.br');
        $httpRequest->setPort(443);
        $httpRequest->addHeader([
            'Host' => 'api.totalvoice.com.br',
            'Access-Token' => '94bf8b13d7f6c6d9c58b13b4029eb40b'
        ]);

        $content = [
            "numero_destino" => "48984429946",
            "mensagem" => "Quer receber uma ligação? Digite SIM para aceitar",
            "resposta_usuario" => true,
            "multi_sms" => false,
            "data_criacao" => ""
        ];

        $response = $httpRequest->post($content, '/sms');

        $this->assertEquals($response->status, 200);
        $this->assertEquals($response->sucesso, true);
    }
}