<?php

namespace _core;

class HTTP
{

    public $cookies;

    public function __construct()
    {
        $this->cookies = new Cookies;
    }

    /**
     * Make a HTTP request
     *
     * Returns a array with url, headers, body, finalUrl, httpCode
     *
     * @param string $url URL to request
     * @param string $method Method of request (GET, POST, DELETE, PUT, OPTIONS)
     * @param string $postData String to post with your request, if you want to post like formdata with key and 
     * value use "http_build_query" to format array like "foo=bar&foo2=bar2"
     * @param array $headers Array with headers to set in request (['User-Agent: WebTest', 'Cookie: foo=bar'])
     * @param bool $returnBody Set false to don't return body of request
     * @param string $cookieJar Netscape cookies file to read and save if needed 
     * @param int $timeout Specify maximum time of execution (0 = unlimited)
     * @return array [url => string, headers => array, body => string, finalUrl => string, httpCode => int]
     **/
    public function request(string $url, string $method = 'GET', string $postData = '', array $headers = [], bool $returnBody = true, $cookieJar = '', int $timeout = 0)
    {
        if (strpos($url, '//') === false) $url = "https://$url";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_NOBODY, (!$returnBody));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($cookieJar) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        }
        if ($postData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $res = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $body = $res;
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $headers = [];
        if ($headerSize) {
            $headerX = explode("\r\n", substr($res, 0, $headerSize));
            foreach ($headerX as $header) {
                $x = explode(':', $header);
                $name = trim($x[0]);
                $val = trim($x[1]);
                if ($name && $val) $headers[] = [$name => $val];
            }
            $body = substr($res, $headerSize);
        }

        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        return compact('url', 'headers', 'body', 'finalUrl', 'httpCode');
    }

    /**
     * Download a file
     *
     * Download a file with specified params
     *
     * @param string $url The file URL
     * @param string $file Where to save the file
     * @param array $headers Array of headers if needed to access the file
     * @param string $cookiesJar Netscape cookies file to read and save if needed 
     * @return int Status Code
     **/
    public function downloadFile(string $url, string $file, array $headers, string $cookieJar)
    {
        $fp = fopen($file, 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($cookieJar) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
        }
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        return $httpCode;
    }
}
