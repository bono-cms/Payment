
DROP TABLE IF EXISTS `bono_module_payment_transactions`;

CREATE TABLE bono_module_payment_transactions (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `amount` FLOAT NOT NULL COMMENT 'Amount',
    `datetime` DATETIME NOT NULL COMMENT 'Date and time',
    `status` SMALLINT COMMENT 'Status constant',
    `module` varchar(255) NOT NULL COMMENT 'Module where payment was made',
    `token` varchar(255) NOT NULL COMMENT 'Unique transaction token'
);
