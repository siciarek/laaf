import sys
import CGIHTTPServer
import BaseHTTPServer

PORT=int(sys.argv[1])

class Handler(CGIHTTPServer.CGIHTTPRequestHandler):
   cgi_directories = ["/web"]

httpd = BaseHTTPServer.HTTPServer(("", PORT), Handler)
httpd.serve_forever()
