#!/bin/bash

#scaffold emma-backend-project
alias emma_backend_add="cp -R  /c/xampp/htdocs/emma-backend-framework/* .";
alias emma_backend_remove="rm -R  ./backend ./vendor ./composer.json ./README.md ./test.php ./testapi.php ./emma_command_setup.bash";

#List of startable servers
alias emma_backend_serve80="php -S localhost:80";
alias emma_backend_serve8000="php -S localhost:8000";
alias emma_backend_serve8080="php -S localhost:8080";

#List of makable classes
alias emma_backend_make_controller="bash ./backend/linux/make_controller.bash";
alias emma_backend_make_event="bash ./backend/linux/make_event.bash";
alias emma_backend_make_exception="bash ./backend/linux/make_exception.bash";
alias emma_backend_make_listener="bash ./backend/linux/make_listener.bash";
alias emma_backend_make_model="bash ./backend/linux/make_model.bash";
alias emma_backend_make_provider="bash ./backend/linux/make_provider.bash";
alias emma_backend_make_utility="bash ./backend/linux/make_utility.bash";
alias emma_backend_make_migration="bash ./backend/linux/make_migration.bash";
