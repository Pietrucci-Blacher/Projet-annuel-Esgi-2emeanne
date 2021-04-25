package com.example.step2app;

import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Color;
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

import java.util.Arrays;

public class QRcodeMenu extends AppCompatActivity {

    private TableLayout parcelsContainer;
    private LinearLayout parcelContainer;
    private Button [] buttonScan = new Button[25];
    private TextView [] refTxt = new TextView[25];
    private TextView timeTxt,distTxt;
    String[] listParcel = new String[25];
    int nbColis;

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_q_rcode_menu);

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

        String idDeliver = getIntent().getStringExtra("idDeliver");
        this.parcelsContainer = findViewById(R.id.parcelContainer);
        this.distTxt = findViewById(R.id.distDelivery);
        this.timeTxt = findViewById(R.id.timeDelivery);

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
        try {
            JSONArray end = jsonObj.getJSONArray("end");
            JSONObject endParcel = end.getJSONObject(0);
            endRef = endParcel.getString("refQrcode");
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
            } catch (JSONException e) {
                e.printStackTrace();
            }
            createParcel(this.parcelContainer,parcelRef,parcelsContainer,i+1);

        }

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
                        Toast.makeText(QRcodeMenu.this, "ok", Toast.LENGTH_SHORT).show();
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