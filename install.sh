#!/bin/bash 

sudo mysql < tickets.sql

sudo mysql -e "GRANT ALL PRIVILEGES ON SupportTickets.* TO 'ticket'@'localhost' IDENTIFIED BY 'btsinfo';"

sudo chown www-data:www-data -R /var/www/

