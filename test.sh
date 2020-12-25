#!/bin/sh

OK='[OK]\t'
WARN='[WARN]\t'
ERROR='[ERROR]\t'
TOKEN='FkMODUFlWhg='
RUNNING=' __  ___ __  __  ______  ______  __  ______  ______
/\ \/ ___\ \/\ \/\  __ \/\  __ \/\ \/\  __ \/\  __ \\
\ \  /___/\ \_\ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \_\ \\
 \ \_\   \ \_____\ \_\ \ \ \_\ \ \ \_\ \_\ \_\ \___  \\
  \/_/    \/_____/\/_/\/_/\/_/\/_/\/_/\/_/\/_/\/___/\ \\
 __________________________________________________\_\ \\
/\______________________________________________________\\
\/______________________________________________________/'

assert() {
    exit_code="$?"
    [ "$exit_code" = 0 ] && ([ -z "$1" ] || echo "$OK$1") || (
    [ -z "$2" ] || echo "$ERROR$2 - exit code:$exit_code")
}

echo "$RUNNING"
echo "${WARN}creating tables from schema"
mysql -hdb4free.net -uyoussef_database -ppassword youssef_database < web/schema.sql
assert "created successfully" "your sql sucks"
echo "${WARN}posting http://localhost:80/register/"
curl -s -X POST http://localhost:80/register/ -d \
    '{
         "name": "Youssef",
         "email": "bananaboy@gmail.com",
         "password": "secure_password"
     }'
assert "success" "failed"
echo "${WARN}calling http://localhost:80/login/"
curl -s -X POST http://localhost:80/login/ -d \
    '{
         "email": "bananaboy@gmail.com",
         "password": "secure_password"
     }'
assert "success" "failed"
echo "${WARN}calling http://localhost:80/user"
curl -s -H "Authorization: $TOKEN" http://localhost:80/user
assert "success" "failed"
# echo "${WARN}calling http://localhost:80/user/"
# curl -s http://localhost:80/user/
# assert "success" "failed"
# echo "${WARN}calling http://localhost:80/user"
# curl -s http://localhost:80/user
# assert "success" "failed"
echo "${WARN}calling http://localhost:80/email_checker"
curl -s 'http://localhost:80/email_checker?email=test@exmaple.com'
assert "success" "failed"
echo "${WARN}calling http://localhost:80/departments"
curl -s -H "Authorization: $TOKEN" http://localhost:80/departments
assert "success" "failed"
