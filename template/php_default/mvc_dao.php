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

$param_pk = '';
foreach($domain['field_pk_list'] as $k => $v) {
    if($k > 0) $param_pk .= ' ,';
    $param_pk .= '$'.$v['field_func_name'];
}
?>
<?='<?'?>php

/**
 *
 * <?php echo $domain['table_func_name']?> Dao
 *
 * @author nettem developer
 * @copyright nettem
 */
class <?php echo $domain['table_class_name']?>Dao extends DAO {

    function nextNumber() {
        //
    }

    /**
     *
     * 등록
     *
     */
    function insert($data) {
        $sql = "
             insert into <?php echo $domain['table_name']?> (
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
                        <?php echo $key==0?' ':',' ?> <?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>

<?php foreach($domain['field_list'] as $key => $value) { ?>
                        , <?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>
                    ) values (
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
                        <?php echo $key==0?' ':',' ?> ?
<?php } ?>

<?php foreach($domain['field_list'] as $key => $value) { ?>
                        , ?
<?php } ?>
                    )
        ";

        return $this->pdo->exec($sql, array(
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
            $data['<?php echo $value['field_name']?>'],
<?php } ?>

<?php foreach($domain['field_list'] as $key => $value) { ?>
            $data['<?php echo $value['field_name']?>'],
<?php } ?>
        ));
    }

    /**
     *
     * 수정
     *
     */
    function update($data) {
        $sql = "
         update <?php echo $domain['table_name']?><?php echo "\r\n"?>
<?php foreach($domain['field_list'] as $key => $value) { ?>
            <?php echo $key==0?'set':'    ,' ?> <?php echo $value['field_name']?> = ?
<?php } ?>

<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
          <?php echo $key==0?'where':'  and' ?> <?php echo $value['field_name']?> = ?
<?php } ?>
        ";

        return $this->pdo->exec($sql, array(
<?php foreach($domain['field_list'] as $key => $value) { ?>
            $data['<?php echo $value['field_name']?>'],
<?php } ?>

<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
            $data['<?php echo $value['field_name']?>'],
<?php } ?>
        ));
    }

    /**
     *
     * 삭제
     *
     */
    function delete(<?php echo $param_pk?>) {
        $sql = "
         delete
           from <?php echo $domain['table_name']?>

<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
          <?php echo $key==0?'where':'  and' ?> <?php echo $value['field_name']?> = ?
<?php } ?>
        ";
        return $this->pdo->exec($sql, array(
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
            $<?php echo $value['field_name']?>,
<?php } ?>
        ));
    }

    /**
     *
     * 한건 조회
     *
     */
    function find(<?php echo $param_pk?>) {
        $sql = "
         select
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
                <?php echo $key==0?' ':',' ?> <?php echo $domain['table_name']?>.<?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>

<?php foreach($domain['field_list'] as $key => $value) { ?>
                , <?php echo $domain['table_name']?>.<?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>

           from <?php echo $domain['table_name']?>


<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
          <?php echo $key==0?'where':'  and' ?> <?php echo $value['field_name']?> = ?
<?php } ?>
        ";

        return $this->pdo->queryForLine($sql, array(
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
            $<?php echo $value['field_name']?>,
<?php } ?>
        ));
    }

    /**
     *
     * 검색
     *
     */
    function paging(PagingPartition $pp) {
        $params = array();
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
        //$params[] = $<?php echo $value['field_name']?>;
<?php } ?>

        $sql .= "
         select
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
                <?php echo $key==0?' ':',' ?> <?php echo $domain['table_name']?>.<?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>

<?php foreach($domain['field_list'] as $key => $value) { ?>
                , <?php echo $domain['table_name']?>.<?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>


           from <?php echo $domain['table_name']?>

          where 1 = 1
        ";

        return $this->pdo->pagingForArray($sql, $pp, $params);
    }

    /**
     *
     * 검색
     *
     */
    function select(<?php echo $param_pk?>) {
        $params = array();
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
        $params[] = $<?php echo $value['field_name']?>;
<?php } ?>

        $sql = "
         select
<?php foreach($domain['field_pk_list'] as $key => $value) { ?>
                <?php echo $key==0?' ':',' ?> <?php echo $domain['table_name']?>.<?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>

<?php foreach($domain['field_list'] as $key => $value) { ?>
                , <?php echo $domain['table_name']?>.<?php echo $value['field_name']?><?php echo "\r\n"?>
<?php } ?>


           from <?php echo $domain['table_name']?>

          where 1 = 1
        ";

        return $this->pdo->queryForArray($sql, $params);
    }
}
?>