CREATE TABLE IF NOT EXISTS cscart_departments (
    department_id INT(11) unsigned NOT NULL AUTO_INCREMENT,
    lang_code char(2) NOT NULL DEFAULT '',
    name varchar(255) NOT NULL DEFAULT '',
    description text NULL,
    timestamp INT(11) unsigned NOT NULL DEFAULT '0',
    status varchar(1) NOT NULL DEFAULT 'A',
    PRIMARY KEY (department_id, lang_code)
) ENGINE=INNODB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS cscart_department_lead (
    department_id INT(11) unsigned NOT NULL DEFAULT '0',
    user_id INT(11) unsigned NOT NULL DEFAULT '0',
    KEY (user_id),
    PRIMARY KEY (department_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS cscart_department_staff (
    department_id INT(11) unsigned NOT NULL DEFAULT '0',
    user_id INT(11) unsigned NOT NULL DEFAULT '0',
    KEY (department_id),
    KEY (user_id),
    PRIMARY KEY (department_id, user_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8