package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class calculatePay extends AppCompatActivity {

    private TextView parcelPrice,kmPrice,weightPrime,objectifPrime,objectifPrimeDetail;
    private Button btnPay;
    private double totalPay,totalWeightPrime;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_calculate_pay);

        this.parcelPrice = findViewById(R.id.parcelPrice);
        this.kmPrice = findViewById(R.id.kmPrice);
        this.weightPrime = findViewById(R.id.weightPrime);
        this.objectifPrime = findViewById(R.id.objectifPrime);
        this.objectifPrimeDetail = findViewById(R.id.objectifPrimeDetail);

        this.btnPay = findViewById(R.id.btnPay);

        JSONObject jsonObj = null;
        Integer delivered = null;
        Integer nbKm = null;
        Integer affected = null;
        Integer parcelSup30 = null;
        try {
            jsonObj = new JSONObject(getIntent().getStringExtra("payInfo"));
            nbKm = Integer.parseInt(jsonObj.getString("nbKm"));
            affected = Integer.parseInt(jsonObj.getString("affected"));
            delivered = Integer.parseInt(jsonObj.getString("delivered"));
            parcelSup30=Integer.parseInt(jsonObj.getString("parcelSup30"));
        } catch (JSONException e) {
            e.printStackTrace();
        }

        parcelPrice.setText(delivered.toString() + " colis x 1.90 €");
        kmPrice.setText(nbKm.toString() + " km parcourus x 0.36 €");
        objectifPrimeDetail.setText(delivered.toString()+" colis livrés / "+affected.toString()+" colis affectés");

        totalPay=0;

        totalPay += delivered*1.90;
        totalPay += nbKm*0.36;

        totalWeightPrime = 0;

        for (int i = 0; i < parcelSup30; i++){
            try {
                JSONArray sup30 = jsonObj.getJSONArray("sup30");
                totalWeightPrime+= Math.ceil(Integer.parseInt(sup30.getJSONObject(i).getString("weight"))/22)*3;
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }

        weightPrime.setText(String.valueOf(totalWeightPrime)+" € de prime charges lourdes");

        totalPay+=totalWeightPrime;


    }

}