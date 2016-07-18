<?php
$domain = $form_value;
/*
$domain['table_name'];          데이터베이스 Table
$domain['table_comment'];       설명
$domain['table_class_name'];    도메인 클래스 형식
$domain['table_func_name'];     도메인 변수 형식
$domain['package'];             패키지
$domain['package_path'];        패키지 path

field_all_list, field_pk_list, field_list
$domain['field_list']['field_name'];            데이터베이스 Column
$domain['field_list']['field_class_name'];      프로퍼티 클래스 형식 (set, get 함수등에서 사용)
$domain['field_list']['field_func_name'];       프로퍼티 변수 형식 (각종 변수에 사용)
*/
?>
<?='<?'?>php
require_once(dirname(__FILE__) . '/_inc/common.php');

//service
$<?php echo $domain['table_func_name']?>_service = new <?php echo $domain['table_class_name']?>Service();

$<?php echo $domain['table_func_name']?>_service->insert(array(
<?php foreach($domain['field_list'] as $key => $value) { ?>
    '<?php echo $value['field_func_name']?>'=>$_POST['<?php echo $value['field_func_name']?>'],
<?php } ?>
));

echo json_encode(array(
    'result'=>'Y',
));
?>
