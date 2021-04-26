package com.example.step2app;

import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.Arrays;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class QRcodeMenu extends AppCompatActivity {

    private TableLayout parcelsContainer;
    private LinearLayout parcelContainer;
    private Button btnVal,btnDel;
    private Button [] buttonScan = new Button[25];
    private TextView [] refTxt = new TextView[25];
    private TextView timeTxt,distTxt;
    String[] listParcel = new String[25];
    int nbColis,nbColisScan;

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_q_rcode_menu);

        String firstname = getIntent().getStringExtra("prenom");
        String lastname = getIntent().getStringExtra("nom");
        String zoneGeo = getIntent().getStringExtra("zoneGeo");
        String ptac = getIntent().getStringExtra("poidsVehicule");
        String idDeposit = getIntent().getStringExtra("idDepot");
        String idDeliver = getIntent().getStringExtra("idLivreur");

        JSONObject jsonObj = null;
        String distance = null;
        int time = 0;
        try {
            jsonObj = new JSONObject(getIntent().getStringExtra("delivery"));
            time = Integer.parseInt(jsonObj.getString("temps"));
            distance = jsonObj.getString("distance");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        this.parcelsContainer = findViewById(R.id.parcelContainer);
        this.distTxt = findViewById(R.id.distDelivery);
        this.timeTxt = findViewById(R.id.timeDelivery);
        this.btnVal = findViewById(R.id.btnVal);
        this.btnDel = findViewById(R.id.btnDel);
        nbColisScan = 0;

        distTxt.setText("Distance : " + distance + " km");
        int hours = time / 3600;
        int minutes = (time % 3600) / 60;
        timeTxt.setText("Temps : "+hours+"h"+minutes);

        try {
            nbColis = Integer.parseInt(jsonObj.getString("nbColis"));
        } catch (JSONException e) {
            e.printStackTrace();
        }

        String endRef = null;
        String parcelsId= "";
        try {
            JSONArray end = jsonObj.getJSONArray("end");
            JSONObject endParcel = end.getJSONObject(0);
            endRef = endParcel.getString("refQrcode");
            parcelsId = endParcel.getString("idColis");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        createParcel(this.parcelContainer,endRef,parcelsContainer,0);

        for (int i = 0; i < (nbColis - 1); i++) {

            String parcelRef = null;
            try {
                JSONArray parcelArray = jsonObj.getJSONArray("colis");
                JSONObject parcel = parcelArray.getJSONObject(i);
                parcelRef = parcel.getString("refQrcode");
                parcelsId += "."+parcel.getString("id");
            } catch (JSONException e) {
                e.printStackTrace();
            }
            createParcel(this.parcelContainer,parcelRef,parcelsContainer,i+1);

        }

        btnDel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent menuInt = new Intent(QRcodeMenu.this, MenuActivity.class);
                menuInt.putExtra("idLivreur",idDeliver);
                menuInt.putExtra("idDepot",idDeposit);
                menuInt.putExtra("prenom",firstname);
                menuInt.putExtra("nom",lastname);
                menuInt.putExtra("poidsVehicule",ptac);
                menuInt.putExtra("zoneGeo",zoneGeo);
                startActivity(menuInt);
            }
        });

        String finalParcelsId = parcelsId;
        btnVal.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (nbColis == nbColisScan){
                    Toast.makeText(QRcodeMenu.this, finalParcelsId, Toast.LENGTH_SHORT).show();

                    OkHttpClient client = new OkHttpClient();
                    String url = "https://pa2021-esgi.herokuapp.com/androidApp/affectDelivery.php";
                    RequestBody formBody = new FormBody.Builder().add("idDeliver", idDeliver).add("parcelsId", finalParcelsId).build();
                    Request request = new Request.Builder().url(url).post(formBody).build();

                    client.newCall(request).enqueue(new Callback() {
                        @Override
                        public void onFailure(Call call, IOException e) {
                            e.printStackTrace();
                        }
                        @Override
                        public void onResponse(Call call, Response response) throws IOException {
                            if (response.isSuccessful()) {
                                final String myResponse = response.body().string();

                                QRcodeMenu.this.runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        if(myResponse.equals("success")){
                                            Toast.makeText(QRcodeMenu.this,"OK",Toast.LENGTH_SHORT).show();
                                        }else{
                                            Toast.makeText(QRcodeMenu.this,"Une erreur est survenue",Toast.LENGTH_SHORT).show();
                                        }
                                    }
                                });
                            }
                        }
                    });
                }else{
                    Toast.makeText(QRcodeMenu.this, "Il vous manque des colis à scanner", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    private void btnProperties(int index){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT);
        params.weight = 0.4f;
        buttonScan[index].setText("Scanner");
        buttonScan[index].setBackgroundTintList(getResources().getColorStateList(R.color.upGold));
        buttonScan[index].setLayoutParams(params);
    }

    private void refProperties( String txtContent,int index){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT);
        params.weight = 0.6f;
        refTxt[index].setGravity(Gravity.CENTER);
        refTxt[index].setTextSize(14);
        refTxt[index].setText(txtContent);
        refTxt[index].setTextColor(Color.parseColor("#FFFFFF"));
        refTxt[index].setLayoutParams(params);
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    private void createParcel(LinearLayout parcel, String ref,TableLayout container,int index){
        parcel = new LinearLayout(this);
        buttonScan[index] = new Button(this);
        btnProperties(index);
        refTxt[index] = new TextView(this);
        refProperties(ref,index);
        parcel.addView(refTxt[index]);
        parcel.addView(buttonScan[index]);
        container.addView(parcel);
        listParcel[index]=ref;

        buttonScan[index].setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                IntentIntegrator qrcodeScanner = new IntentIntegrator(QRcodeMenu.this);
                qrcodeScanner.setPrompt("Volume + pour mettre le flash");
                qrcodeScanner.setBeepEnabled(false);
                qrcodeScanner.setOrientationLocked(true);
                qrcodeScanner.setCaptureActivity(Capture.class);
                Intent scannerInt = qrcodeScanner.createScanIntent();
                startActivityForResult(scannerInt,index);
            }
        });
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        for (int i = 0; i<nbColis;i++){
            if (requestCode == i) {
                if (resultCode == RESULT_OK) {
                    String contents = data.getStringExtra("SCAN_RESULT");
                    if (contents.equals(listParcel[requestCode])){
                        nbColisScan+=1;
                        buttonScan[i].setVisibility(View.GONE);
                        refTxt[i].setTextColor(Color.GREEN);
                        refTxt[i].setPadding(0,18,0,18);
                    }else{
                        Toast.makeText(QRcodeMenu.this, "Mauvais qrcode veuillez réessayer", Toast.LENGTH_SHORT).show();
                    }
                } else if (resultCode == RESULT_CANCELED) {
                    Toast.makeText(QRcodeMenu.this, "Erreur, veuillez réessayer", Toast.LENGTH_SHORT).show();
                }
            }
        }
    }
}