<?php


namespace CallMe;


class SocketHttpRequest
{
    private $host;
    private $port;
    private $fSocket;
    private $header;

    public function __construct()
    {
        $this->header = [];
        $this->addHeader([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }

    /**
     * Inicia uma conexão com um determinado host
     *
     * @throws \ErrorException
     */
    private function startConnection()
    {
        $this->fSocket = fsockopen($this->host, $this->port, $errno, $errstr, 60);

        if(!$this->fSocket){
            throw new \ErrorException('Não foi possível se conectar ao host');
        }
    }

    /**
     *  Fecha o arquivo manipulado
     */
    private function closeFile()
    {
        fclose($this->fSocket);
    }

    /**
     * Seta o host de destino
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * Configura a porta na qual a requisição será realizada
     * @param int $port
     */
    public function setPort(int $port)
    {
        $this->port = $port;
    }

    /**
     * Adiciona novos elementos ao cabeçalho da requisição
     * @param array $header
     */
    public function addHeader(array $header)
    {
        $this->header = array_merge($this->header, $header);
    }


    /**
     * Gera o cabeçalho da requisição HTTP
     * @param string $action
     * @return string cabeçalho da requisição
     */
    private function headerGenerate(string $action)
    {
        $header = $action . "\r\n";

        foreach ($this->header as $key => $value) {
            $header .= "$key: $value \r\n";
        }

        return $header . "\r\n";
    }


    /**
     * Realiza uma requisição HTTP GET
     *
     * @param $path
     * @return mixed
     * @throws \ErrorException
     */
    public function get($path)
    {
        $this->startConnection();

        $header = $this->headerGenerate("GET $path HTTP/1.1");

        fwrite($this->fSocket, $header);

        $response = $this->getResponseBody();

        $this->closeFile();

        return json_decode($response);
    }

    /**
     * Realiza uma requisição HTTP POST
     *
     * @param array $data
     * @param string $path
     * @return mixed
     * @throws \ErrorException
     */
    public function post(array $data, string $path)
    {
        $this->startConnection();

        $content = json_encode($data);
        print_r($content);
        $this->addHeader(
            ['Content-Length' => strlen($content)]
        );

        $header = $this->headerGenerate("POST $path HTTP/1.1");

        fwrite($this->fSocket, $header);
        fwrite($this->fSocket, $content);

        $reponse = $this->getResponseBody();

        $this->closeFile();

        return json_decode($reponse);
    }

    /**
     * Retorna o corpo de resposta da requisição
     *
     * @return string Corpo de resposta da requisição
     */
    private function getResponseBody()
    {
        while ($line = fgets($this->fSocket)) {
            $line = trim($line);
            if ($line == "") {
                break;
            }
        }
        $output = '';

        while ($line = fgets($this->fSocket)) {
            $output .= $line;
        }

        return $output;
    }
}