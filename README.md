 # Minance (CSCI 310 Group L)

## SQL Instructions
* mysql -u root -p -h localhost (where password = "password")
* CREATE USER 'dbworker'@'localhost' IDENTIFIED BY 'password';
* CREATE DATABASE minance;
* GRANT ALL PRIVILEGES ON minance.* TO 'dbworker'@'localhost';
* USE minance;
* source data/minance.sql

<strong>Some rules to follow...</strong>

Transaction types should only be "card", "loan", "savings"

Account amounts for types loan and credit are reversed. For example, loans disbursed should start in the negatives (-100.00).
