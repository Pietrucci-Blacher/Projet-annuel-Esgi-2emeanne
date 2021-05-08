package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.text.InputType;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.math.BigDecimal;
import java.math.RoundingMode;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class calculatePay extends AppCompatActivity {

    private TextView parcelPrice,kmPrice,weightPrime,objectifPrime,objectifPrimeDetail;
    private Button btnPay;
    private BigDecimal totalPay,totalWeightPrime,prime;
    private String bankAccount;

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

        String idDeliver = getIntent().getStringExtra("idLivreur");

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
            bankAccount=jsonObj.getString("bank");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        parcelPrice.setText(delivered.toString() + " colis x 1.90 €");
        kmPrice.setText(nbKm.toString() + " km parcourus x 0.36 €");
        objectifPrimeDetail.setText(delivered.toString()+" colis traités / "+affected.toString()+" colis affectés");

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
            prime=prime.add(new BigDecimal("10").multiply(totalPay).divide(new BigDecimal("100"),2,BigDecimal.ROUND_HALF_EVEN));
        }else if(percentPrime>72){
            prime=prime.add(new BigDecimal("120"));
        }else if (percentPrime > 60){
            prime=prime.add(new BigDecimal("50"));
        }else if (percentPrime <= 10){
            prime=prime.subtract(new BigDecimal("15").multiply(totalPay).divide(new BigDecimal("100"),2,BigDecimal.ROUND_HALF_EVEN));
        }

        objectifPrime.setText(String.valueOf(prime)+" € de prime d'objectif");

        totalPay=totalPay.add(prime);

        btnPay.setText("Récupérer "+String.valueOf(totalPay)+" €");

        Integer finalDelivered = delivered;
        Integer finalNbKm = nbKm;
        btnPay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(totalPay.compareTo(new BigDecimal("10")) == 1){
                    AlertDialog.Builder builder = new AlertDialog.Builder(calculatePay.this,AlertDialog.THEME_DEVICE_DEFAULT_DARK);
                    builder.setTitle("Merci d'indiquer un compte bancaire");

                    final EditText input = new EditText(calculatePay.this);

                    input.setTextColor(Color.WHITE);
                    input.setInputType(InputType.TYPE_CLASS_TEXT);
                    if(bankAccount.equals("none")){
                        input.setText("");
                    }else{
                        input.setText(bankAccount);
                    }
                    builder.setView(input);

                    builder.setPositiveButton("Valider", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            Toast toast =Toast.makeText(calculatePay.this, "Paiement en cours", Toast.LENGTH_LONG);
                            toast.show();
                            bankAccount = input.getText().toString();
                            OkHttpClient client = new OkHttpClient();
                            String url = "https://pa2021-esgi.herokuapp.com/androidApp/payDeliver.php";
                            RequestBody formBody = new FormBody.Builder()
                                    .add("idDeliver", idDeliver)
                                    .add("amount", totalPay.toString())
                                    .add("bankAccount", bankAccount)
                                    .add("primeObjectif", prime.toString())
                                    .add("nbParcel", finalDelivered.toString())
                                    .add("primeWeight", totalWeightPrime.toString())
                                    .add("nbKm", finalNbKm.toString())
                                    .build();
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

                                        calculatePay.this.runOnUiThread(new Runnable() {
                                            @Override
                                            public void run() {
                                                if(myResponse.equals("success")){
                                                    toast.cancel();
                                                    Toast.makeText(calculatePay.this, "Paiement effectué", Toast.LENGTH_SHORT).show();
                                                    Intent menuInt = new Intent(calculatePay.this,MenuActivity.class);
                                                    startActivity(menuInt);
                                                }else{
                                                    Toast.makeText(calculatePay.this, "Erreur lors du paiement", Toast.LENGTH_SHORT).show();
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    });

                    builder.setNegativeButton("Annuler", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.cancel();
                        }
                    });

                    builder.show();

                }else{
                    Toast.makeText(calculatePay.this, "Virement minimum de plus de 10 €", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

}