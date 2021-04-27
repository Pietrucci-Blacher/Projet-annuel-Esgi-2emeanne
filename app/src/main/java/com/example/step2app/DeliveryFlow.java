package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class DeliveryFlow extends AppCompatActivity {

    private TextView ref,name,adresse,city,phone,info,counter;
    private Button sign,absent,travel;
    private int countParcel = 0;

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
                if (countParcel+1 == Integer.parseInt(jsonObj.getString("nbColis"))){
                    updateView(jsonObj,this.ref,this.name,this.adresse,this.city,this.phone,this.info,this.counter,"end",0);
                }else{
                    updateView(jsonObj,this.ref,this.name,this.adresse,this.city,this.phone,this.info,this.counter,"colis",countParcel);
                }
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    private void updateView(JSONObject jsonObj,TextView ref,TextView name,TextView adresse,TextView city,TextView phone,TextView info,TextView counter,String element,int count) throws JSONException {
        JSONObject parcel = jsonObj.getJSONArray(element).getJSONObject(count);
        ref.setText("Référence : "+parcel.getString("refQrcode"));
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
}