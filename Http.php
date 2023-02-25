<?php

class Http
{
    /**
     * @purpose 获取请求数据
     * @param string $date 时间（格式：Y-m-d）
     * @return mixed|void
     */
    public function send(string $date)
    {
        $random = mt_rand() / mt_getrandmax();
        $data = self::request("http://www.szse.cn/api/report/ShowReport/data?SHOWTYPE=JSON&CATALOGID=SGT_SGTJYRB&txtDate=$date&random=$random");
        if ($data['code'] !== 200) {
            exit("获取数据失败，请重试！");
        }
        return json_decode($data['data'], false);
    }

    /**
     * @purpose http请求封装方法
     * @param string $url 请求地址
     * @param array|null $data 请求数据
     * @param string $method http方法
     * @param array $header 请求头
     * @return array
     */
    private static function request(string $url, array $data = null, string $method = 'GET', array $header = []): array
    {
        // 初始化句柄
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 设置超时为1分钟
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        // 请求方法
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } elseif ($method === 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } elseif ($method === 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        // 设置请求头
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        // 请求
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // 关闭
        curl_close($curl);

        if ($error) {
            throw new \RuntimeException($error);
        }

        return ['code' => $httpCode, 'data' => $response];
    }
}