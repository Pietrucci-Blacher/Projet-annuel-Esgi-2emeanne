package com.example.step2app;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;


public class MainActivity extends AppCompatActivity {

    private EditText email,mdp;
    private Button loginBtn;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        this.loginBtn = findViewById(R.id.loginBtn);
        this.email = findViewById(R.id.emailInput);
        this.mdp = findViewById(R.id.mdpInput);

        this.loginBtn.setOnClickListener(new View.OnClickListener() {
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View v) {
                if(email.getText().toString().length()==0){
                    email.setError("Saisie requise");
                }else if(mdp.getText().toString().length()==0){
                    mdp.setError("Saisie requise");
                }else {
                    OkHttpClient client = new OkHttpClient();
                    String url = "https://pa2021-esgi.herokuapp.com/androidApp/login.php";
                    RequestBody formBody = new FormBody.Builder().add("email", email.getText().toString()).add("mdp", mdp.getText().toString()).build();
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
                                MainActivity.this.runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        if(myResponse.equals("failed")){
                                            Toast.makeText(MainActivity.this,"Merci de vérifier votre email/mot de passe",Toast.LENGTH_SHORT).show();
                                        }else{
                                            Toast.makeText(MainActivity.this,"Bienvenue "+myResponse,Toast.LENGTH_SHORT).show();
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }

}
