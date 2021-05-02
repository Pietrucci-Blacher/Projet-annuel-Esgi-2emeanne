package com.example.step2app;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.content.ContextWrapper;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import com.cloudinary.android.MediaManager;
import com.cloudinary.android.callback.ErrorInfo;
import com.cloudinary.android.callback.UploadCallback;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.util.HashMap;
import java.util.Map;

public class Sign extends AppCompatActivity {

    Drawing drawing;
    LinearLayout drawLayout;
    Button erase,validate,back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign);

        this.drawLayout = findViewById(R.id.drawLayout);
        this.erase = findViewById(R.id.btnErase);
        this.validate = findViewById(R.id.btnValidate);
        this.back = findViewById(R.id.btnBack);

        String fileName = getIntent().getStringExtra("refQrcode");

        drawing = new Drawing(this,null);
        drawing.setBackgroundColor(Color.WHITE);
        drawing.setDrawingCacheEnabled(true);
        drawLayout.addView(drawing);


        erase.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                drawing.erase();
                drawing.invalidate();
            }
        });

        back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                setResult(RESULT_CANCELED);
                finish();
            }
        });

        validate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                ContextWrapper cw = new ContextWrapper(getApplicationContext());
                File directory = cw.getDir("sign", Context.MODE_PRIVATE);

                if (!directory.exists()) {
                    directory.mkdir();
                }

                File filePath = new File(directory, "sign.jpg");
                Bitmap bitmap = drawing.getDrawingCache();

                try {
                    bitmap.compress(Bitmap.CompressFormat.JPEG, 95, new FileOutputStream(filePath));
                } catch (FileNotFoundException e) {
                    e.printStackTrace();
                }

                MediaManager.get().upload(String.valueOf(filePath))
                      .option("resource_type", "image")
                      .option("folder", "uploadSign")
                      .option("public_id", fileName)
                        .callback(new UploadCallback() {
                            Toast toast;
                            @Override
                            public void onStart(String requestId) {
                                toast =Toast.makeText(Sign.this, "Upload en cours", Toast.LENGTH_SHORT);
                                toast.show();
                            }

                            @Override
                            public void onProgress(String requestId, long bytes, long totalBytes) { }

                            @Override
                            public void onSuccess(String requestId, Map resultData) {
                                filePath.delete();
                                toast.cancel();
                                setResult(RESULT_OK);
                                finish();
                            }

                            @Override
                            public void onError(String requestId, ErrorInfo error) {
                                filePath.delete();
                                toast.cancel();
                                Toast.makeText(Sign.this, "Erreur veuillez réessayer", Toast.LENGTH_SHORT).show();
                            }

                            @Override
                            public void onReschedule(String requestId, ErrorInfo error) { }
                        }).dispatch();

            }
        });
    }
}