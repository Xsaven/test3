# OLX Price Tracker

Цей проект є сервісом для відстеження зміни ціни оголошень на OLX. Користувачі можуть підписуватись на зміни цін та отримувати повідомлення електронною поштою.

## Встановлення

### Вимоги

- PHP >= 8.0
- Composer
- Docker та Docker Compose (включено до Laravel Sail)

### Клонування репозиторію

```bash
git clone https://github.com/Xsaven/test3.git
cd test3
```

### Встановлення через Docker
    
```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```
