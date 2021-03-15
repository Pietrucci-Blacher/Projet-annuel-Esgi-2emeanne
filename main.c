#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <winsock.h>
#include <mysql.h>

MYSQL mysql;
char infoParcel[11][255];


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

void makeQuery(){
}

void readExcel(){
    FILE* file;
    file = fopen("upload/new.csv","r");
    char character;
    int col = 0;
    int i =0;

    if(file){
        while(feof(file)==0){
            character = fgetc(file);
            if(character=='\n'){
                makeQuery();
                i=0;
                col=0;
            }else if (character == ';'){
                col +=1;
                i=0;
            }else{
                infoParcel[col][i] = character;
                //printf("%s\n",infoParcel[col]);
                i+=1;
            }
        }
    }
    fclose(file);
}

int main(int argc,char **argv)
{
    mysql_connection();
    return 0;
}
