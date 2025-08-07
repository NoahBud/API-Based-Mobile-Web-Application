package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.projet.R;
import com.example.projet.models.Book;
import com.example.projet.models.Copy;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.response.BookResponse;
import com.example.projet.network.request.response.CopyResponse;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ReservationBookActivity extends AppCompatActivity {

    private Button cancelButton, confirmButton;
    private TextView reservationMessageText;
    private int bookId;
    private int boxId;
    private ApiService apiService;
    private Copy selectedCopy;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_reservation_book);

        cancelButton = findViewById(R.id.cancelButton);
        confirmButton = findViewById(R.id.confirmButton);
        reservationMessageText = findViewById(R.id.reservationMessage);
        apiService = ApiClient.getClient(this).create(ApiService.class);


        bookId = getIntent().getIntExtra("book_id", -1);
        boxId = getIntent().getIntExtra("box_id", -1);

        if (bookId == -1) {
            Toast.makeText(this, "ID du livre invalide", Toast.LENGTH_SHORT).show();
            finish();
        } else {
            fetchBookName(bookId);
            fetchCopiesByBook(bookId);
        }

        cancelButton.setOnClickListener(v -> finish());

        confirmButton.setOnClickListener(v -> {
            if (selectedCopy != null) {
                updateCopyAvailability(selectedCopy.getId());
            } else {
                Toast.makeText(this, "Problème, contactez l'assistance", Toast.LENGTH_SHORT).show();
            }
        });



    }

    private void fetchBookName(int bookId) {
        apiService.getBookById(bookId).enqueue(new Callback<BookResponse>() {
            @Override
            public void onResponse(Call<BookResponse> call, Response<BookResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    Book book = response.body().getBook();
                    reservationMessageText.setText("Êtes-vous sûr de vouloir réserver : \"" + book.getName() + "\" ?");
                } else {
                    Toast.makeText(ReservationBookActivity.this, "Erreur de récupération du livre", Toast.LENGTH_SHORT).show();
                    finish();
                }
            }

            @Override
            public void onFailure(Call<BookResponse> call, Throwable t) {
                Toast.makeText(ReservationBookActivity.this, "Erreur réseau", Toast.LENGTH_SHORT).show();
                finish();
            }
        });
    }

    private void fetchCopiesByBook(int bookId) {
        apiService.getCopiesByBook(bookId).enqueue(new Callback<CopyResponse>() {
            @Override
            public void onResponse(Call<CopyResponse> call, Response<CopyResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Copy> copies = response.body().getCopies();
                    for (Copy copy : copies) {
                        if (copy.getBoxId() == boxId && copy.getDisponibilite()==1 ) {
                            selectedCopy = copy;
                            break;
                        }
                    }
                } else {
                    Toast.makeText(ReservationBookActivity.this, "Aucune copie disponible pour ce livre.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<CopyResponse> call, Throwable t) {
                Toast.makeText(ReservationBookActivity.this, "Erreur réseau lors de la récupération des copies", Toast.LENGTH_SHORT).show();
            }
        });
    }


    private void updateCopyAvailability(int copyId) {
        apiService.setCopyDisponibility(copyId).enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(ReservationBookActivity.this, "Vous pouvez récupérer le livre, bonne journée !", Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent(ReservationBookActivity.this, BookListActivity.class);
                    intent.putExtra("box_id", boxId);
                    startActivity(intent);
                    finish();
                } else {
                    Toast.makeText(ReservationBookActivity.this, "Erreur lors de la mise à jour", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<Void> call, Throwable t) {
                Toast.makeText(ReservationBookActivity.this, "Erreur réseau", Toast.LENGTH_SHORT).show();
            }
        });
    }
}
