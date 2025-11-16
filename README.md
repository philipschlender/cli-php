# Cli

## How to install

1. Add the following repositories to the ```repositories``` element of the ```composer.json``` file

    ```json
    {
        "type": "github",
        "url": "https://github.com/philipschlender/cli-php.git"
    },
    {
        "type": "github",
        "url": "https://github.com/philipschlender/faker-php.git"
    },
    {
        "type": "github",
        "url": "https://github.com/philipschlender/json-php.git"
    }
    ```

2. Add the package ```philipschlender/cli-php``` to the ```require``` or ```require-dev``` element of the ```composer.json``` file

    ```bash
    composer require philipschlender/cli-php
    ```
