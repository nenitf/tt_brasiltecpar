# devdocs

Apoio ao desenvolvimento

## Database

```sh
php bin/console doctrine:database:create
php bin/console dbal:run-sql 'SELECT * FROM resultados_cli'
```

### Migration

```sh
php bin/console make:migration
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
php bin/console doctrine:migrations:migrate prev
```
