### Setup
```sh
make
```

#### Manually Fetch Articles
##### Articles are fetched automatically every minute, so you don't need to do this
```sh
make fetch_articles
```

#### Api documentation - Swagger
http://localhost:8080/api/documentation

Default User credentials:
```log
test@example.com
password
```

- get token from `/api/login` endpoint
- click on `Authorize` button on top right corner
- paste the token in the input box with `Bearer ` prefix

#### Run tests
```sh
make test
```

#### Run phpstan
```sh
make phpstan
```
