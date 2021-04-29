package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.cloudinary.android.MediaManager;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.HashMap;
import java.util.Map;

public class DeliveryFlow extends AppCompatActivity {

    private TextView ref,name,adresse,city,phone,info,counter;
    private Button sign,absent,travel;
    private int countParcel = 0;
    Boolean initialized = false;

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

        String idDeliver = getIntent().getStringExtra("idLivreur");

        JSONObject jsonObj = null;
        try {
            jsonObj = new JSONObject(getIntent().getStringExtra("delivery"));
        } catch (JSONException e) {
            e.printStackTrace();
        }

        try {
            if(Integer.parseInt(jsonObj.getString("nbColis"))==1){
                updateView(jsonObj,this.ref,this.name,this.adresse,this.city,this.phone,this.info,this.counter,"end",countParcel);
            }else{
                updateView(jsonObj,this.ref,this.name,this.adresse,this.city,this.phone,this.info,this.counter,"colis",countParcel);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

        sign.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(initialized==false){
                    Map config = new HashMap();
                    config.put("cloud_name", "hvrzhzxky");
                    config.put("api_key", "812238978328538");
                    config.put("api_secret", "_TOLF-WD9wC2a1kBHDDDGGTAHAg");
                    MediaManager.init(DeliveryFlow.this, config);
                    initialized = true;
                }
                String fileName = (String) ref.getTag();
                Intent signIntent = new Intent(DeliveryFlow.this,Sign.class);
                signIntent.putExtra("refQrcode",fileName);
                startActivityForResult(signIntent,0);
            }
        });

        JSONObject finalJsonObj = jsonObj;
        travel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    String url = "https://www.google.com/maps/dir/?api=1&destination=Madrid,Spain&waypoints=Zaragoza|Huesca&travelmode=driving&dir_action=navigate";
                    Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(constructUrl(finalJsonObj,countParcel)));
                    startActivity(intent);
                } catch (JSONException e) {
                    e.printStackTrace();
                } catch (UnsupportedEncodingException e) {
                    e.printStackTrace();
                }

            }
        });
    }

    private void updateView(JSONObject jsonObj,TextView ref,TextView name,TextView adresse,TextView city,TextView phone,TextView info,TextView counter,String element,int count) throws JSONException {
        JSONObject parcel = jsonObj.getJSONArray(element).getJSONObject(count);
        ref.setText("Référence : "+parcel.getString("refQrcode"));
        ref.setTag(parcel.getString("refQrcode"));
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

}