#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <winsock.h>
#include <mysql.h>

MYSQL mysql;

int mysql_connection(){
    mysql_init(&mysql);
    if (!mysql_real_connect(&mysql, "eu-cdbr-west-03.cleardb.net", "bdd1b420797f42", "190eb870", "heroku_a4b01b2a0b88f60", 3306, NULL, 0)){
        // error
        printf("MYSQL CONNECT ERROR");
        return -1;
    } else {
        printf("MYSQL CONNECT SUCCESS");
        return 0;
    }
}

int main(int argc,char **argv)
{
    mysql_connection();
    return 0;
}
