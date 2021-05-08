package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.TableLayout;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.util.ArrayList;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class ReturnParcel extends AppCompatActivity {

    private Button returned;
    private TableLayout returnParcelContainer;
    private TextView parcel;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_return_parcel);

        this.returned=findViewById(R.id.btnReturned);
        this.returnParcelContainer=findViewById(R.id.returnParcelContainer);

        SharedPreferences prefs = getSharedPreferences("valeurs",MODE_PRIVATE);

        ArrayList<String> returnParcel = getIntent().getStringArrayListExtra("returnParcel");
        String idDelivery = getIntent().getStringExtra("idDelivery");
        String nbKm = getIntent().getStringExtra("nbKm");

        for(int i = 0; i<returnParcel.size();i++){
            parcel = new TextView(this);
            parcel.setText(returnParcel.get(i));
            parcel.setTextColor(Color.parseColor("#FFFFFF"));
            parcel.setGravity(Gravity.CENTER);
            parcel.setTextSize(18);
            parcel.setPadding(0,10,0,10);
            returnParcelContainer.addView(parcel);
        }

        returned.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                OkHttpClient client = new OkHttpClient();
                RequestBody formBody = new FormBody.Builder().add("deliveryId", idDelivery).add("nbKm", nbKm).build();
                Request request = new Request.Builder().url("https://pa2021-esgi.herokuapp.com/androidApp/finishReturnDelivery.php").post(formBody).build();
                client.newCall(request).enqueue(new Callback() {
                    @Override
                    public void onFailure(Call call, IOException e) {
                        e.printStackTrace();
                    }
                    @Override
                    public void onResponse(Call call, Response response) throws IOException {
                        if (response.isSuccessful()) {
                            final String myResponse = response.body().string();
                            ReturnParcel.this.runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    Intent menuInt = new Intent(ReturnParcel.this, MenuActivity.class);
                                    SharedPreferences.Editor edit = prefs.edit();
                                    edit.putString("idLivraison","none");
                                    edit.apply();
                                    Toast.makeText(ReturnParcel.this, "Livraison terminée", Toast.LENGTH_SHORT).show();
                                    startActivity(menuInt);
                                }
                            });
                        }
                    }
                });
            }
        });
    }
}