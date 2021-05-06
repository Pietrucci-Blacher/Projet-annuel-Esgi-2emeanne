package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.math.BigDecimal;
import java.math.RoundingMode;

public class calculatePay extends AppCompatActivity {

    private TextView parcelPrice,kmPrice,weightPrime,objectifPrime,objectifPrimeDetail;
    private Button btnPay;
    private BigDecimal totalPay,totalWeightPrime,prime;

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

        totalPay=new BigDecimal("0");

        totalPay=totalPay.add(new BigDecimal(delivered).multiply(new BigDecimal("1.90")));
        totalPay=totalPay.add(new BigDecimal(nbKm).multiply(new BigDecimal("0.36")));

        totalWeightPrime = new BigDecimal("0");

        for (int i = 0; i < parcelSup30; i++){
            try {
                JSONArray sup30 = jsonObj.getJSONArray("sup30");
                BigDecimal temp = new BigDecimal(sup30.getJSONObject(i).getString("weight"));
                temp=temp.subtract(new BigDecimal("30"));
                temp=temp.divide(new BigDecimal("22"),2,BigDecimal.ROUND_HALF_EVEN);
                temp=temp.setScale(0,RoundingMode.CEILING);
                temp=temp.multiply(new BigDecimal("3"));
                totalWeightPrime=totalWeightPrime.add(temp);
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }

        weightPrime.setText(totalWeightPrime.toString()+" € de prime charges lourdes");

        totalPay=totalPay.add(totalWeightPrime);


        Integer percentPrime = (delivered*100)/affected;

        prime = new BigDecimal("0");

        if(percentPrime>87){
            prime.add(new BigDecimal("10").multiply(totalPay).divide(new BigDecimal("100")));
        }else if(percentPrime>72){
            prime.add(new BigDecimal("120"));
        }else if (percentPrime > 60){
            prime.add(new BigDecimal("50"));
        }else if (percentPrime <= 10){
            prime.subtract(new BigDecimal("15").multiply(totalPay).divide(new BigDecimal("100")));
        }

        objectifPrime.setText(String.valueOf(prime)+" € de prime d'objectif");

        totalPay=totalPay.add(prime);

        btnPay.setText("Récupérer "+String.valueOf(totalPay)+" €");
    }

}