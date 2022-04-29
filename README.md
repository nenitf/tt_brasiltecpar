# tt_hasheros

Teste técnico para [Brasil TecPar](https://www.brasiltecpar.com.br/) cujo **NÃO OBTIVE RESPOSTA**

O teste é formado por 3 partes.

1. A criação de uma rota que encontra um hash, de certo formato, para uma certa string fornecida como input.
    - [x] Encontro da key que gere hash iniciando com 4 zeros para certa string informada.
    - [x] Criação de rota.
    - [x] Controle do número máximo de requisições aceitas em 1 minuto pela rota.
2. A criação de um comando que consulta a rota criada e armazena os resultados na base de dados.
    - [x] Buscar e armazenar resultados obtidos.
    - [x] Respeitar o limite de requisições da rota.
    - [x] Aguardar o menor tempo possível para realização de todas requisições solicitadas.
3. Criação de rota que retorne os resultados que foram gravados.
    - [x] Retornar os resultados de forma paginada;
    - [x] Ter o filtro por "Número de tentativas" podendo filtrar por resultados que tiveram menos de x tentativas.
    - [x] Não devem ser retornados todos os campos da tabela, somente as informações nas colunas batch, "Número do bloco", "String de entrada" e "Chave encontrada".

## Execução local com Docker

> Foram criado "alias" no `Makefile` para facilitar as operações. Caso não possua o comando `make`, leia as instruções no arquivo e execute-as diretamente.

### Configuração inicial

1. Duplique `.env.local.example` como `.env.local` e atualize as variáveis se necessário
    ```sh
    cp .env.local.example .env.local
    ```

2. Crie os containers
    ```sh
    make up
    ```
    > Caso queira, ao final da configuração, pare os containers com ``make down``

3. Baixe as dependências do composer
    ```sh
    make install
    ```

4. Crie a base de dados
    ```sh
    make createdb
    ```
    ```sh
    make migrate
    ```

### Execução

Com a **configuração inicial** já realizada, suba os containers se necessário e acesse a aplicação em `localhost:8080`

```sh
make up
```

#### API

Acesse a documentação interativa disponível em `localhost:8080/api/doc`

#### CLI

1. Acesse o container
    ```sh
    make bash
    ```

2. Execute o comando
    ```sh
    php bin/console avato:test entrada --requests=20
    ```

### Teste

1. Acesse o container
    ```sh
    make bash
    ```
    
2. Execute o comando
    ```sh
    php bin/phpunit
    ```
    
    > Para um teste específico utilize ``php bin/phpunit tests/caminho/do/ExemploTest.php``
