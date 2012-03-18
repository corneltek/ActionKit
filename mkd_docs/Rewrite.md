Phifty Web Framework
====================

Template Export Vars
--------------------

Env scope:

    {{ Env.request }}
    {{ Env.get }}
    {{ Env.post }}
    {{ Env.session }}
    {{ Env.globals }}
    {{ Env.cookie }}
    {{ Env.server }}
    {{ Env.env }}

AppKernel (Phifty webapp object):
    
    {{ Kernel }}


Rewrite Configuration
---------------------

Lighttpd,

    $HTTP["host"] == "phifty.local" {
        server.document-root = "/Users/c9s/git/lart/phifty/webroot" 
        # url.rewrite-if-not-file = ....
        url.rewrite-once = ( 
            "^/.*\.(php|css|html|htm|pdf|png|gif|jpe?g|js)$" => "$0",
            "^/(.*)" => "/index.php/$1",
        )
    }

Apache rewrite rule

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-s
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [NC,L]

