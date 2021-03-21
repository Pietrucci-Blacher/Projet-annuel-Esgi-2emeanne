#include <common.h>

void printQr(const uint8_t qrcode[], char *qrcodeString) {
    //FILE *fp;
    char path[255];
    strcpy(path,"generated/qrcode/");
    strcat(path,qrcodeString);
    strcat(path,".bmp");

    bmp_img img;
	bmp_img_init_df (&img, 512, 512);

    int xQrcode, yQrcode;

    double scale = 512/(double)qrcodegen_getSize(qrcode);

    for (size_t y = 0; y < 512; y++)
    {
        for (size_t x = 0; x < 512; x++)
        {
            xQrcode = x/scale; //-> X/(512/longeur du qrcode de base) -> pour scale
            yQrcode = y/scale;
            qrcodegen_getModule(qrcode, xQrcode, yQrcode) ? bmp_pixel_init (&img.img_pixels[y][x], 0, 0, 0) : bmp_pixel_init (&img.img_pixels[y][x], 250, 250, 250);
        }
    }

	bmp_img_write (&img, path);
	bmp_img_free (&img);
}

void generateQrcode() {
    char qrCodeTxt[255];
    char timestamp[255];
    sprintf(timestamp,"%d",(int)time(NULL));
    strcpy(qrCodeTxt,idEntreprise);
    strcat(qrCodeTxt,timestamp);
    strcpy(infoParcel[10][actualIndex],qrCodeTxt);

	enum qrcodegen_Ecc errCorLvl = qrcodegen_Ecc_LOW;

	uint8_t qrcode[qrcodegen_BUFFER_LEN_MAX];
	uint8_t tempBuffer[qrcodegen_BUFFER_LEN_MAX];
	bool ok = qrcodegen_encodeText(qrCodeTxt, tempBuffer, qrcode, errCorLvl,
		qrcodegen_VERSION_MIN, qrcodegen_VERSION_MAX, qrcodegen_Mask_AUTO, true);
	if (ok)
		printQr(qrcode,qrCodeTxt);
}

void deleteQrcode(int index){
    char path[255];
    strcpy(path,"generated/qrcode/");
    strcat(path, infoParcel[10][index]);
    strcat(path,".bmp");

    remove(path);
}
