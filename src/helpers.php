<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2020/1/5
 * Time: 下午2:42
 */

function curl_post($url, $post_data, $header = [])
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置头部信息
    if (!empty($header)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

    try {
        //执行命令
        $data = curl_exec($curl);
    } catch (\Exception $e) {
        return ['status' => '0', 'msg' => $e->getMessage()];
    }

    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return $data;
}

function curl_get($url, $header = [])
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置头部信息
    if (!empty($header)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }

    try {
        //执行命令
        $data = curl_exec($curl);
    } catch (\Exception $e) {
        return ['status' => '0', 'msg' => $e->getMessage()];
    }

    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return $data;
}