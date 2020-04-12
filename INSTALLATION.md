Installation
============

Sometimes modules might require payment processing. Then depending on payment status, you'd want to control what to do with data. This document describes how to connect this module to another one that handles payments/transactions.

Getting started
===============

Typically when working with bookings and transactions, you'd usually want to store related information in a dedicated table. To make your current table just add these columns:

`extension` varchar(255) COMMENT 'Payment extension'
`amount` FLOAT NOT NULL COMMENT 'Payment amount',
`token` varchar(255) NOT NULL COMMENT 'Unique transaction token',
`status` TINYINT NOT NULL COMMENT 'Transaction status',

After you add these columns, you're ready to go!