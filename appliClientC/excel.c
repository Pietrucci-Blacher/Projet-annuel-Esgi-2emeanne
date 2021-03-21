#include <common.h>

void sendExcel(){
    sendExcelFTP();

    remove("generated/excel/new.csv");
    for (int i = 0 ; i<25; i++){
        for(int j = 0; j<11; j++){
            strcpy(infoParcel[j][i],"");

        }
    }

    actualIndex = -1;
    changeScreen(GTK_BUTTON(btn[0]),(gpointer)5);
}

void checkExcel(){
    FILE* file;
    file = fopen("generated/excel/new.csv","r");
    char character;
    int col = 0;
    int row = 0;
    int i =0;
    if(file){

        while(feof(file)==0){
            character = fgetc(file);
            if(character=='\n'){
                row +=1;
                //printf("row");
                i=0;
                col=0;
            }else if (character == ';'){
                col +=1;
                //printf("col");
                i=0;
            }else{
                infoParcel[col][row][i] = character;
                //printf("%s\n",infoParcel[col][row]);
                i+=1;
            }
        }
        actualIndex = row-1;
    }
    fclose(file);
}

void dataToExcell(){
    FILE *fp;

    fp = fopen("generated/excel/new.csv","w");

    for (int i = 0 ; i<25; i++){
        for(int j = 0; j<11; j++){
            if(j==0 && (strcmp(infoParcel[j][i],"")==0)){
               break;
            }
            if(j == 10){
                fprintf(fp,"%s\n",infoParcel[j][i]);
            }else{
                fprintf(fp,"%s;",infoParcel[j][i]);
            }
        }
    }

    fclose(fp);
}
