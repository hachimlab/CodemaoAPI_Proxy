// FXXK LTC NM$L
export async function onRequest(context) {
  const { request, params } = context;

  if (!params.path || params.path.length === 0) {
    return new Response(
      `<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <title>CodemaoAPI_CFProxy</title>
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
    <img src="https://s3.bmp.ovh/imgs/2024/10/25/7cd326afa68c3c6a.png"
         width="162" height="213.2" alt="言叶大魔王">
    <h2>早上好~ Sensei~</h2>
    <p><b>这是一个运行在 Cloudflare 上的 Codemao API 反向代理</b></p>
    <p>使用方法：把 <code>api.codemao.cn</code> 换成此域名即可</p>
    <p>不过，如果是在Cloudflare上部署，则不能在CoCo里使用，日记可以在README中找到链接</p>
    <p>公共服务，不记录请求内容</p>
    <a href="https://github.com/Wangs-official/CodemaoAPI_Proxy">
      GitHub 仓库（记得 Star）
    </a>
  </div>
</body>
</html>`,
      {
        status: 200,
        headers: {
          "Content-Type": "text/html; charset=utf-8",
        },
      }
    );
  }

  if (params.path.join("/") === "testcma") {
    return new Response(JSON.stringify({ status: "ok" }), {
      status: 200,
      headers: {
        "Content-Type": "application/json;charset=UTF-8",
      },
    });
  }

  const targetBase = "https://api.codemao.cn";
  const incomingUrl = new URL(request.url);
  const targetUrl = new URL(`${targetBase}/${params.path.join("/")}`);
  targetUrl.search = incomingUrl.search;

  const headers = new Headers();
  headers.set(
    "User-Agent",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36"
  );
  headers.set("Accept", "application/json, text/plain, */*");

  const cookie = request.headers.get("cookie");
  if (cookie) headers.set("Cookie", cookie);

  const authorization = request.headers.get("authorization");
  if (authorization) headers.set("Authorization", authorization);

  const contentType = request.headers.get("content-type");
  if (contentType) headers.set("Content-Type", contentType);

  let body = null;
  if (!["GET", "HEAD"].includes(request.method)) {
    body = await request.text();
  }

  const proxyRequest = new Request(targetUrl.toString(), {
    method: request.method,
    headers,
    body,
    redirect: "manual",
  });

  const response = await fetch(proxyRequest);

  const responseHeaders = new Headers();
  
  for (const [key, value] of response.headers.entries()) {
    const lowerKey = key.toLowerCase();
    if (!lowerKey.startsWith("cf-") && 
        lowerKey !== "server" && 
        lowerKey !== "via" &&
        lowerKey !== "nel" &&
        lowerKey !== "report-to" &&
        lowerKey !== "alt-svc") {
      responseHeaders.set(key, value);
    }
  }

  return new Response(response.body, {
    status: response.status,
    headers: responseHeaders,
  });
}