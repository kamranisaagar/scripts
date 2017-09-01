<?php

$query = "DROP TABLE IF EXISTS mpulse.recalled_promotions;

CREATE TABLE `recalled_promotions` (
  `recallid` INT(11) NOT NULL AUTO_INCREMENT,
  `subcat` VARCHAR(255) DEFAULT NULL,
  `startdate` DATE DEFAULT NULL,
  `enddate` DATE DEFAULT NULL,
  PRIMARY KEY (`recallid`),
  KEY `recallid` (`recallid`)
) ENGINE=INNODB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DELIMITER $$

CREATE TRIGGER recall_trigger
BEFORE UPDATE ON promo_header
    FOR EACH ROW
    BEGIN
        IF new.remote = 'RECALLED_ACK' AND new.type=1
        THEN
                INSERT INTO mpulse.recalled_promotions
                    (
                        subcat    ,
                        startdate          ,
                        enddate
                        )
                    VALUES
                    (
                        NEW.articlecategory,
                        DATE(new.startdate),
                        CURDATE()
                    );
        END IF;

    END$$

DELIMITER ;";
			  
$result = $link->mysqli_multi_query($query) or die("Error in the consult.." . mysqli_error($link));