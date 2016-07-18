<?php
class GenUtils {
    /**
     * 클래스 형태로 변환
     * @param unknown $name
     * @return string|unknown
     */
    public static function toClass($name)
    {
        if( empty($name) )
        {
            return '';
        }
        else
        {
            $tmp = explode("_", $name);

            $value = '';

            for($i = 0; $i < count($tmp); $i++) {
                $tmp[$i] = strtolower($tmp[$i]);
                $tmp[$i] = strtoupper(substr($tmp[$i], 0, 1)).
                substr($tmp[$i], 1);

                $value .= $tmp[$i];
            }

            return $value;
        }
    }

    /**
     * 함수명 형태로 변환
     * @param $name
     * @return unknown_type
     */
    public static function toFunc($name)
    {
        if( empty($name) )
        {
            return '';
        }
        else
        {
            $tmp = explode("_", $name);

            $value = '';

            for($i = 0; $i < count($tmp); $i++) {
                $tmp[$i] = strtolower($tmp[$i]);

                if($i > 0) {
                    $tmp[$i] = strtoupper(substr($tmp[$i], 0, 1)).
                    substr($tmp[$i], 1);
                }

                $value .= $tmp[$i];
            }

            return $value;
        }
    }

    /**
     * 모두 소문자료 변환
     * @param $name
     * @return unknown_type
     */
    public static function toLower($name)
    {
        if( empty($name) )
        {
            return '';
        }
        else
        {
            $tmp = explode("_", $name);

            for($i = 0; $i < count($tmp); $i++) {
                $tmp[$i] = strtolower($tmp[$i]);
            }

            $value = implode("_", $tmp);

            return $value;
        }
    }

}
?>