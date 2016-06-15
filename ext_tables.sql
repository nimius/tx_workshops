CREATE TABLE tx_workshops_domain_model_workshop (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    hidden tinyint(1) unsigned default '0' NOT NULL,

    type tinyint(1) unsigned default '0' NOT NULL,
    identifier varchar(255) default NULL,
    name varchar(255) default NULL,
    abstract text,
    description text,
    price decimal(5,2) unsigned NULL default NULL,

    dates int(11) unsigned default '0' NOT NULL,
    categories int(11) unsigned default '0' NOT NULL,
    related_workshops int(11) unsigned default '0' NOT NULL,
    images int(11) unsigned default '0' NOT NULL,
    files int(11) unsigned default '0' NOT NULL,

    internal_url int(11) unsigned default '0' NOT NULL,
    external_url varchar(255) default NULL,

    sorting int(11) default '0' NOT NULL,
    crdate int(11) default '0' NOT NULL,
    cruser_id int(11) default '0' NOT NULL,
    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource mediumtext,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY language (sys_language_uid,l10n_parent)
);

CREATE TABLE tx_workshops_domain_model_related_workshop (
    uid_local int(11) default '0' NOT NULL,
    uid_foreign int(11) default '0' NOT NULL,
    sorting int(11) default '0' NOT NULL,
    sorting_foreign int(11) default '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_workshops_domain_model_date (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,
    parent int(11) unsigned default '0' NOT NULL,
    hidden tinyint(1) unsigned default '0' NOT NULL,

    type tinyint(1) unsigned default '0' NOT NULL,
    dates int(11) unsigned default '0' NOT NULL,
    workshop int(11) unsigned default '0' NOT NULL,
    location int(11) unsigned default '0' NOT NULL,
    instructor int(11) unsigned default '0' NOT NULL,
    registrations int(11) unsigned default '0' NOT NULL,

    begin_at int(11) unsigned default '0' NOT NULL,
    end_at int(11) unsigned default '0' NOT NULL,
    registration_deadline_at int(11) unsigned default '0' NOT NULL,
    minimum_attendance_enabled tinyint(1) default '0' NOT NULL,
    minimum_attendance tinyint(4) default '0' NOT NULL,
    maximum_attendance_enabled tinyint(1) unsigned default '0' NOT NULL,
    maximum_attendance tinyint(4) unsigned default '0' NOT NULL,
    notes text,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY workshop (workshop),
    KEY location (location),
    KEY instructor (instructor)
);

CREATE TABLE tx_workshops_domain_model_location (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,

    name varchar(255) default NULL,
    address text,
    zip varchar(10) default NULL,
    city varchar(255) default NULL,
    country int(11) unsigned default '0' NOT NULL,
    latitude decimal(10,8) signed NULL default NULL,
    longitude decimal(11,8) signed NULL default NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

CREATE TABLE tx_workshops_domain_model_instructor (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,

    name varchar(255) default NULL,
    abstract text,
    images int(11) unsigned default '0' NOT NULL,
    email varchar(255) default NULL,
    profile_pid int(11) unsigned default NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);

CREATE TABLE tx_workshops_domain_model_registration (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned default '0' NOT NULL,

    workshop_date int(11) unsigned default '0' NOT NULL,
    frontend_user int(11) unsigned default '0' NOT NULL,
    language int(11) unsigned default '0' NOT NULL,

    company varchar(255) DEFAULT NULL,
    first_name varchar(255) DEFAULT NULL,
    last_name varchar(255) DEFAULT NULL,
    address varchar(255) DEFAULT NULL,
    zip varchar(10) DEFAULT NULL,
    city varchar(255) DEFAULT NULL,
    country varchar(255) DEFAULT NULL,
    email varchar(255) DEFAULT NULL,
    notes text,
    telephone varchar(20) DEFAULT NULL,
    additional_fields_string text,

    confirmation_sent_at int(11) unsigned default '0' NOT NULL,
    crdate int(11) default '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY frontend_user (frontend_user),
    KEY workshop_date (workshop_date)
);

CREATE TABLE sys_category (
    tx_workshops_detail_pid int(11) unsigned default '0' NOT NULL,
    
    KEY tx_workshops_detail_pid(tx_workshops_detail_pid)
);