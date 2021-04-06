#include <dirent.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <winsock.h>
#include <mysql.h>
#include <math.h>
#include <time.h>

MYSQL mysql;
char infoParcel[11][255];

void readExcel();

void mysql_connection(){
    mysql_init(&mysql);
    if (!mysql_real_connect(&mysql, "eu-cdbr-west-03.cleardb.net", "bdd1b420797f42", "190eb870", "heroku_a4b01b2a0b88f60", 3306, NULL, 0)){
        // error
        printf("MYSQL CONNECT ERROR");
        mysql_close(&mysql);
    } else {
        printf("MYSQL CONNECT SUCCESS");
        readExcel();
    }
}

void displayArray(){
    printf("\n");
    for (int i = 0; i<11; i++){
        printf(" %s ", infoParcel[i]);
    }
}

void emptyArray(){
    for (int i = 0; i<11; i++){
        memset(infoParcel[i],0,sizeof(infoParcel[i]));
    }

}

const char* calculateDate(){
    int skip;

    if(strcmp(infoParcel[2],"standard")==0){
        skip = 5;
    }else{
        skip = 2;
    }

    time_t t = time(NULL);
    struct tm tm = *localtime(&t);

    tm.tm_mday += skip;
    mktime(&tm);

    char *buffer=malloc(sizeof(char)*30);
    strftime(buffer, 30, "%Y-%m-%d", &tm);
    printf("\nDate : %s",buffer);
    return buffer;
}

double calculatePrice(){
    double weight[10]={0.5,1,2,3,5,7,10,15,30};
    double parcelWeight;
    char strParcelWeight[255];
    int indexWeight;
    double price=0;

    parcelWeight=atof(infoParcel[1]);

    for(int i = 0;i<10;i++){
        if(parcelWeight <= weight[i]){
            indexWeight = i;
            break;
        }else if(parcelWeight > weight[8] ){
            indexWeight = 9;
            break;
        }
    }

    char query[1000];
    strcpy(query,"SELECT ");
    if(strcmp(infoParcel[2],"standard")==0){
        strcat(query,"prixStandard FROM tarifcolis WHERE poidsMax = ");
    }else{
        strcat(query,"prixExpress FROM tarifcolis WHERE poidsMax = ");
    }

    if(indexWeight == 0){
        sprintf(strParcelWeight, "%.1lf", weight[indexWeight]);
    }else if(indexWeight < 9){
        sprintf(strParcelWeight, "%.0lf", weight[indexWeight]);
    }else{
        strcpy(strParcelWeight,"31");
    }

    strcat(query,"\"");
    strcat(query,strParcelWeight);
    strcat(query,"\"");
    strcat(query," ORDER BY date DESC;");
    printf("\n\n%s",query);
    if (mysql_query(&mysql, query)) {
        printf("\nRETRIEVE DATA ERROR");
    }else{
        printf("\nRETRIEVE DATA SUCCESS");
        MYSQL_RES *result = mysql_store_result(&mysql);
        if(result == NULL){
            fprintf(stderr, "%s\n", mysql_error(&mysql));
            mysql_free_result(result);
        }else{
            MYSQL_ROW row;
            row = mysql_fetch_row(result);
            if(indexWeight != 9){
                price = atof(row[0]);
            }else{
                price = ceil(parcelWeight/20)*atof(row[0]);
            }
            printf("\nPrice : %lf",price);
            mysql_free_result(result);
            return price;
        }
    }
    return price;
}

void queryParcel(int idClient){
    char idClientStr[50];
    char query[1000];
    char price[10];
    sprintf(idClientStr, "%d", idClient);

    strcpy(query,"INSERT INTO COLIS(entreprise,poids,modeLivraison,refQrcode,status,client,prix,date) VALUES (");
    for(int i = 0; i<3; i++){
        strcat(query,"\"");
        strcat(query,infoParcel[i]);
        strcat(query,"\"");
        strcat(query,",");
    }

    strcat(query,"\"");
    strcat(query,infoParcel[10]);
    strcat(query,"\"");
    strcat(query,",");
    strcat(query,"\"En attente de récupération par le livreur\"");
    strcat(query,",");
    strcat(query,idClientStr);
    strcat(query,",");
    sprintf(price, "%.2lf", calculatePrice());
    strcat(query,price);
    strcat(query,",");
    strcat(query,"\"");
    strcat(query,calculateDate());
    strcat(query,"\"");
    strcat(query,");");

    printf("\n\n%s",query);
    if (mysql_query(&mysql, query)) {
        printf("\nINSERT ERROR");
    }else{
        printf("\nINSERT SUCCESS");
    }
}

void queryClient(){
    int userId;
    char query[1000];
    strcpy(query,"INSERT INTO CLIENT(nom,prenom,adresse,ville,codePostal,info,numPhone,status) VALUES (");
    for(int i = 3; i<11;i++){
        if(i == 10){
            strcat(query,"\"client\"");
            strcat(query,");");
        }else{
            strcat(query,"\"");
            strcat(query,infoParcel[i]);
            strcat(query,"\"");
            strcat(query,",");
        }
    }

    printf("\n\n----------------------------------------------");
    printf("\n\n%s",query);

    if (mysql_query(&mysql, query)) {
        printf("\nINSERT ERROR");
    }else{
        printf("\nINSERT SUCCESS");
        userId = mysql_insert_id(&mysql);
        queryParcel(userId);
    }
}

void readExcel(){
    DIR *d;
    struct dirent *dir;
    char path[255];
    char movePath[255];
    FILE* file;

    strcpy(path,"excel/upload/");
    strcpy(movePath,"excel/processed/");
    char character;
    int col = 0;
    int i =0;

    d = opendir("excel/upload");
    if(d){

        while ((dir = readdir(d)) != NULL)
        {
            if(strcmp(dir->d_name,".") != 0 && strcmp(dir->d_name,"..") != 0){
                //printf("-%s-",dir->d_name);
                strcat(path,dir->d_name);
                strcat(movePath,dir->d_name);
                file = fopen(path,"r");
                if(file){
                    while(feof(file)==0){
                        character = fgetc(file);
                        if(character=='\n'){
                            //displayArray();
                            queryClient();
                            emptyArray();
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
                    fclose(file);
                    rename(path,movePath);
                }
            }
        }
        closedir(d);
    }
    mysql_close(&mysql);
}

int main(int argc,char **argv)
{
    mysql_connection();
    return 0;
}
