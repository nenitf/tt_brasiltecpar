# tt_hasheros

Teste técnico para [Brasil TecPar](https://www.brasiltecpar.com.br/)

O teste é formado por 3 partes.

1. A criação de uma rota que encontra um hash, de certo formato, para uma certa string fornecida como input.
    - [ ] Encontro da key que gere hash iniciando com 4 zeros para certa string informada.
    - [ ] Controle do número máximo de requisições aceitas em 1 minuto pela rota.
2. A criação de um comando que consulta a rota criada e armazena os resultados na base de dados.
    - [ ] Buscar e armazenar resultados obtidos.
    - [ ] Respeitar o limite de requisições da rota.
    - [ ] Aguardar o menor tempo possível para realização de todas requisições solicitadas.
3. Criação de rota que retorne os resultados que foram gravados.
    - [ ] Retornar os resultados de forma paginada;
    - [ ] Ter o filtro por "Número de tentativas" podendo filtrar por resultados que tiveram menos de x tentativas.
    - [ ] Não devem ser retornados todos os campos da tabela, somente as informações nas colunas batch, "Número do bloco", "String de entrada" e "Chave encontrada".

## Execução local com Docker

### Configuração inicial

1. Duplique `.env.local.example` e renomeie para `.env.local`
    ```sh
    cp .env.local.example .env.local
    ```

2. Crie os containers
    ```sh
    docker-compose --env-file .env.local up -d
    ```
    > Caso queira, ao final da configuração, pare os containers com ``docker-compose --env-file .env.local down``

3. Baixe as dependências do composer
    ```sh
    docker-compose exec app composer install
    ```

### Execução

Com a **configuração inicial** já realizada, suba os containers se necessário e acesse a aplicação em `localhost:8080`

```sh
docker-compose --env-file .env.local up -d
```

### Teste

- Individual
    ```sh
    docker-compose --env-file .env.local exec app php bin/phpunit tests/caminho/do/ExemploTest.php
    ```

- Completo
    ```sh
    docker-compose --env-file .env.local exec app php bin/phpunit
    ```
