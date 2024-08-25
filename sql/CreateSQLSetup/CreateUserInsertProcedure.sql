-- For MySQL syntax
DELIMITER //
CREATE PROCEDURE insert_into_user_tables(
    IN p_username varchar(255),
    IN p_email varchar(255),
    IN p_pwd varchar(255),
    IN p_name varchar(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
    
    START TRANSACTION;

    INSERT INTO users (username, email, pwd)
        VALUES (p_username, p_email, p_pwd);

    SET @last_user_id = LAST_INSERT_ID();

    INSERT INTO user_data (user_id, name)
        VALUES (@last_user_id, p_name);

    COMMIT;
END //
DELIMITER ;

-- To Run
--CALL insert_into_user_tables(p_username, p_email, p_pwd, p_name);