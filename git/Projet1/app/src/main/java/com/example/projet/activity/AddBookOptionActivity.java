package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import androidx.appcompat.app.AppCompatActivity;
import com.example.projet.R;

public class AddBookOptionActivity extends AppCompatActivity {

    private Button btnScan, btnAddManually;
    private ImageButton btnBack;
    private int boxId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_book_option);

        // Récupérer l'ID de la boîte depuis l'intent qui a lancé cette activité
        boxId = getIntent().getIntExtra("box_id", -1);
        Log.d("AddBookActivity", "box_id reçu : " + boxId);

        btnScan = findViewById(R.id.btnScan);
        btnAddManually = findViewById(R.id.btnAddManually);
        btnBack = findViewById(R.id.btnBack);

        // Lorsque l'utilisateur clique sur "Scanner", lance BookScannerActivity en passant l'ID de la boîte
        btnScan.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(AddBookOptionActivity.this, BookScannerActivity.class);
                intent.putExtra("box_id", boxId);
                startActivity(intent);
            }
        });
        btnAddManually.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(AddBookOptionActivity.this, AddBookActivity.class);
                intent.putExtra("box_id", boxId);
                startActivity(intent);
            }
        });
        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(AddBookOptionActivity.this, BoxListActivity.class);
                startActivity(intent);
            }
        });
    }
}