package com.example.projet.activity;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.response.AuthResponse;
import com.example.projet.R;
import com.example.projet.network.request.RegisterRequest;
import com.example.projet.models.User;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import java.io.IOException;

public class RegisterActivity extends AppCompatActivity {

    private EditText emailEditText, passwordEditText, nameEditText, confirmPasswordEditText;
    private Button registerButton, btnBack;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        emailEditText = findViewById(R.id.emailEditText);
        passwordEditText = findViewById(R.id.passwordEditText);
        nameEditText = findViewById(R.id.nameEditText);
        confirmPasswordEditText = findViewById(R.id.confirmPasswordEditText);
        registerButton = findViewById(R.id.registerButton);
        btnBack = findViewById(R.id.btnBack);
        SharedPreferences sharedPreferences = getSharedPreferences("MyPrefs", MODE_PRIVATE);
        if (sharedPreferences.getBoolean("isLoggedIn", false)) {
            startActivity(new Intent(RegisterActivity.this, MainActivity.class));
            finish();
        }
        Retrofit retrofit = ApiClient.getClient(this);
        apiService = retrofit.create(ApiService.class);

        registerButton.setOnClickListener(v -> {
            String email = emailEditText.getText().toString().trim();
            String password = passwordEditText.getText().toString().trim();
            String confirmPassword = confirmPasswordEditText.getText().toString().trim();
            String name = nameEditText.getText().toString().trim();
            String role = "visiteur";


            if (email.isEmpty() || password.isEmpty() || name.isEmpty() || confirmPassword.isEmpty()) {
                Toast.makeText(RegisterActivity.this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
            } else if (!password.equals(confirmPassword)) {
                Toast.makeText(RegisterActivity.this, "Les mots de passe ne correspondent pas", Toast.LENGTH_SHORT).show();
            } else {
                registerUser(name, email, password, confirmPassword, role);
            }
        });
        btnBack.setOnClickListener(v -> finish());
    }

    private void registerUser(String name, String email, String password, String confirmPassword, String role) {
        RegisterRequest registerRequest = new RegisterRequest(name, email, password, confirmPassword, role);

        apiService.registerUser(registerRequest).enqueue(new Callback<AuthResponse>() {
            @Override
            public void onResponse(Call<AuthResponse> call, Response<AuthResponse> response) {
                Log.d("DEBUG", "onResponse activé, code: " + response.code());

                if (response.isSuccessful() && response.body() != null) {
                    AuthResponse authResponse = response.body();
                    User user = authResponse.getUser();
                    SharedPreferences sharedPreferences = getSharedPreferences("MyPrefs", MODE_PRIVATE);
                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    editor.putBoolean("isLoggedIn", true);
                    editor.putString("token", authResponse.getToken());
                    editor.putInt("userId", user.getId());
                    editor.putString("userEmail", user.getEmail());
                    editor.putString("userName", user.getName());
                    editor.putString("userRole", role); // Sauvegarde du rôle
                    editor.apply();

                    ApiClient.resetClient();

                    Toast.makeText(RegisterActivity.this, "Inscription réussie", Toast.LENGTH_SHORT).show();
                    startActivity(new Intent(RegisterActivity.this, MainActivity.class));
                    finish();
                } else {
                    Log.e("DEBUG", "Requête échouée, code: " + response.code());
                    try {
                        String errorMessage = response.errorBody().string();
                        Log.e("DEBUG", "Erreur API : " + errorMessage);
                        Toast.makeText(RegisterActivity.this, "Erreur : " + errorMessage, Toast.LENGTH_LONG).show();
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }

            @Override
            public void onFailure(Call<AuthResponse> call, Throwable t) {
                Log.e("DEBUG", "onFailure activé", t);
                if (t instanceof IOException) {
                    Toast.makeText(RegisterActivity.this, "Vérifiez votre connexion Internet.", Toast.LENGTH_SHORT).show();
                } else {
                    Toast.makeText(RegisterActivity.this, "Erreur serveur, réessayez plus tard.", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }
}
