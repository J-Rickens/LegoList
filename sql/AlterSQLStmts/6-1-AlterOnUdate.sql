ALTER TABLE legolists
MODIFY COLUMN date_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;