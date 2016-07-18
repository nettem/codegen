<?php
//로그설정
error_reporting(E_ALL & ~(E_NOTICE));
ini_set ('display_errors', true);

//generator service
require_once(dirname(__FILE__).'/generator/service/generator_service.php');
$generator_service = new GeneratorService();

//dsmake z colorcom -> php_dsmake 설정
$generator_service->generator(dirname(__FILE__).'/domain/test/board.xml',
                              dirname(__FILE__).'/template/php_default',
                              dirname(__FILE__).'/ztemp');

echo '생성완료';
?>