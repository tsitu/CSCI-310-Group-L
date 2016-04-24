# Minance (CSCI 310 Group L)

## Usage
* To run the application, open Firefox and navigate to "https://localhost/login". Login with the provided credentials.
* To run white-box tests, navigate to /var/www/html/CSCI-310-Group-L/testing and simply run 'cucumber'
* To run black-box tests, navigate to /var/www/html/CSCI-310-Group-L/testing and run 'phpunit black/blah.php'

## SQL Instructions (External - Updated)
* mysql -u sql3112429 -h sql3.freemysqlhosting.net -p
* password is NqxhS6d8yQ
* database is sql3112429

### SQL Instructions (Local - Deprecated)
* mysql -u root -p -h localhost (where password = "password")
* CREATE USER 'dbworker'@'localhost' IDENTIFIED BY 'password';
* CREATE DATABASE minance;
* GRANT ALL PRIVILEGES ON minance.* TO 'dbworker'@'localhost';
* USE minance;
* source data/minance.sql

<strong>Some rules to follow...</strong>

Transaction types should only be "card", "loan", "savings"

Account amounts for types loan and credit are reversed. For example, loans disbursed should start in the negatives (-100.00)
