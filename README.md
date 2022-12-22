# rss-bridge-collection
My custom RSS bridges for [RSS-Bridge](https://github.com/RSS-Bridge/rss-bridge). 
[Docs](https://rss-bridge.github.io/rss-bridge/index.html)

* `GesetzeImInternetBridge.php` 
* `GithubReleaseBridge.php`  Github releases and tags already have atom feeds but I added a search filter.  
* `TraktBridge.php` 
* `TraktCommentsBridge.php` 

## HTTP Headers

Use this for HTTP headers in Apache/nginx: 

``` 
Permissions-Policy: geolocation=(), microphone=(), camera=(), magnetometer=(), gyroscope=(), vibrate=(), payment=(), notifications=(), push=(), speaker=()
Referrer-Policy: strict-origin-when-cross-origin
Access-Control-Allow-Origin: *
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
Content-Security-Policy: default-src https: 'unsafe-inline'
``` 
