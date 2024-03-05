## Laravel - Multidadosti

[![Downloads](https://img.shields.io/packagist/dt/agenciafmd/laravel-multidadosti.svg?style=flat-square)](https://packagist.org/packages/agenciafmd/laravel-multidadosti)
[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

- Envia as conversões para a Multidadosti

## Instalação

```bash
composer require agenciafmd/laravel-multidadosti:dev-master
```

## Configuração

Para que a integração seja realizada, precisamos da **url da API**

Por isso, é necessário colocar o endereço no nosso .env

```dotenv
MULTIDADOSTI_API_URL=https://xxxxxxxxx.multidadosti.com.br/webservices/rest/api.php?api_method=incluir_oc
```
Caso seja necessária a autenticação, é necessário colocar fornecer os dados no nosso .env

```dotenv
MULTIDADOSTI_AUTH_TOKEN=token
MULTIDADOSTI_USERNAME=username@email.com
MULTIDADOSTI_PASSWORD=sua_senha
```

```dotenv
## Uso

Envie os campos no formato de array para o SendConversionsToMultidadosti.

O campo **email** é obrigatório =)

Para que o processo funcione pelos **jobs**, é preciso passar os valores dos cookies conforme mostrado abaixo.

```php
use Agenciafmd\Multidadosti\Jobs\SendConversionsToMultidadosti;

$data['email'] = 'carlos@fmd.ag';

SendConversionsToMultidadosti::dispatch($data + [
        "usuario_ws": "seu_usuario",
        "senha_ws": "sua_senha",
        "cod_divisao": "COM01",
        "cod_solicitacao": "SO124",
        "cod_campanha": "",
        "cod_origem": "",
        "codigo_midia": "MID10",
        "nome_cliente": "Irineu Martins Junior",
        "email_cliente": "irineu@fmd.ag",
        "ddd_cel_cliente": "17",
        "tel_cel_cliente": "335324444",
        "descricao": "Mensagem de teste da F&MD"
    ])
    ->delay(5)
    ->onQueue('low');
```

Note que no nosso exemplo, enviamos o job para a fila **low**.

Certifique-se de estar rodando no seu queue:work esteja semelhante ao abaixo.

```shell
php artisan queue:work --tries=3 --delay=5 --timeout=60 --queue=high,default,low
```