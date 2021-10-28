composer require jackiedo/workbench:5.6
Создать новы брачь
php artisan workbench:make --resources Shep/coach

публикация бранча
php artisan vendor:publish --provider="Shep\coach\CoachServiceProvider"

php artisan migrate
php artisan migrate --seed --bench="Shep/coach"