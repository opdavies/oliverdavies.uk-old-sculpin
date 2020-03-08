---
title: Nginx Redirects With Query String Arguments
date: 2017-01-31
excerpt: How to redirect from an old domain to a new one, and also to redirect from the root example.com domain to the canonical www subdomain.
tags: [nginx]
---
This is an example of how my Nginx configuration looked to redirect from an old domain to a new one, and also to redirect from the root `example.com` domain to the canonical `www` subdomain.

```language-nginx
server {
  listen 80;

  server_name example.com;
  server_name my-old-domain.com;
  server_name www.my-old-domain.com;

  return 301 https://www.example.com$uri;
}
```

It also redirects the URI value, e.g. from `http://example.com/test` to `http://example.com/test`, but I noticed recently though that any the query string would be lost - e.g. `http://example.com/?test` would redirect to `http://www.example.com` and the `?test` would be dropped. The application that I built references images based on the query string, so I wanted these to be included within the redirect.

This was fixed by making a small change to my `return` statement.

Before:

```language-nginx
return 301 https://www.example.com$uri;
```

After:

```language-nginx
return 301 https://www.example.com$uri$is_args$args;
```

`$is_args` is an empty string if there are no arguments, or a `?` to signify the start of the query string. `$args` then adds the arguments (`$query_string` could also be used with the same result).

Here is an demo of it working on this website:

![](/images/blog/nginx-redirect-with-args.gif)

## Resources

- [Query string](https://en.wikipedia.org/wiki/Query_string)
- [Nginx ngx_http_core_module](http://nginx.org/en/docs/http/ngx_http_core_module.html)
