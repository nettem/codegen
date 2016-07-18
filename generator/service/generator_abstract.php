<?php
/**
 *
 *
 * @author nettem@nettem.co.kr
 */
class SmartySingleton {
    private static $instance;

    /**
     *
     */
    private function __construct() {

    }

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

abstract class GeneratorAbstract {
    protected function fileCreate($path, $file_name, $contents) {
        //인코딩(system의 인코디을 따라가야 함)
        $path = iconv("utf-8","CP949", $path);

        //인코딩(system의 인코디을 따라가야 함)
        $file_name = iconv("utf-8","CP949", $file_name);

        //폴더 생성
        mkdirRecursive($path);

        $file_path = $path.'/'.$file_name;

        if(file_exists($file_path) == true) {

            throw new Exception('파일생성을 하지 못했습니다. ('.$file_path.')');

        } else {
            //var_dump($file_path);
            //exit();
            file_put_contents($file_path, $contents);
        }
    }
}


/**
 * path의 모든 폴더 생성
 *
 * @param unknown_type $pathname
 * @param unknown_type $mode
 * @return unknown
 */
function mkdirRecursive($pathname, $mode = 0777) {
    is_dir(dirname($pathname)) || mkdirRecursive(dirname($pathname), $mode);
    return is_dir($pathname) || @mkdir($pathname, $mode);
}

/**
 * filename의 내용을 그대로 가져 옴
 *
 * $form_value는 filename 페이지에서 사용할 변수 설정
 *
 * @param unknown_type $filename
 * @return unknown
 */
function getIncludeContents($filename, $form_value = '') {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

/**
 * 하위 폴더와 파일 모두 삭제
 * @param $dir
 * @return unknown_type
 */
function rmdirRecursive($dir) {
    $dirs = dir($dir);
    while(false !== ($entry = $dirs->read())) {
        if(($entry != '.') && ($entry != '..')) {
            if(is_dir($dir.'/'.$entry)) {
                rmdirRecursive($dir.'/'.$entry);
            } else {
                @unlink($dir.'/'.$entry);
            }
        }
    }
    $dirs->close();
    @rmdir($dir);
}
?>