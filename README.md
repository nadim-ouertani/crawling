# crawling-package

crawling is a PHP library crawling google search result.

## Installation

If you use [docker](https://docker.io/) you can run the following command to set up your environment.

First you need to create a ```docker-compose.yaml``` file

```Docker
version: '3'
services:
  websrv:
    image: php:8.1-apache-buster
    container_name: kr_webserver
    volumes:
      - "./sites:/var/www/html/"
    ports:
      - 80:80
      - 443:443
```

Then run this command:

```bash
docker up -d
```
And finally you need to install composer using the bash.

```bash
docker compose exec websrv bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Now your environment is ready with ```apache2``` && ```PHP```

We use [serpapi](https://serpapi.com/) to get a full json response from Google you need to set up an account and get your API key.

To install the package run:
```bash
composer require nadim/crawling
```

## Usage

```php
require_once "./vendor/autoload.php";

$client = new \SearchEngine\SearchEngine();
$client->setEngine("YOUR_ENGINE");
$client->setApi("YOUR_API_KEY");
$results = $client->search(["ARRAY","OF","KEYWORDS"]);
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)