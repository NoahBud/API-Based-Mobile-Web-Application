package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.text.SpannableStringBuilder;
import android.text.Spanned;
import android.text.method.LinkMovementMethod;
import android.text.style.ClickableSpan;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.projet.R;
import com.example.projet.models.Author;
import com.example.projet.models.Book;
import com.example.projet.models.Box;
import com.example.projet.models.Copy;
import com.example.projet.models.Editor;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.response.AuthorResponse;
import com.example.projet.network.request.response.BookResponse;
import com.example.projet.network.request.response.CopyResponse;
import com.example.projet.network.request.response.EditorResponse;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class BookDetailActivity extends AppCompatActivity {

    private TextView bookISBN, bookName, bookGenre, bookDescription, bookAuthor, bookEditor, bookBoxes;
    private Button backButton, reserveButton;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book_detail);

        bookISBN = findViewById(R.id.bookISBN);
        bookName = findViewById(R.id.bookName);
        bookGenre = findViewById(R.id.bookGenre);
        bookDescription = findViewById(R.id.bookDescription);
        bookAuthor = findViewById(R.id.bookAuthor);
        bookEditor = findViewById(R.id.bookEditor);
        bookBoxes = findViewById(R.id.bookBoxes);
        backButton = findViewById(R.id.backButton);
        reserveButton = findViewById(R.id.reserveButton);
        apiService = ApiClient.getClient(this).create(ApiService.class);

        backButton.setOnClickListener(v -> finish());

        int bookId = getIntent().getIntExtra("book_id", -1);
        int boxId = getIntent().getIntExtra("box_id", -1);

        reserveButton.setOnClickListener(v -> {
            Intent intent = new Intent(BookDetailActivity.this, ReservationBookActivity.class);
            intent.putExtra("book_id", bookId);
            intent.putExtra("box_id", boxId);
            startActivity(intent);
        });

        if (bookId == -1) {
            Toast.makeText(this, "ID du livre invalide", Toast.LENGTH_SHORT).show();
            finish();
        } else {
            fetchBookDetails(bookId);
            fetchAuthorsByBook(bookId);
            fetchEditorByBook(bookId);
            fetchAvailableBoxesByBook(bookId);
        }
    }

    private void fetchBookDetails(int bookId) {
        Call<BookResponse> call = apiService.getBookById(bookId);
        call.enqueue(new Callback<BookResponse>() {
            @Override
            public void onResponse(Call<BookResponse> call, Response<BookResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    Book book = response.body().getBook();
                    bookISBN.setText("ISBN : " + book.getISBN());
                    bookName.setText(book.getName());
                    bookGenre.setText("Genre : " + book.getGenre());
                    bookDescription.setText("Description : " + book.getDescription());
                } else {
                    Toast.makeText(BookDetailActivity.this, "Erreur de récupération des détails du livre", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<BookResponse> call, Throwable t) {
                Toast.makeText(BookDetailActivity.this, "Erreur réseau", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void fetchAuthorsByBook(int bookId) {
        Call<AuthorResponse> call = apiService.getAuthorsByBook(bookId);
        call.enqueue(new Callback<AuthorResponse>() {
            @Override
            public void onResponse(Call<AuthorResponse> call, Response<AuthorResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().getAuthors() != null && !response.body().getAuthors().isEmpty()) {
                    Author author = response.body().getAuthors().get(0);
                    bookAuthor.setText("Auteur : " + author.getName());
                } else {
                    bookAuthor.setText("Auteur : Inconnu");
                }
            }

            @Override
            public void onFailure(Call<AuthorResponse> call, Throwable t) {
                Toast.makeText(BookDetailActivity.this, "Erreur de récupération de l'auteur", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void fetchEditorByBook(int bookId) {
        Call<EditorResponse> call = apiService.getEditorById(bookId);
        call.enqueue(new Callback<EditorResponse>() {
            @Override
            public void onResponse(Call<EditorResponse> call, Response<EditorResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    Editor editor = response.body().getEditor();
                    if (editor != null) {
                        bookEditor.setText("Éditeur : " + editor.getName());
                    } else {
                        bookEditor.setText("Éditeur : Inconnu");
                    }
                } else {
                    bookEditor.setText("Éditeur : Inconnu");
                    Toast.makeText(BookDetailActivity.this, "Erreur de récupération de l'éditeur", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<EditorResponse> call, Throwable t) {
                Toast.makeText(BookDetailActivity.this, "Erreur réseau", Toast.LENGTH_SHORT).show();
                bookEditor.setText("Éditeur : Inconnu");
            }
        });
    }

    private void fetchAvailableBoxesByBook(int bookId) {
        Call<CopyResponse> call = apiService.getCopiesByBook(bookId);
        call.enqueue(new Callback<CopyResponse>() {
            @Override
            public void onResponse(Call<CopyResponse> call, Response<CopyResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Copy> allCopies = response.body().getCopies();
                    List<Copy> availableCopies = new ArrayList<>();
                    for (Copy copy : allCopies) {
                        if (copy.getDisponibilite() == 1) {
                            availableCopies.add(copy);
                        }
                    }

                    if (!availableCopies.isEmpty()) {
                        Set<Integer> boxIds = new HashSet<>();
                        for (Copy copy : availableCopies) {
                            boxIds.add(copy.getBoxId());
                        }
                        fetchBoxesByIds(boxIds);
                    } else {
                        bookBoxes.setText("Aucune copie disponible.");
                    }
                } else {
                    Toast.makeText(BookDetailActivity.this, "Erreur de récupération des copies", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<CopyResponse> call, Throwable t) {
                Toast.makeText(BookDetailActivity.this, "Erreur réseau lors de la récupération des copies", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void fetchBoxesByIds(Set<Integer> boxIds) {
        Call<List<Box>> call = apiService.getAllBoxes();
        call.enqueue(new Callback<List<Box>>() {
            @Override
            public void onResponse(Call<List<Box>> call, Response<List<Box>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Box> allBoxes = response.body();
                    List<Box> matchedBoxes = new ArrayList<>();
                    for (Box box : allBoxes) {
                        if (boxIds.contains(box.getId())) {
                            matchedBoxes.add(box);
                        }
                    }
                    if (!matchedBoxes.isEmpty()) {
                        SpannableStringBuilder spannable = new SpannableStringBuilder("Disponible dans les boîtes à livres:\n");

                        for (Box box : matchedBoxes) {
                            String boxName = "- " + box.getName() + "\n";
                            int start = spannable.length();
                            spannable.append(boxName);
                            int end = spannable.length();

                            ClickableSpan clickableSpan = new ClickableSpan() {
                                @Override
                                public void onClick(View widget) {
                                    Intent intent = new Intent(BookDetailActivity.this, BookListActivity.class);
                                    intent.putExtra("box_id", box.getId());
                                    startActivity(intent);
                                }
                            };
                            spannable.setSpan(clickableSpan, start, end - 1, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                        }

                        bookBoxes.setText(spannable);
                        bookBoxes.setMovementMethod(LinkMovementMethod.getInstance());
                    } else {
                        bookBoxes.setText("Aucune box correspondante trouvée.");
                    }
                } else {
                    Toast.makeText(BookDetailActivity.this, "Erreur de récupération des boxes", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<List<Box>> call, Throwable t) {
                Toast.makeText(BookDetailActivity.this, "Erreur réseau lors de la récupération des boxes", Toast.LENGTH_SHORT).show();
            }
        });
    }
}

