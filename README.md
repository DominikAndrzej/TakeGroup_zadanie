## Wymagania

Aby uruchomić projekt bez konieczności instalowania lokalnego PHP czy baz danych, wymagane jest środowisko Docker:

* **Windows**: Docker Desktop z włączoną integracją WSL2.
* **Linux/macOS**: Docker oraz Docker Compose.

Oraz:
* **Klucz API**: Aktywny token (API Read Access Token) z serwisu [The Movie Database](https://www.themoviedb.org/)

## Uruchamianie

### 1. Sklonuj repozytorium i wejdź do folderu projektu.
### 2. Skopiuj plik .env.example do .env
```
cp .env.example .env
```
W pliku .env dopisz następujące linijki:
```
TMDB_TOKEN=twoj_token_tutaj
TMDB_BASE_URL=TMDB_BASE_URL=https://api.themoviedb.org/3
```
### 3. Uruchom kontenery
Jeśli nie masz PHP/Composera na maszynie hosta, użyj tej komendy w terminalu (będąc w folderze projektu). Pobierze ona obraz Composera, zamontuje Twój folder i zainstaluje wszystko, co potrzebne:
```
# Uruchamiamy tymczasowy kontener z Composerem, aby zainstalować biblioteki
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
Następnie:
```
./vendor/bin/sail up -d
```
### 4. Zainstaluj zależności i wykonaj migracje:
```
./vendor/bin/sail composer install
./vendor/bin/sail artisan migrate
```
