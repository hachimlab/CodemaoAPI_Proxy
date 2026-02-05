<!--FXXK LTC NM$L-->
<?php
$targetBase = "https://api.codemao.cn";
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

if (empty($path)) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <title>CodemaoAPI_Proxy</title>
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      background: #f7f7f7;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    }
  </style>
</head>
<body>
  <div style="text-align:center;">
    <img src="https://s3.bmp.ovh/2026/02/05/InIgrvie.png" alt="酱酱">
    <h2>早上好~ Sensei~</h2>
    <p><b>这是一个 Codemao API 反向代理</b></p>
    <p>使用方法：把 <code>api.codemao.cn</code> 换成此域名即可</p>
    <p>公共服务，不记录请求内容</p>
    <a href="https://github.com/Wangs-official/CodemaoAPI_Proxy">
      GitHub 仓库（记得 Star）
    </a>
  </div>
</body>
</html>';
    exit;
}

if ($path === 'testcma') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'ok']);
    exit;
}

$targetUrl = $targetBase . '/' . $path;
if (!empty($_SERVER['QUERY_STRING'])) {
    $targetUrl .= '?' . $_SERVER['QUERY_STRING'];
}

$headers = [
    "User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'),
    "Accept: application/json, text/plain, */*"
];

if (!empty($_SERVER['HTTP_COOKIE'])) {
    $headers[] = "Cookie: " . $_SERVER['HTTP_COOKIE'];
}
if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers[] = "Authorization: " . $_SERVER['HTTP_AUTHORIZATION'];
}
if (!empty($_SERVER['CONTENT_TYPE'])) {
    $headers[] = "Content-Type: " . $_SERVER['CONTENT_TYPE'];
}

$method = $_SERVER['REQUEST_METHOD'];
$body = null;
if (!in_array($method, ['GET', 'HEAD'])) {
    $body = file_get_contents('php://input');
}

$ch = curl_init($targetUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
if ($body !== null) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);

$response = curl_exec($ch);
if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
if ($contentType) {
    header("Content-Type: $contentType");
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
http_response_code($httpCode);
echo $response;
curl_close($ch);