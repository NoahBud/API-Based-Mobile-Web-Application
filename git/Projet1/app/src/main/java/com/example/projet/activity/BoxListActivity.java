package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.SearchView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.projet.R;
import com.example.projet.adapter.BoxAdapter;
import com.example.projet.models.Box;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class BoxListActivity extends AppCompatActivity implements BoxAdapter.OnBoxClickListener {

    private RecyclerView recyclerView;
    private BoxAdapter boxAdapter;
    private Button backButton, addButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_box_list);

        recyclerView = findViewById(R.id.recyclerView);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        backButton = findViewById(R.id.backButton);
        addButton = findViewById(R.id.addButton);

        backButton.setOnClickListener(v -> {
            startActivity(new Intent(BoxListActivity.this, MainActivity.class));
            finish();
        });

        addButton.setOnClickListener(v -> {
            startActivity(new Intent(BoxListActivity.this, AddBoxActivity.class));
        });
        SearchView searchView = findViewById(R.id.searchView);
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {

                if (boxAdapter != null) {
                    boxAdapter.filterList(newText);
                }
                return true;
            }
        });

        fetchBoxes();
    }

    private void fetchBoxes() {
        ApiService apiService = ApiClient.getClient(this).create(ApiService.class);
        Call<List<Box>> call = apiService.getAllBoxes();

        call.enqueue(new Callback<List<Box>>() {
            @Override
            public void onResponse(Call<List<Box>> call, Response<List<Box>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Box> boxList = response.body();
                    boxList.sort((box1, box2) -> box1.getName().compareToIgnoreCase(box2.getName()));
                    boxAdapter = new BoxAdapter(boxList, BoxListActivity.this);
                    recyclerView.setAdapter(boxAdapter);
                } else {
                    Toast.makeText(BoxListActivity.this, "Erreur de récupération des données", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<List<Box>> call, Throwable t) {
                Toast.makeText(BoxListActivity.this, "Échec de connexion", Toast.LENGTH_SHORT).show();
            }
        });
    }

    @Override
    public void onBoxClick(Box box) {
        Intent intent = new Intent(BoxListActivity.this, BookListActivity.class);
        intent.putExtra("box_id", box.getId());
        startActivity(intent);
    }
}