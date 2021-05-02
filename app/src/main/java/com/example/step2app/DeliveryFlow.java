package com.example.step2app;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.cloudinary.android.MediaManager;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class DeliveryFlow extends AppCompatActivity {

    private TextView ref,name,adresse,city,phone,info,counter;
    private Button sign,absent,travel,cancel;
    private Integer countParcel = 0;
    String parcelsId;
    String idDelivery;
    Integer nbKm;
    Integer nbKmSkip = 0;
    List<String> returnParcel = new ArrayList<String>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_delivery_flow);

        this.ref = findViewById(R.id.parcelRef);
        this.name = findViewById(R.id.parcelName);
        this.adresse = findViewById(R.id.parcelAddr);
        this.city = findViewById(R.id.parcelCity);
        this.phone = findViewById(R.id.parcelPhone);
        this.counter = findViewById(R.id.parcelCounter);
        this.info = findViewById(R.id.parcelInfo);

        this.sign = findViewById(R.id.btnSign);
        this.absent = findViewById(R.id.btnAbsent);
        this.travel = findViewById(R.id.btnTravel);
        this.cancel = findViewById(R.id.btnCancel);

        idDelivery = getIntent().getStringExtra("idLivraison");

        JSONObject jsonObj = null;
        try {
            jsonObj = new JSONObject(getIntent().getStringExtra("delivery"));
        } catch (JSONException e) {
            e.printStackTrace();
        }

        try {
            if(Integer.parseInt(jsonObj.getString("nbColis"))==1){
                updateView(jsonObj,this.ref,this.name,this.adresse,this.city,this.phone,this.info,this.counter,"end",countParcel);
                countParcel+=1;
            }else{
                updateView(jsonObj,this.ref,this.name,this.adresse,this.city,this.phone,this.info,this.counter,"colis",countParcel);
                countParcel+=1;
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

        sign.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                SharedPreferences onlyonce = getSharedPreferences("onlyOnce",MODE_PRIVATE);
                if (onlyonce.getBoolean("initialized",false) == false) {
                    Map config = new HashMap();
                    config.put("cloud_name", "hvrzhzxky");
                    config.put("api_key", "812238978328538");
                    config.put("api_secret", "_TOLF-WD9wC2a1kBHDDDGGTAHAg");
                    MediaManager.init(DeliveryFlow.this, config);
                    onlyonce.edit().putBoolean("initialized",true).apply();
                }
                String fileName = (String) ref.getText();
                Intent signIntent = new Intent(DeliveryFlow.this,Sign.class);
                signIntent.putExtra("refQrcode",fileName);
                signIntent.putExtra("delivery",getIntent().getStringExtra("delivery"));
                startActivityForResult(signIntent,0);
            }
        });

        JSONObject finalJsonObj = jsonObj;
        travel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    String url = "https://www.google.com/maps/dir/?api=1&destination=Madrid,Spain&waypoints=Zaragoza|Huesca&travelmode=driving&dir_action=navigate";
                    Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(constructUrl(finalJsonObj,countParcel-1)));
                    startActivity(intent);
                } catch (JSONException e) {
                    e.printStackTrace();
                } catch (UnsupportedEncodingException e) {
                    e.printStackTrace();
                }

            }
        });

        JSONObject finalJsonObj1 = jsonObj;
        absent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                returnParcel.add(ref.getText().toString());
                handleParcel("https://pa2021-esgi.herokuapp.com/androidApp/absentParcel.php", finalJsonObj1);
            }
        });

        JSONObject finalJsonObj2 = jsonObj;
        cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(DeliveryFlow.this);
                alertDialogBuilder.setTitle("Annuler la livraison");
                alertDialogBuilder.setMessage("Êtes-vous sure de vouloir annuler cette livraison ?");
                alertDialogBuilder.setPositiveButton("Oui", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        //Toast.makeText(DeliveryFlow.this, returnParcel.toString(), Toast.LENGTH_SHORT).show();
                        OkHttpClient client = new OkHttpClient();
                        RequestBody formBody = new FormBody.Builder().add("deliveryId", idDelivery).build();
                        Request request = new Request.Builder().url("https://pa2021-esgi.herokuapp.com/androidApp/cancelDelivery.php").post(formBody).build();
                        client.newCall(request).enqueue(new Callback() {
                            @Override
                            public void onFailure(Call call, IOException e) {
                                e.printStackTrace();
                            }
                            @Override
                            public void onResponse(Call call, Response response) throws IOException {
                                if (response.isSuccessful()) {
                                    final String myResponse = response.body().string();
                                    DeliveryFlow.this.runOnUiThread(new Runnable() {
                                        @Override
                                        public void run() {
                                            Integer nbParcel = 0;
                                            Integer kmSkip = 0;
                                            try {
                                                nbParcel = Integer.parseInt(finalJsonObj2.getString("nbColis"));
                                            } catch (JSONException e) {
                                                e.printStackTrace();
                                            }
                                            for (int i = countParcel - 1; i < nbParcel; i++) {
                                                if (i == nbParcel - 1) {
                                                    try {
                                                        returnParcel.add("Référence : "+finalJsonObj2.getJSONArray("end").getJSONObject(0).getString("refQrcode"));
                                                        kmSkip += Integer.parseInt(finalJsonObj2.getJSONArray("end").getJSONObject(0).getString("distance"));
                                                    } catch (JSONException e) {
                                                        e.printStackTrace();
                                                    }
                                                } else {
                                                    try {
                                                        returnParcel.add("Référence : "+finalJsonObj2.getJSONArray("colis").getJSONObject(i).getString("refQrcode"));
                                                        kmSkip += Integer.parseInt(finalJsonObj2.getJSONArray("colis").getJSONObject(i).getString("distance"));
                                                    } catch (JSONException e) {
                                                        e.printStackTrace();
                                                    }
                                                }
                                            }

                                            Integer distance = null;
                                            try {
                                                distance = Integer.parseInt(finalJsonObj2.getString("distance")) - kmSkip;
                                            } catch (JSONException e) {
                                                e.printStackTrace();
                                            }

                                            Intent returnInt = new Intent(DeliveryFlow.this, ReturnParcel.class);
                                            returnInt.putStringArrayListExtra("returnParcel", (ArrayList<String>) returnParcel);
                                            returnInt.putExtra("idDelivery", idDelivery);
                                            returnInt.putExtra("nbKm", distance.toString());
                                            startActivity(returnInt);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
                alertDialogBuilder.setNegativeButton("Non", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        dialog.cancel();
                    }
                });
                alertDialogBuilder.create().show();
            }
        });
    }

    private void updateView(JSONObject jsonObj,TextView ref,TextView name,TextView adresse,TextView city,TextView phone,TextView info,TextView counter,String element,int count) throws JSONException {
        JSONObject parcel = jsonObj.getJSONArray(element).getJSONObject(count);
        ref.setText("Référence : "+parcel.getString("refQrcode"));
        ref.setTag(parcel.getString("idColis"));
        name.setText("Destinataire : "+parcel.getString("nom")+" "+parcel.getString("prenom"));
        adresse.setText("Adresse : "+parcel.getString("adresse"));
        city.setText("Ville : "+parcel.getString("ville")+" "+parcel.getString("codePostal"));
        if(parcel.getString("numPhone").equals("")){
            phone.setText("");
        }else{
            phone.setText("Numéro de téléphone : "+parcel.getString("numPhone"));
        }
        if(parcel.getString("info").equals("")){
            info.setText("");
        }else{
            info.setText(parcel.getString("info"));
        }
        counter.setText("Colis "+(countParcel+1)+"/"+jsonObj.getString("nbColis"));
    }

    private String constructUrl (JSONObject jsonObj, int count) throws JSONException, UnsupportedEncodingException {
        String url = "https://www.google.com/maps/dir/?api=1&destination=";
        String parcel;
        JSONObject parcelInfo;

        int nbColis = Integer.parseInt(jsonObj.getString("nbColis"));

        parcelInfo = jsonObj.getJSONArray("end").getJSONObject(0);
        parcel = parcelInfo.getString("adresse") + " " + parcelInfo.getString("ville") + " " + parcelInfo.getString("codePostal");

        url += URLEncoder.encode(parcel,"utf-8");

        if (nbColis > 1) {
            url+="&waypoints=";
            for (int i = count; i < nbColis - 1; i++) {
                parcelInfo = jsonObj.getJSONArray("colis").getJSONObject(i);
                parcel = parcelInfo.getString("adresse") + " " + parcelInfo.getString("ville") + " " + parcelInfo.getString("codePostal");
                url += URLEncoder.encode(parcel,"utf-8");
                if(i != nbColis-2){
                    url += "|";
                }
            }
        }

        url += "&travelmode=driving&dir_action=navigate";

        return url;
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 0) {
            if(resultCode == RESULT_OK){
                try {
                    handleParcel("https://pa2021-esgi.herokuapp.com/androidApp/deliverParcel.php", new JSONObject(getIntent().getStringExtra("delivery")));
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    private void handleParcel(String url, JSONObject json){

        OkHttpClient client = new OkHttpClient();
        RequestBody formBody = new FormBody.Builder().add("parcelId", ref.getTag().toString()).add("deliveryId", idDelivery).build();
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
                    DeliveryFlow.this.runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            try {
                                if(countParcel == Integer.parseInt(json.getString("nbColis"))){
                                    if (returnParcel.size() == 0){
                                        OkHttpClient client = new OkHttpClient();
                                        RequestBody formBody = new FormBody.Builder().add("nbKm",json.getString("distance")).add("deliveryId", idDelivery).build();
                                        Request request = new Request.Builder().url("https://pa2021-esgi.herokuapp.com/androidApp/finishDelivery.php").post(formBody).build();
                                        client.newCall(request).enqueue(new Callback() {
                                            @Override
                                            public void onFailure(Call call, IOException e) {
                                                e.printStackTrace();
                                            }
                                            @Override
                                            public void onResponse(Call call, Response response) throws IOException {
                                                if (response.isSuccessful()) {
                                                    final String myResponse = response.body().string();
                                                    DeliveryFlow.this.runOnUiThread(new Runnable() {
                                                        @Override
                                                        public void run() {
                                                            Intent menuInt = new Intent(DeliveryFlow.this, MenuActivity.class);
                                                            Toast.makeText(DeliveryFlow.this, "Livraison terminée", Toast.LENGTH_SHORT).show();
                                                            startActivity(menuInt);
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }else{
                                        Intent returnInt = new Intent(DeliveryFlow.this,ReturnParcel.class);
                                        returnInt.putStringArrayListExtra("returnParcel", (ArrayList<String>) returnParcel);
                                        returnInt.putExtra("nbKm",json.getString("distance"));
                                        returnInt.putExtra("idDelivery",idDelivery);
                                        startActivity(returnInt);
                                    }
                                }else{
                                    if(countParcel == Integer.parseInt(json.getString("nbColis"))-1){
                                        updateView(json,ref,name,adresse,city,phone,info,counter,"end",0);
                                    }else{
                                        updateView(json,ref,name,adresse,city,phone,info,counter,"colis",countParcel);
                                    }
                                    countParcel+=1;
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
}