<?php
/**
 *  @param [string] $[file] [文件路径]
 *  @param [int] $[rate] [下载速度]
 *  @param boole $[forceDownload] [文件名是否中文处理]
 */

function downFile($file,$rate=100,$forceDownload=true)
{
    
    if(!file_exists($file))
    {
        header("HTTP/1.1 404 Not Found");
        return false;
    }
    if(!is_readable($file)) {
        header("HTTP/1.1 404 Not Found");
        return false;
    }

    #读取文件的信息
    $fileStat = stat($file);
    $lastModified = $fileStat['mtime'];

    #拼成etag，防止文件发生修改
    $md5 = md5($fileStat['mtime'] . '=' . $fileStat['ino'] . '=' . $fileStat['size']);
    $etag = '"' . $md5 . '-' . crc32($md5) . '"';

    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified) {
        header("HTTP/1.1 304 Not Modified");
        return true;
    }

    if (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $lastModified) {
        header("HTTP/1.1 304 Not Modified");
        return true;
    }

    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
        header("HTTP/1.1 304 Not Modified");
        return true;
    }


    $fileSize = $fileStat['size'];
    $contentLength = $fileSize;//文件大小
    $isPartial = false;//是否断点续传
    $fancyName = basename($file);

    //计算断点续传的开始位置
    if (isset($_SERVER['HTTP_RANGE'])) {
        if (preg_match('/^bytes=(\d*)-(\d*)$/', $_SERVER['HTTP_RANGE'], $matches)) {
            $startPos = $matches[1];
            $endPos = $matches[2];

            if ($startPos == '' && $endPos == '') {
                return false;
            }

            if ($startPos == '') {
                $startPos = $fileSize - $endPos;
                $endPos = $fileSize - 1;
            } else if ($endPos == '') {
                $endPos = $fileSize - 1;
            }

            $startPos = $startPos < 0 ? 0 : $startPos;//开始位置
            $endPos = $endPos > $fileSize - 1 ? $fileSize - 1 : $endPos;//结束位置

            $length = $endPos - $startPos + 1;//剩余大小

            if ($length < 0) {
                return false;
            }

            $contentLength = $length;
            $isPartial = true;
        }
    }
   //断点续传 记录下次下载的位置
    if ($isPartial) {
        header('HTTP/1.1 206 Partial Content');
        header(sprintf('Content-Range:bytes %s-%s/%s', $startPos, $endPos, $fileSize));

    } else {
        header("HTTP/1.1 200 OK");
        $startPos = 0;
        $endPos = $contentLength - 1;
    }
    //设置header头
    header('Pragma: cache');
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $lastModified) . ' GMT');
    header("ETag: $etag");
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . $contentLength);
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    //对不同浏览器进行中文设置，避免下载导致文件名乱码
    if ($forceDownload) {
            //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . rawurlencode($fancyName) . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . rawurlencode($fancyName));
        } else {
            header('Content-Disposition: attachment; filename="' . $fancyName . '"');
        }
    }else
    {
        header("Content-Disposition: attachment; filename=" . $fancyName);
    }

    $bufferSize = 1024;//设置最小读取字节数 1kb
    //判断是否有设置下载速度
    if($rate > 0) 
    {
        $bufferSize = $rate * $bufferSize; //100*1024 下载速度最大为100KB/s
    }
    
    $bytesSent = 0;
    $outputTimeStart = 0.00;
    $fp = fopen($file, "rb");
    fseek($fp, $startPos);

    while ($bytesSent < $contentLength && !feof($fp) && connection_status() == 0) {
        $readBufferSize = $contentLength - $bytesSent < $bufferSize ? $contentLength - $bytesSent : $bufferSize;
        $buffer = fread($fp, $readBufferSize);
        echo $buffer;
        //输出缓冲
        if(ob_get_level()>0)
        {
            ob_flush();
        }
        flush();
        $bytesSent += $readBufferSize;
        sleep(1); //睡眠一秒 这里也就是限制下载速度的关键 一秒只读$readBufferSize字节
    }
    if($fp) fclose($fp); 
}
$file = @$_GET["name"];
if($file){
	$rate = 1000; //下载速度 单位 kb/s
	downFile($file,$rate,true);
}else{
	echo "no filename";
}

?>