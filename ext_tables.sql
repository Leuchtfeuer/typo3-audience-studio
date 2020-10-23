#
# Table structure for table 'tx_marketingautomation_persona'
#
CREATE TABLE tx_marketingautomation_persona (
    tx_audience_studio_segments int(11) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_audience_studio_segment'
#
CREATE TABLE tx_audience_studio_segment (
    title varchar(255) DEFAULT '' NOT NULL,
    as_segment_id varchar(255) DEFAULT '' NOT NULL,
    items int(11) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_audience_studio_segment_mm'
#
CREATE TABLE tx_audience_studio_segment_persona_mm (
    uid_local int(11) DEFAULT '0' NOT NULL,
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    tablenames varchar(255) DEFAULT '' NOT NULL,
    fieldname varchar(255) DEFAULT '' NOT NULL,
    sorting int(11) DEFAULT '0' NOT NULL,
    sorting_foreign int(11) DEFAULT '0' NOT NULL,

    KEY uid_local_foreign (uid_local,uid_foreign),
    KEY uid_foreign_tablefield (uid_foreign,tablenames(40),fieldname(3),sorting_foreign)
);

#
# Table structure for table 'tx_audience_studio_user'
#
CREATE TABLE tx_audience_studio_user (
    tstamp int(10) DEFAULT '0' NOT NULL,
    crdate int(10) DEFAULT '0' NOT NULL,
    as_ku_id varchar(255) NOT NULL,
    segments mediumtext,
    PRIMARY KEY (as_ku_id)
);
