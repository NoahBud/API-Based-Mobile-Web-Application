package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.example.projet.R;
import com.example.projet.models.Copy;
import com.example.projet.models.Editor;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.AddBookRequest;
import com.example.projet.network.request.BoxIdRequest;
import com.example.projet.network.request.response.IdResponse;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class AddBookActivity extends AppCompatActivity {

    private EditText nameEditText, genreEditText, descriptionEditText, isbnEditText;
    private AutoCompleteTextView authorAutoComplete;
    private Spinner editorSpinner, stateSpinner;
    private Button addButton, backButton;
    private ApiService apiService;
    private List<Editor> editorList = new ArrayList<>();
    private int boxId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_book);

        // Initialisation des vues
        nameEditText = findViewById(R.id.nameEditText);
        genreEditText = findViewById(R.id.genreEditText);
        descriptionEditText = findViewById(R.id.descriptionEditText);
        isbnEditText = findViewById(R.id.isbnEditText);
        authorAutoComplete = findViewById(R.id.authorAutoComplete);
        editorSpinner = findViewById(R.id.editorSpinner);
        stateSpinner = findViewById(R.id.stateSpinner);
        addButton = findViewById(R.id.addButton);
        backButton = findViewById(R.id.backButton);

        apiService = ApiClient.getClient(this).create(ApiService.class);

        backButton.setOnClickListener(v -> finish());

        // Récupération du box_id et de l'isbn depuis l'intention
        boxId = getIntent().getIntExtra("box_id", -1);
        Log.d("AddBookActivity", "box_id reçu : " + boxId);

        String isbnFromIntent = getIntent().getStringExtra("isbn");
        if (isbnFromIntent != null && !isbnFromIntent.isEmpty()) {
            isbnEditText.setText(isbnFromIntent);
            Log.d("AddBookActivity", "ISBN prérempli : " + isbnFromIntent);
        }

        fetchEditors();
        setupStateSpinner();

        addButton.setOnClickListener(v -> addBook());
    }

    private void setupStateSpinner() {
        String[] bookStates = {"Neuf", "Bon", "Usé", "Très usé", "A remplacer"};
        ArrayAdapter<String> stateAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, bookStates);
        stateAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        stateSpinner.setAdapter(stateAdapter);
    }

    private void fetchEditors() {
        apiService.getAllEditors().enqueue(new Callback<List<Editor>>() {
            @Override
            public void onResponse(Call<List<Editor>> call, Response<List<Editor>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    editorList.clear();
                    editorList.addAll(response.body());

                    List<String> editorNames = new ArrayList<>();
                    for (Editor editor : editorList) {
                        editorNames.add(editor.getName());
                    }

                    ArrayAdapter<String> adapter = new ArrayAdapter<>(AddBookActivity.this,
                            android.R.layout.simple_spinner_item, editorNames);
                    adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    editorSpinner.setAdapter(adapter);
                } else {
                    Toast.makeText(AddBookActivity.this, "Erreur de récupération des éditeurs", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<List<Editor>> call, Throwable t) {
                Toast.makeText(AddBookActivity.this, "Erreur de réseau", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void addBook() {
        String name = nameEditText.getText().toString().trim();
        String genre = genreEditText.getText().toString().trim();
        String description = descriptionEditText.getText().toString().trim();
        String isbn = isbnEditText.getText().toString().trim();
        String authorName = authorAutoComplete.getText().toString().trim();

        if (name.isEmpty() || genre.isEmpty() || description.isEmpty() || isbn.isEmpty() || authorName.isEmpty()) {
            Toast.makeText(this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
            return;
        }

        List<String> authors = new ArrayList<>();
        authors.add(authorName);

        int editorId = editorList.get(editorSpinner.getSelectedItemPosition()).getId_editor();
        AddBookRequest bookRequest = new AddBookRequest(isbn, name, genre, description, editorId, authors);

        apiService.addBook(bookRequest).enqueue(new Callback<IdResponse>() {
            @Override
            public void onResponse(Call<IdResponse> call, Response<IdResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    IdResponse idResponse = response.body();
                    int bookId = idResponse.getBookId();
                    String message = idResponse.getMessage();
                    Toast.makeText(AddBookActivity.this, message, Toast.LENGTH_SHORT).show();
                    createCopy(bookId);
                    finish();
                } else {
                    Log.e("AddBookActivity", "Réponse de l'API : " + response.message());
                    Toast.makeText(AddBookActivity.this, "Erreur lors de l'ajout du livre", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<IdResponse> call, Throwable t) {
                Toast.makeText(AddBookActivity.this, "Erreur de connexion", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void createCopy(int bookId) {
        BoxIdRequest boxIdRequest = new BoxIdRequest(boxId);
        apiService.createCopyForBook(bookId, boxIdRequest).enqueue(new Callback<Copy>() {
            @Override
            public void onResponse(Call<Copy> call, Response<Copy> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(AddBookActivity.this, "Copie créée avec succès !", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<Copy> call, Throwable t) {
                Toast.makeText(AddBookActivity.this, "Erreur de connexion", Toast.LENGTH_SHORT).show();
            }
        });
    }
}