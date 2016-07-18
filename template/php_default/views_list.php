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
require_once('AestivatePHP/paging_partition.php');

require_once('module/<?php echo $domain['package']?>/service/<?php echo $domain['table_func_name']?>_service.php');

//service
$<?php echo $domain['table_func_name']?>_service = new <?php echo $domain['table_class_name']?>Service();

//조회
$list = $<?php echo $domain['table_func_name']?>_service->paging($paging);
?>
<!doctype html>
<html lang="ko">
<head>
<?='<?'?>php require ROOT_DIR . '/_inc_meta.html';?>
<title><?='<?'?>php echo $config['title']; ?></title>
<link href="/css/common.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div style="width:100%;margin:0 auto;">
    <div class="table">
        <table>
            <col style="width: 30px;">
<?php foreach($domain['field_list'] as $key => $value) { ?>
            <col style="width: <?php echo $value['LENGTH']?>px;">
<?php } ?>
            <col style="width: 150px;">
            <tr>
<?php foreach($domain['field_list'] as $key => $value) { ?>
                <th align="center"><span><?php echo $value['field_comment']?></span></th>
<?php } ?>

                <th align="center"><span>비고</span></th>
            </tr>

            <?='<?'?>php foreach($list as $i => $value) { ?>
            <tr bgcolor="<?='<?'?>php echo ($i%2)!=0?'#F9F9F9':'#FFFFFF'?>"
                onmouseover="this.style.background='#FFFF99'"
                onmouseout="this.style.background='<?='<?'?>php echo ($i%2)!=0?'#F9F9F9':'#FFFFFF'?>'">
<?php foreach($domain['field_list'] as $key => $value) { ?>
                <td align="center"><span><?='<?'?>php echo $value['<?php echo $value['field_func_name']?>'] ?></span></td>
<?php } ?>

                <td align="center">
                    ...
                </td>

            </tr>
            <?='<?'?>php } ?>

        </table>

        <?='<?'?>php if(count($list)==0) { ?>
        <table>
            <tr>
                <td align="center">검색결과가 없습니다.</td>
            </tr>
        </table>
        <?='<?'?>php } ?>

    </div>
</div>

</body>
</html>
