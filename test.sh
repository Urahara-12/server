# variables assigned sstrings
OK='[OK]    '
WARN='[WARN]    '
ERROR='[ERROR]  '
TOKEN='HU0bAUtdXw4=' # authentication token that translates into user id after decryption
RUNNING=' __  ___ __  __  ______  ______  __  ______  ______
/\ \/ ___\ \/\ \/\  __ \/\  __ \/\ \/\  __ \/\  __ \
\ \  /___/\ \_\ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \_\ \
 \ \_\   \ \_____\ \_\ \ \ \_\ \ \ \_\ \_\ \_\ \___  \
  \/_/    \/_____/\/_/\/_/\/_/\/_/\/_/\/_/\/_/\/___/\ \
 __________________________________________________\_\ \
/\______________________________________________________\
\/______________________________________________________/'

assert() { # looks at the line that got executed if its successful or not , from stackoverflow, used for testing
    exit_code="$?"
    [ "$exit_code" = 0 ] && ([ -z "$1" ] || echo "$OK$1") || (
    [ -z "$2" ] || echo "$ERROR$2 - exit code:$exit_code")
}

echo "$RUNNING" # echos running el cute el fo2 de
echo "${WARN}creating tables from schema" 
#c:/xampp/mysql/bin/mysql.exe -hdb4free.net -uyoussef_database -ppassword youssef_database < web/schema.sql #connects to host and send my schema.sql
c:/xampp/mysql/bin/mysql.exe -hlocalhost -uroot app < web/schema.sql #connects to host and send my schema.sql
assert "created successfully" "your sql sucks" # 2 parameters, success and failure parameters, lel connection el fo2
echo "${WARN}posting http://localhost:80/register/" # -X means specify request type, -d is user data formatted in json
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
echo "${WARN}calling http://localhost:80/email_checker"
curl -s 'http://localhost:80/email_checker?email=test@exmaple.com'
assert "success" "failed"
echo "${WARN}calling http://localhost:80/departments"
curl -s -H "Authorization: $TOKEN" http://localhost:80/departments
assert "success" "failed"
