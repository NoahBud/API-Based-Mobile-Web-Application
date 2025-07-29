package com.example.projet.activity;

import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import android.util.Log;

import androidx.appcompat.app.AppCompatActivity;

import com.example.projet.R;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.AddBoxRequest;
import com.example.projet.network.request.response.BoxResponse;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class AddBoxActivity extends AppCompatActivity {

    private EditText nameEditText, addressEditText, etatEditText;
    private Button addButton, backButton;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_box);

        nameEditText = findViewById(R.id.nameEditText);
        addressEditText = findViewById(R.id.addressEditText);
        etatEditText = findViewById(R.id.etatEditText);
        addButton = findViewById(R.id.addButton);
        backButton = findViewById(R.id.backButton);
        apiService = ApiClient.getClient(this).create(ApiService.class);

        addButton.setOnClickListener(v -> {
            String name = nameEditText.getText().toString().trim();
            String address = addressEditText.getText().toString().trim();
            String etat = etatEditText.getText().toString().trim();

            if (name.isEmpty() || address.isEmpty() || etat.isEmpty()) {
                Toast.makeText(AddBoxActivity.this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
            } else {
                AddBoxRequest newBox = new AddBoxRequest(name, address, etat);

                Log.d("API_Request", "Tentative d'ajout de boîte : " + name + ", " + address + ", " + etat);

                addBox(newBox);
            }
        });

        backButton.setOnClickListener(v -> finish());
    }

    private void addBox(AddBoxRequest boxRequest) {
        apiService.addBox(boxRequest).enqueue(new Callback<BoxResponse>() {
            @Override
            public void onResponse(Call<BoxResponse> call, Response<BoxResponse> response) {
                Log.d("API_Response", "Réponse de l'API : " + response.code() + " - " + (response.body() != null ? response.body().toString() : "Aucune réponse"));

                if (response.isSuccessful() && response.body() != null && response.body().getBox() != null) {
                    Toast.makeText(AddBoxActivity.this, "Boîte ajoutée avec succès!", Toast.LENGTH_SHORT).show();
                    finish();
                } else {
                    Toast.makeText(AddBoxActivity.this, "Erreur lors de l'ajout de la boîte", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<BoxResponse> call, Throwable t) {
                Log.e("API_Error", "Échec de la connexion : " + t.getMessage());

                Toast.makeText(AddBoxActivity.this, "Échec de la connexion", Toast.LENGTH_SHORT).show();
            }
        });
    }
}
