<?php header('HTTP/1.1 404'); die; ?>

[globals]
test=
num=123

; this is a comment
str1=abc defg h ijk

; here's another way of assigning strings:-
str2="abc"

multi=this \
is a\  
 string that spans \
several lines

; ordinary array
list=7,8,9

; array with named keys
hash[x]=1
hash[y]=2
hash[z]=3

mix="this",123.45,FALSE

const=NULL
os=PHP_OS

long="12345678901234567890"
huge=12345678901234567890

[routes]
GET /go=home
GET /404=app->page404
GET /inside/@series=func1
GET /cached=app->temp,5

[maps]
/map=map