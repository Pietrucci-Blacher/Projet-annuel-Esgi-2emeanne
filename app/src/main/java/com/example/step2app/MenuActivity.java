package com.example.step2app;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ListAdapter;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.List;

public class MenuActivity extends AppCompatActivity {

    private Button disconnectBtn,paiementBtn,deliveryBtn;
    private TextView welcomeTxt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);

        String firstname = getIntent().getStringExtra("prenom");
        String lastname = getIntent().getStringExtra("nom");

        this.welcomeTxt =findViewById(R.id.welcomeTxt);
        welcomeTxt.setText("Bienvenue "+firstname+" "+lastname);

        this.disconnectBtn = findViewById(R.id.disconnectBtn);
        this.deliveryBtn = findViewById(R.id.deliveryBtn);

        this.deliveryBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Calendar rightNow = Calendar.getInstance();
                int hour = rightNow.get(Calendar.HOUR_OF_DAY);
                if(hour < 9 || hour > 19){
                    Toast.makeText(MenuActivity.this, "Les dépots ouvrent à 9 heures et ferment à 19 heures", Toast.LENGTH_LONG).show();
                }else{
                    int end = 19-hour;
                    String[] listHour = new String[end];
                    Arrays.fill(listHour, "");

                    for (int i = 1; i<=end;i++){
                        listHour[i-1]=String.valueOf(i)+" heures";
                    }

                    AlertDialog.Builder alertDialog = new AlertDialog.Builder(MenuActivity.this);
                    alertDialog.setTitle("Durée de votre livraison");
                    alertDialog.setItems(listHour, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            int hours = which+1;
                            Toast.makeText(MenuActivity.this, String.valueOf(hours), Toast.LENGTH_SHORT).show();
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