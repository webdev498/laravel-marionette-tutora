## Tutora


### Install instructions

Install using composer install
Rename .example.env to .env
Run "php artisan migrate --seed"


### cloc
```
cloc --exclude-dir=vendor app database grunt public resources tests
````

### Testing

##### Mocha

```
# Single test
mocha tests/mocha/test.js --timeout 30000

# All tests
mocha tests/mocha --timeout 30000

```


