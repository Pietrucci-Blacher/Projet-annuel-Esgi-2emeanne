package com.example.step2app;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ListAdapter;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class MenuActivity extends AppCompatActivity {

    private Button disconnectBtn,paiementBtn,deliveryBtn;
    private TextView welcomeTxt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);

        SharedPreferences prefs = getSharedPreferences("valeurs",MODE_PRIVATE);

        String firstname = prefs.getString("prenom",null);
        String lastname = prefs.getString("nom",null);
        String zoneGeo = prefs.getString("zoneGeo",null);
        String ptac = prefs.getString("poidsVehicule",null);
        String idDeposit = prefs.getString("idDepot",null);
        String idDeliver = prefs.getString("idLivreur",null);

        this.welcomeTxt =findViewById(R.id.welcomeTxt);
        welcomeTxt.setText("Bienvenue "+firstname+" "+lastname+idDeliver);

        this.disconnectBtn = findViewById(R.id.disconnectBtn);
        this.deliveryBtn = findViewById(R.id.deliveryBtn);

        this.deliveryBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Calendar rightNow = Calendar.getInstance();
                int hour = rightNow.get(Calendar.HOUR_OF_DAY);
                int minHour = 9;
                int maxHour = 30;
                if( hour< minHour || hour > maxHour){
                    Toast.makeText(MenuActivity.this, "Les dépots ouvrent à 9 heures et ferment à 19 heures", Toast.LENGTH_LONG).show();
                }else{
                    int end = maxHour-hour;
                    String[] listHour = new String[end];
                    Arrays.fill(listHour, "");

                    for (int i = 1; i<=end;i++){
                        listHour[i-1]=String.valueOf(i)+" heures";
                    }

                    AlertDialog.Builder alertDialog = new AlertDialog.Builder(MenuActivity.this);
                    alertDialog.setTitle("Durée maximum de la livraison");
                    alertDialog.setItems(listHour, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            int hours = which+1;
                            OkHttpClient client = new OkHttpClient();
                            String url = "https://pa2021-esgi.herokuapp.com/androidApp/processDelivery.php";
                            RequestBody formBody =
                                    new FormBody.Builder().add("deposit", idDeposit).add("zone", zoneGeo).add("time", String.valueOf(hours)).add("poids", ptac).build();
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
                                        JSONObject jsonObj = null;
                                        try {
                                            jsonObj = new JSONObject(myResponse);
                                        } catch (JSONException e) {
                                            e.printStackTrace();
                                        }
                                        JSONObject finalJsonObj = jsonObj;
                                        MenuActivity.this.runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {
                                                try {
                                                    if(finalJsonObj.getInt("nbColis") > 0){
                                                        Intent qrCodeInt = new Intent(MenuActivity.this, QRcodeMenu.class);
                                                        qrCodeInt.putExtra("delivery",finalJsonObj.toString());
                                                        qrCodeInt.putExtra("idLivreur",idDeliver);
                                                        startActivity(qrCodeInt);
                                                    }else{
                                                        Toast.makeText(MenuActivity.this,"Aucun colis ne corresponds aux critères",Toast.LENGTH_SHORT).show();
                                                    }
                                                } catch (JSONException e) {
                                                    e.printStackTrace();
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });

                    alertDialog.setNegativeButton("Annuler", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {

                        }
                    });

                    AlertDialog customAlertDialog = alertDialog.create();
                    customAlertDialog.show();
                }

            }
        });

        this.disconnectBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent loginScreen = new Intent(MenuActivity.this, MainActivity.class);
                startActivity(loginScreen);
            }
        });
    }
}