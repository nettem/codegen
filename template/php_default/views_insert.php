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
?>
<!doctype html>
<html lang="ko">
<head>
<?='<?'?>php require ROOT_DIR . '/_inc_meta.html';?>
<title><?='<?'?>php echo $config['title']; ?></title>
<link href="/css/common.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
/**
 * 초기화
 */
$(function() {
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
    $('#<?php echo $value['field_func_name']?>').focus();
<?php } ?>
});

/**
 * form submit
 */
function formSubmit() {

}
</script>
</head>
<body>

<div style="width:98%;margin:0 auto;">
    <form id="<?php echo $domain['table_func_name']?>_form" name="<?php echo $domain['table_func_name']?>_form" method="post" action="./insert_submit.php" onsubmit="formSubmit(); return false;">
        <div class="table">
            <div class="table_title">
                등록
            </div>
            <table>
                <col style="width: 140px;">
                <col style="width: px;">
                <tbody>
<?php foreach($domain['field_list'] as $key => $value) { ?>
                    <tr>
                        <th><?php echo $value['field_comment']?></th>
                        <td>
                            <div class="item">
                                <input type="text" id="<?php echo $value['field_func_name']?>" name="<?php echo $value['field_func_name']?>" class="input"/>
                            </div>
                        </td>
                    </tr>
<?php } ?>
                 </tbody>
            </table>
        </div>

        <div style="text-align: center;padding-top:5px;">
            <input type="submit" value="저장" />
        </div>
    </form>
</div>
</body>
</html>
