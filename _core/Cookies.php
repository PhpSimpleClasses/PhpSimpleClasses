<?php

namespace _core;

class Cookies
{

    /**
     * Return a string of cookies
     *
     * Format cookies on array to use in header of http request and return cookies string
     *
     * @param array $cookiesArr Array with cookies (['foo' => 'bar', 'lorem' => 'ipsum'])
     * @return string
     **/
    public function toString(array $cookiesArr)
    {
        $str = '';
        foreach ($cookiesArr as $key => $val) {
            if ($key && $val) {
                $str .= "$key=$val;";
            }
        }
        return $str;
    }

    /**
     * Return array of cookies
     *
     * Format cookies on string to be array
     *
     * @param string $cookiesStr String with cookies ('foo=bar;lorem=ipsum;')
     * @return Array
     **/
    public function toArray(string $cookiesStr)
    {
        $xCookies = explode(';', $cookiesStr);
        $arr = [];
        foreach ($xCookies as $ck) {
            if ($ck) {
                $xck = explode('=', $ck);
                $name = $xck[0];
                array_splice($xck, 0, 1);
                $val = implode('=', $xck);
                if ($name && $val) {
                    $arr[$name] = $val;
                }
            }
        }
        return $arr;
    }

    /**
     * Return Array of cookies
     *
     * Format cookies from headers to be an array
     *
     * @param array $headers Array with headers (['Document-Type: text/html', 'Set-Cookie: a=test;...'])
     * @return array
     **/
    public function fromHeaders(array $headers)
    {
        $arr = [];
        foreach ($headers as $item) {
            $key = array_keys($item)[0];
            $val = $item[$key];
            if (strtolower($key) == 'set-cookie') {
                $xVal = explode(';', $val)[0];
                $xck = explode('=', $xVal);
                $ckName = $xck[0];
                array_splice($xck, 0, 1);
                $ckVal = implode('=', $xck);
                if ($ckName && $ckVal) {
                    $ckVal = trim($ckVal);
                    $arr[$ckName] = $ckVal;
                }
            }
        }
        return $arr;
    }

    /**
     * Return Array of cookies
     *
     * Format cookies from JSON to be an array
     * Json data must be in format [
     * {'name': 'myCookie', 
     * 'value': 'value1'},
     * 
     * {'name': 'otherCookie', 
     * 'value': 'value321'}
     * ]
     *
     * @param string $jsonPath JSON file path
     * @return array
     **/
    public function fromJson(string $jsonPath)
    {
        $arr = [];
        if (@$jsonArr = json_decode(file_get_contents($jsonPath), true)) {
            foreach ($jsonArr as $item) {
                $name = $item['name'];
                $val = $item['value'];
                if ($name && $val) {
                    $arr[$name] = $val;
                }
            }
        }
        return $arr;
    }

    /**
     * Update cookies in JSON
     *
     * Update Cookies in JSON to future use (important if host check for last cookies sent)
     *
     * @param array $newCookies Array of new cookies
     * @param string $jsonPath JSON file path
     **/
    public function updateJson(array $newCookies, string $jsonPath)
    {
        $newArr = [];
        if (@$newCookies && $arrJson = json_decode(file_get_contents($jsonPath), true)) {
            foreach ($arrJson as $item) {
                $name = $item['name'];
                $val = $item['value'];
                if ($newCookies[$name]) {
                    $newArr[] = [
                        'name' => $name,
                        'value' => $newCookies[$name]
                    ];
                    unset($newCookies[$name]);
                } else {
                    $newArr[] = [
                        'name' => $name,
                        'value' => $val
                    ];
                }
            }
            foreach ($newCookies as $key => $val) {
                $newArr[] = [
                    'name' => $key,
                    'value' => $val
                ];
            }
            file_put_contents($jsonPath, json_encode($newArr));
        }
    }
}
