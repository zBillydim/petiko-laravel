Primeiro faça o clone do projeto
Via SSH ou da sua preferencia.
Git clone URL PROJETO

após isso vá até o projeto onde fez o clone e instale o composer

Composer install

depois

php artisan key:generate

configure o banco de dados no .env e rode as migrations

php artisan migrate:install --seed

rode o servidor local

php artisan serve

é necessário o redis estar rodando na maquina