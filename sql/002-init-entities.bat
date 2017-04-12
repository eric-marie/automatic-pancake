php bin/console doctrine:mapping:import --force AppBundle yml
php bin/console doctrine:mapping:convert annotation ./src
php bin/console doctrine:generate:entities AppBundle
rmdir /s /q "src/AppBundle/Resources/config/doctrine"