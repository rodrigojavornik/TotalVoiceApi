#CallMe

###O que é
Uma aplicação que se conecta a API da TotalVoice por meio de sockets em PHP

###Como funciona
src/SocketHttpRequest realiza requisições HTTP por meio de Sockets.
public/index utiliza a classe SocketHttpRequest para trabalhar com a API da TotalVoice.
O funcionamento do sistema é bem simples. Uma mensagem de texto é encaminhada para 
o celular informado. Caso a mensagem seja respondida com a palavra "sim", o sistema realiza
uma chamada telefonica para o mesmo número que recebeu a mensagem.

###Configurações
Em public/index.php basta setar as variáveis $token com o token fornecido pela TotalVoice e 
$telefone com o número que receberá as interações.

###Executando o código
composer run start

###Executando os testes
composer run test ./tests/SocketsHttpTest