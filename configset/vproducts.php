<?php

$productsQuery="
truncate unicenta.products_cat;

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-1', 'VP-1', 'VP-1', '-Gifts Product', '1', '1', 'ON-1', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-1',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-2', 'VP-2', 'VP-2', '-Lighters Product', '1', '1', 'ON-2', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-2',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-3', 'VP-3', 'VP-3', '-Bongs and Pipe Product', '1', '1', 'ON-3', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-3',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-4', 'VP-4', 'VP-4', '-Confectionery and Chews Product', '1', '1', 'ON-4', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-4',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-5', 'VP-5', 'VP-5', '-TACC Product', '1', '1', 'ON-5', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-5',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-6', 'VP-6', 'VP-6', '-Accessories and ECIG Product', '1', '1', 'ON-6', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-6',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-7', 'VP-7', 'VP-7', '-Cold Drinks Product', '1', '1', 'ON-7', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-7',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-12', 'VP-12', 'VP-12', '-Misc Product', '1', '1', 'ON-12', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-12',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-13', 'VP-13', 'VP-13', '-Misc Cig and Tobacco Product', '1', '1', 'ON-13', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-13',NULL);

INSERT IGNORE INTO unicenta.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISVPRICE) 
VALUES ('VP-15', 'VP-15', 'VP-15', '-Cigar Product', '1', '1', 'ON-15', '001', true);
INSERT IGNORE INTO unicenta.products_cat VALUES('VP-15',NULL);";
?>