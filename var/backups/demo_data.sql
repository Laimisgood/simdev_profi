INSERT INTO cscart_departments (department_id, lang_code, name, description, timestamp, status) VALUES (
    '1', 
    'ru', 
    'Дирекция', 
    '<p>Администраторская</p>', 
    '1672222168', 
    'A'
    );
INSERT INTO cscart_departments (department_id, lang_code, name, description, timestamp, status) VALUES (
    '2', 
    'ru', 
    'Бухгалтерия', 
    '<p><strong>тут</strong> работают <strong><em>бухгалтеры</em></strong></p>', 
    '1672222247', 
    'A'
    );
INSERT INTO cscart_departments (department_id, lang_code, name, description, timestamp, status) VALUES (
    '3', 
    'ru', 
    'Логистика', 
    '<p></p><p>совокупность организационно-управленческих и производственно-технологических процессов по эффективному обеспечению различных систем товарно-материальными ресурсами.</p><p></p>', 
    '1672222310', 
    'D'
    );
INSERT INTO cscart_departments (department_id, lang_code, name, description, timestamp, status) VALUES (
    '4', 
    'ru', 
    'Отдел безопасности', 
    '<p><strong><em>Охранники</em></strong></p>', 
    '1672222374', 
    'A'
    );

INSERT INTO cscart_department_lead (department_id, user_id) VALUES (
    '1', 
    '13'
    );
INSERT INTO cscart_department_lead (department_id, user_id) VALUES (
    '2', 
    '4'
    );
INSERT INTO cscart_department_lead (department_id, user_id) VALUES (
    '3', 
    '12'
    );
INSERT INTO cscart_department_lead (department_id, user_id) VALUES (
    '4', 
    '3'
    );

INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '1', 
    '7'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '1', 
    '12'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '2', 
    '8'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '2', 
    '10'
    );

INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '2', 
    '14'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '3', 
    '9'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '3', 
    '11'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '4', 
    '6'
    );
INSERT INTO cscart_department_staff (department_id, user_id) VALUES (
    '4', 
    '7'
    );   