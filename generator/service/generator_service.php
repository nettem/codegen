<?php
require_once(dirname(__FILE__) . '/../lib/xml.php');
require_once(dirname(__FILE__) . '/generator_abstract.php');

/**
 * 코드 생성기
 *
 * @author nettem@nettem.co.kr
 */

class GeneratorService extends GeneratorAbstract {
    function __construct() {

    }

    private function genInfo($xml) {
        $parser = new XMLParser($xml);
        $parser->Parse();

        $domain_list = array();
        if(isset($parser->document->domains) == true) {
            foreach($parser->document->domains[0]->domain as $key => $domain) {
                $field_all_list = array();
                $field_pk_list = array();
                $field_list = array();

                $info = $domain->tagAttrs;

                foreach($domain->field as $key => $field) {
                    $field_info = $field->tagAttrs;

                    foreach($field->tagChildren as $value) {
                        $field_info[$value->tagName] = $value->tagData;
                    }

                    $field_all_list[] = $field_info;
                    if($field_info['pk'] == 'Y') {
                        $field_pk_list[] = $field_info;
                    } else {
                        $field_list[] = $field_info;
                    }
                }

                $info['field_all_list'] = $field_all_list;
                $info['field_pk_list'] = $field_pk_list;
                $info['field_list'] = $field_list;
                $domain_list[] = $info;
            }
        }

        $source_list = array();
        if(isset($parser->document->sources) == true) {
            if(isset($parser->document->sources[0]->source) == true) {
                foreach($parser->document->sources[0]->source as $key => $source) {
                    $source_list[] = array(
                        'tpl'=>$source->tagAttrs['tpl'],
                        'path'=>$source->tagAttrs['path'],
                        'file'=>$source->tagAttrs['file'],
                    );
                }
            }
        }

        return array(
            'domain_list'=>$domain_list,
            'source_list'=>$source_list,
            'static_list'=>$static_list,
        );
    }

    private function replace($domain, $value) {
        foreach($domain as $key => $v) {
            if(is_array($v) == false) {
                $value = str_replace('${'.$key.'}', $domain[$key], $value);
            }
        }

        return $value;
    }

    private function filePathSecurity($path) {
        //보안관련 설정
        $path = str_replace('..', '', $path);
        //인코딩(system의 인코디을 따라가야 함)
        $path = iconv("utf-8","CP949", $path);

        return $path;
    }

    function generator($domain_xml_path, $template_forder_path, $temp_forder_path) {
        //템플릿 path
        $template_path = realpath($template_forder_path);

        //tmp 폴더 정리
        if($temp_forder_path == '') $temp_forder_path = dirname(__FILE__) . '/../../tmp';
        if(file_exists($temp_forder_path) == true) rmdirRecursive($temp_forder_path);
        mkdirRecursive($temp_forder_path);
        $temp_forder_path = realpath($temp_forder_path);

        //도메인 파싱
        $domain_xml = file_get_contents($domain_xml_path);
        $gen_info = $this->genInfo($domain_xml);
        $domain_list = $gen_info['domain_list'];

        //소스 파싱
        $source_xml = file_get_contents($template_forder_path.'/source.xml');
        $gen_info = $this->genInfo($source_xml);
        $source_list = $gen_info['source_list'];

        //도메인별 코드 생성
        foreach($domain_list as $domain) {
            foreach($source_list as $value) {
                $path = $this->replace($domain, $value['path']);
                if(substr($path, 0, 1) != '/') $path = '/'.$path;
                $path = $this->filePathSecurity($path);

                $tpl = $value['tpl'];
                if(substr($tpl, 0, 1) != '/') $tpl = '/'.$tpl;
                $tpl = $template_path.$tpl;
                $tpl = $this->filePathSecurity($tpl);

                if(file_exists($tpl) == false) return $tpl.' 템플릿 파일이 없습니다.';

                $contents = getIncludeContents($tpl, $domain);
                $this->fileCreate($temp_forder_path.'/'.dirname($path), basename($path), $contents);
            }
        }
    }
}
?>