<?php
//로그설정
error_reporting(E_ALL & ~(E_NOTICE));
ini_set ('display_errors', true);

require_once(dirname(__FILE__).'/generator/service/domain_sql_service.php');

$domain_sql_service = new DomainSqlService();

$sql = "
CREATE TABLE IF NOT EXISTS `board` (
  `seq` int(11) NOT NULL AUTO_INCREMENT,
  `write` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text,
  `register_date` datetime DEFAULT NULL,
  PRIMARY KEY (`seq`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
";
$xml = $domain_sql_service->mysqlToXml($sql);

echo $xml;
?>