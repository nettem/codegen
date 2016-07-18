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

require_once('module/<?php echo $domain['package']?>/dao/<?php echo $domain['table_func_name']?>_dao.php');

/**
 *
 * <?php echo $domain['table_func_name']?> Service
 *
 * @author
 * @copyright
 */
class <?php echo $domain['table_class_name']?>Service {

    private $config;

    private $<?php echo $domain['table_func_name']?>_dao;

    public function __construct() {
        global $config;
        $this->config = $config;

        $this-><?php echo $domain['table_func_name']?>_dao = new <?php echo $domain['table_class_name']?>Dao();
    }

    public function insert($data) {
        $data['<?php echo $domain['table_func_name']?>_number'] = $this-><?php echo $domain['table_func_name']?>_dao->nextNumber();

        //등록
        $this-><?php echo $domain['table_func_name']?>_dao->insert($data);

        return $data['<?php echo $domain['table_func_name']?>_number'];
    }

    public function update($data) {
        //수정
        $this-><?php echo $domain['table_func_name']?>_dao->update($data);
    }

    public function delete(<?php echo $param_pk?>) {
        //삭제
        $this-><?php echo $domain['table_func_name']?>_dao->delete(<?php echo $param_pk?>);
    }

    public function find(<?php echo $param_pk?>) {
        return $this-><?php echo $domain['table_func_name']?>_dao->find(<?php echo $param_pk?>);
    }

    public function paging(PagingPartition $pp) {
        return $this-><?php echo $domain['table_func_name']?>_dao->paging($pp);
    }

    public function select(<?php echo $param_pk?>) {
        return $this-><?php echo $domain['table_func_name']?>_dao->select(<?php echo $param_pk?>);
    }
}
?>