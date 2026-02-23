## Requirements

To run the project without the need for local PHP or database installation, a Docker environment is required:

* **Windows**: Docker Desktop with WSL2 integration enabled.
* **Linux/macOS**: Docker and Docker Compose.

And:
* **API KEY**: An active token (API Read Access Token) from [The Movie Database](https://www.themoviedb.org/)

## Running

### 1. Clone repository and go to project directory.
### 2. copy .env.example into .env file
```
cp .env.example .env
```
In .env file add these lines:
```
TMDB_TOKEN=your_token_here
TMDB_BASE_URL=https://api.themoviedb.org/3
```
### 3. Run containers
If you don't have PHP/Composer on your host machine, run this command in the terminal (while in the project folder). It will pull the Composer image, mount your folder, and install everything required:
```
# Run a temporary Composer container to install dependencies
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
Next:
```
./vendor/bin/sail up -d
```
### 4. Install dependencies and run migration:
```
./vendor/bin/sail composer install
./vendor/bin/sail artisan migrate
```

### Run Feature tests
```
./vendor/bin/sail artisan test --testsuite=Feature
```

## Api Documentation
Endpoints descriptions are in Intellij .http format file: [api-docs.http](./api-docs.http)
