<?php

require_once(dirname(__FILE__) . '/../lib/utils.php');

/**
 *
 * @author nettem@nettem.co.kr
 */
class DomainSqlService {

    public function __construct() {

    }

    public function mysqlToXml($sql) {
        $sql = preg_split("/\n/", $sql);

        //sql 정재
        $temp = array();
        $temp_v = '';
        $is_table = 'N';
        foreach($sql as $key => $value) {
            $value = trim($value);
            if($value == '') continue;
            if(substr($value, 0, 2) == '--') continue;

            $s = preg_split('/ /', $value);
            if($s[0] == 'SET')  {
                continue;
            } else if($s[0] == 'CREATE')  {
                $is_table = 'Y';
                $temp[] = $value;
            } else if($is_table == 'Y' && $s[0] == ')')  {
                $is_table = 'N';
                $temp[] = $value;
            } else if($is_table == 'Y')  {
                $temp[] = $value;
            } else if ($s[0] == 'ALTER')  {
                $temp[] = $value;
            } else {
                $l_idx = count($temp)-1;
                $temp[$l_idx] .= ' '.$value;
            }
        }
        $sql = $temp;

        //코드 분석 시작
        $temp = array();
        $temp_table_name = '';
        foreach($sql as $key => $s_v) {
            $value = preg_split('/ /', $s_v);

            /////////////////////////////////////////
            //comment 공백 정제
            /////////////////////////////////////////
            $ta = array();
            $t = '';
            $is_first = false;
            $is_comment = false;
            foreach($value as $k => $v) {
                if($v == 'COMMENT') $is_comment == true;

                if($is_comment == true) {
                    if($is_first == false && strpos($v, "'") !== false) {
                        $t .= $v;
                        $is_first = true;
                        continue;
                    } else if($is_first == true && strpos($v, "'") === false) {
                        $t .= ' '.$v;
                        continue;
                    } else if($is_first == true && strpos($v, "'") !== false) {
                        $t .= ' '.$v;
                        $is_first = false;
                        $is_comment = false;

                        $v = $t;
                        $t = '';
                    }
                }

                $ta[] = $v;
            }
            $value = $ta;

            /////////////////////////////////////////
            //분석 시작
            /////////////////////////////////////////
            if($value[0] == 'CREATE')  {
                //테이블 생성 정보
                $temp_table_name = $value[count($value)-2];
                $temp_table_name = str_replace('`', '', $temp_table_name);

                $temp[$temp_table_name]['name'] = $temp_table_name;
            } else if($value[0] == 'ALTER')  {
                //ALTER 구분 분석
                $temp_table_name = $value[2];
                $temp_table_name = str_replace('`', '', $temp_table_name);

                if($value[4] != 'PRIMARY') continue;

                $pks = $value[6];
                $pks = str_replace('`', '', $pks);
                $pks = str_replace('(', '', $pks);
                $pks = str_replace(')', '', $pks);
                $pks = preg_split('/,/', $pks);

                foreach($pks as $pk_field_name) {
                    if($pk_field_name == '') continue;
                    $temp[$temp_table_name]['fields'][$pk_field_name]['is_pk'] = 'Y';
                    $temp[$temp_table_name]['pks'][$pk_field_name] = $temp[$temp_table_name]['fields'][$pk_field_name];
                }
            } else {
                if($temp_table_name == '') continue;

                if($value[0] == ')')  {
                    //테이블 comment와 인코딩 정보
                    $pos = strpos($s_v, 'COMMENT=');
                    if($pos !== false) {
                        $comment = substr($s_v, $pos+9);

                        $pos2 = strpos($comment, "'");
                        if($pos2 !== false) {
                            $comment = substr($comment, 0, $pos2);
                            $temp[$temp_table_name]['comment'] = $comment;
                        }
                    }

                    $temp_table_name = '';
                } else if($value[0] == 'PRIMARY')  {
                    //PK 정보
                    $pks = $value[2];
                    $pks = str_replace('`', '', $pks);
                    $pks = str_replace('(', '', $pks);
                    $pks = str_replace(')', '', $pks);
                    $pks = preg_split('/,/', $pks);

                    foreach($pks as $pk_field_name) {
                        if($pk_field_name == '') continue;
                        $temp[$temp_table_name]['fields'][$pk_field_name]['is_pk'] = 'Y';
                        $temp[$temp_table_name]['pks'][$pk_field_name] = $temp[$temp_table_name]['fields'][$pk_field_name];
                    }
                } else if($value[0] == 'KEY')  {
                    //index 정보는 무시
                    continue;
                } else {
                    //Field 정보
                    $name = $value[0];
                    $name = str_replace('`', '', $name);
                    $type = $value[1];
                    $type = substr($type, 0, strpos($type, '('));

                    $comment = '';
                    if($value[count($value)-2] == 'COMMENT') {
                        $comment = $value[count($value)-1];
                        $comment = str_replace("'", "", $comment);
                        $comment = str_replace(",", "", $comment);
                    }

                    $temp[$temp_table_name]['fields'][$name] = array(
                            'name'=>$name,
                            'type'=>$type,
                            'comment'=>$comment,
                    );
                }
            }
        }
        $sql = $temp;

        //xml 변환
        $xml_content = '';
        foreach($sql as $key => $value) {
            $space = '            ';

            $table_name = $value['name'];
            $table_class_name = GenUtils::toClass($value['name']);
            $table_comment = $value['comment'];
            $xml_content .= $space.'<domain table_name="'.$table_name.'" table_comment="'.$table_comment.'" table_class_name="'.$table_class_name.'" table_func_name="'.$table_name.'" package="'.$table_name.'">'."\n";

            foreach($value['fields'] as $field) {
                if($field['is_pk'] == 'Y') {
                    $xml_content .= $space.'    <field field_name="'.$field['name'].'" field_comment="'.$field['comment'].'" field_func_name="'.$field['name'].'" pk="'.$field['is_pk'].'"/>'."\n";
                } else {
                    $xml_content .= $space.'    <field field_name="'.$field['name'].'" field_comment="'.$field['comment'].'" field_func_name="'.$field['name'].'"/>'."\n";
                }
            }

            $xml_content .= $space.'</domain>'."\n";
            $xml_content .= "\n\n";
        }

        $xml_template = file_get_contents(dirname(__FILE__) . '/../template/xml_create_php.xml');
        $xml = str_replace('{domain}', $xml_content, $xml_template);

        return $xml;
    }
}
?>