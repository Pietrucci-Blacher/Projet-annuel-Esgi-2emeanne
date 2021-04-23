package com.example.step2app;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.view.Gravity;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TableLayout;
import android.widget.TextView;

public class QRcodeMenu extends AppCompatActivity {

    private TableLayout parcelsContainer;
    private LinearLayout parcelContainer;
    private Button buttonScan;
    private TextView refTxt;
    private LinearLayout linearLayout;

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_q_rcode_menu);

        this.parcelsContainer=findViewById(R.id.parcelContainer);

        for (int i = 0 ; i <5 ; i++){
            this.parcelContainer = new LinearLayout(this);
            this.buttonScan = new Button(this);
            btnProperties(buttonScan);
            this.refTxt = new TextView(this);
            refProperties(refTxt);

            parcelContainer.addView(refTxt);
            parcelContainer.addView(buttonScan);
            parcelsContainer.addView(parcelContainer);
        }

    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    private void btnProperties(Button btn){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT);
        params.weight = 0.4f;
        btn.setText("Scanner");
        btn.setBackgroundTintList(getResources().getColorStateList(R.color.upGold));
        btn.setLayoutParams(params);
    }

    private void refProperties(TextView txt){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT);
        params.weight = 0.6f;
        txt.setGravity(Gravity.CENTER);
        txt.setTextSize(17);
        txt.setText("Ref Qrcode");
        txt.setTextColor(Color.parseColor("#FFFFFF"));
        txt.setLayoutParams(params);
    }
}