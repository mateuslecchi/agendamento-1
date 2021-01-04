﻿

# SISAEFI - Sistema de Agendamento de Espaço Físico

# Requisitos

Certifique-se de ter:

- [PHP 8.0](https://www.php.net/downloads)
- [Mysql](https://www.mysql.com/downloads/)
- [Composer](https://getcomposer.org/download/)
- [Supervisor](http://supervisord.org/installing.html)
- CRON
- Servidor Web
    - Apache: habilitar o _mod_rewrite_
    - Nginx: siga as instruções
      em [https://laravel.com/docs/8.x/deployment#nginx](https://laravel.com/docs/8.x/deployment#nginx)

## Extensões PHP necessárias

Certifique-se de ter as seguintes extensões para a versão correta do PHP (versão 8.0).

- BCMath
- Ctype
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- Fileinfo
- JSON (já incluso na versão 8 do PHP)

# Implementação

1. Clone o repositório:
    - [https://github.com/Sisaefi/sisaefi.git](https://github.com/Sisaefi/sisaefi.git)
2. Dentro do diretório execute:
    - `composer install --optimize-autoloader --no-dev --ignore-platform-req=php`
3. Faça uma copia do arquivo `.env.example` e renomeie para `.env`
    - Certifique-se de personaliza as informações solicitadas no arquivo.
    - Certifique-se de ter a database configurada em `DB_DATABASE` no `.env` devidamente criada.
4. Dentro do diretório execute:
    - `php artisan migrate:fresh --seed`
    - `php artisan config:cache`
    - `php artisan route:cache`
    - `php artisan view:cache`
5. [Configure os works do supervisor](https://laravel.com/docs/8.x/queues#configuring-supervisor) e no arquivo de
   configuração:
    - Em `command` altere
      para: `command=php /CAMINHO/PARA/DIRETORIO/artisan queue:work --sleep=3 --tries=3 --max-time=3600`
    - Em `numprocs` configure conforme a capacidade do servidor e demanda do sistema, sendo recomendado ao valor maior
      ou igual a 2.
6. Configure uma nova entrada cron:
    - `* * * * * cd /CAMINHO/PARA/DIRETORIO && php artisan schedule:run >> /dev/null 2>&1`
7. **Implementação concluída**.

   _Acesse o endereço do sistema pelo seu navegador e utilize as credenciais informadas no arquivo `.env`_
