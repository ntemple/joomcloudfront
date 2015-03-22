This project aims to create a complete Amazon CloudFront implementation for Joomla!

The code as presented currently works, but needs improvement:

The way it uploads files to the S3 buckets can result in server timeouts on large sites. An AJAX and/or command line based solution is needed.

Since you cannot change file contents with CloudFront (you must instead create a new file with a different name), the system detects the change and does so. This works fine EXCEPT when using css with embedded paths (like to images).

Changing the css directory or the image directory results in broken relative links.

Optimizations can be done as well: two files with the same hash do not need to be stored twice, instead, a reference could be used.

The entire system needs documentation.

DISCUSSION GROUP AVAILABLE:
http://groups.google.com/group/cloudfront-for-joomla-