package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.example.projet.R;
import com.example.projet.adapter.BookResponseWrapper;
import com.example.projet.models.Book;
import com.example.projet.models.Copy;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.BoxIdRequest;
import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class BookScannerActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book_scanner);

        IntentIntegrator integrator = new IntentIntegrator(this);
        integrator.setDesiredBarcodeFormats(IntentIntegrator.EAN_13);
        integrator.setPrompt("Scannez le QR code du livre");
        integrator.setBeepEnabled(true);
        integrator.initiateScan();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        IntentResult result = IntentIntegrator.parseActivityResult(requestCode, resultCode, data);
        if (result != null) {
            if (result.getContents() == null) {
                Toast.makeText(this, "Scan annulé", Toast.LENGTH_SHORT).show();
                finish();
            } else {
                String isbn = result.getContents();
                int box_id = getIntent().getIntExtra("box_id", -1);
                if (box_id == -1) {
                    Toast.makeText(this, "ID de boîte manquant", Toast.LENGTH_SHORT).show();
                    finish();
                    return;
                }
                addBookToBox(isbn, box_id);
            }
        } else {
            super.onActivityResult(requestCode, resultCode, data);
        }
    }

    private void addBookToBox(String isbn, int boxId) {
        ApiService apiService = ApiClient.getClient(this).create(ApiService.class);
        Call<BookResponseWrapper> callGet = apiService.getBookByISBN(isbn);
        callGet.enqueue(new Callback<BookResponseWrapper>() {
            @Override
            public void onResponse(Call<BookResponseWrapper> call, Response<BookResponseWrapper> response) {
                if (response.isSuccessful() && response.body() != null) {
                    BookResponseWrapper wrapper = response.body();
                    Book book = wrapper.getBook();
                    if (book != null && book.getId() != 0) {
                        // Livre existant, on ajoute une copie via l'endpoint correct
                        Call<Copy> callPost = apiService.createCopyForBook(book.getId(), new BoxIdRequest(boxId));
                        callPost.enqueue(new Callback<Copy>() {
                            @Override
                            public void onResponse(Call<Copy> call, Response<Copy> response) {
                                if (response.isSuccessful()) {
                                    Toast.makeText(getApplicationContext(), "Livre ajouté à la boîte", Toast.LENGTH_SHORT).show();
                                    // Redirection vers BookListActivity
                                    Intent intent = new Intent(BookScannerActivity.this, BookListActivity.class);
                                    intent.putExtra("box_id", boxId);
                                    startActivity(intent);
                                    finish();
                                } else {
                                    Toast.makeText(getApplicationContext(), "Erreur lors de l'ajout du livre", Toast.LENGTH_SHORT).show();
                                    // Optionnel : rediriger quand même vers BookListActivity
                                    Intent intent = new Intent(BookScannerActivity.this, BookListActivity.class);
                                    intent.putExtra("box_id", boxId);
                                    startActivity(intent);
                                    finish();
                                }
                            }
                            @Override
                            public void onFailure(Call<Copy> call, Throwable t) {
                                Toast.makeText(getApplicationContext(), "Erreur réseau lors de l'ajout du livre", Toast.LENGTH_SHORT).show();
                                finish();
                            }
                        });
                    } else {
                        // Livre non trouvé, rediriger vers AddBookActivity
                        Toast.makeText(getApplicationContext(), "Livre non trouvé pour cet ISBN", Toast.LENGTH_SHORT).show();
                        Intent intent = new Intent(BookScannerActivity.this, AddBookActivity.class);
                        intent.putExtra("isbn", isbn);
                        intent.putExtra("box_id", boxId);
                        startActivity(intent);
                    }
                } else {
                    Toast.makeText(getApplicationContext(), "Livre non trouvé pour cet ISBN", Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent(BookScannerActivity.this, AddBookActivity.class);
                    intent.putExtra("isbn", isbn);
                    intent.putExtra("box_id", boxId);
                    startActivity(intent);
                }
            }
            @Override
            public void onFailure(Call<BookResponseWrapper> call, Throwable t) {
                Toast.makeText(getApplicationContext(), "Erreur réseau lors de la récupération du livre", Toast.LENGTH_SHORT).show();
                finish();
            }
        });
    }
}