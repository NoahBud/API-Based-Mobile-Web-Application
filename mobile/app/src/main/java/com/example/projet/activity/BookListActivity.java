package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.widget.Button;
import android.widget.SearchView;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.projet.R;
import com.example.projet.adapter.BookAdapter;
import com.example.projet.models.Book;
import com.example.projet.models.Copy;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import com.example.projet.network.request.response.BookResponse;
import com.example.projet.network.request.response.CopyResponse;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;
import retrofit2.Call;
import retrofit2.Response;
import android.util.Log;

public class BookListActivity extends AppCompatActivity {

    private RecyclerView recyclerView;
    private BookAdapter bookAdapter;
    private ApiService apiService;
    private Button backButton;
    private Button addButton;
    private List<Book> books = new ArrayList<>();
    private List<Integer> bookIdList = new ArrayList<>();
    private ExecutorService executorService = Executors.newFixedThreadPool(5);
    private Handler mainHandler = new Handler(Looper.getMainLooper());

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book_list);

        apiService = ApiClient.getClient(this).create(ApiService.class);
        recyclerView = findViewById(R.id.recyclerView);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        backButton = findViewById(R.id.backButton);
        addButton = findViewById(R.id.addButton);

        backButton.setOnClickListener(v -> {
            startActivity(new Intent(BookListActivity.this, BoxListActivity.class));
            finish();
        });

        addButton.setOnClickListener(v -> {
            int boxId = getIntent().getIntExtra("box_id", -1);
            Log.d("BookListActivity", "box_id reçu : " + boxId);

            Intent intent = new Intent(BookListActivity.this, AddBookOptionActivity.class);
            intent.putExtra("box_id", boxId);
            startActivity(intent);
        });

        SearchView searchView = findViewById(R.id.searchView);
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                if (bookAdapter != null) {
                    bookAdapter.filterList(newText);
                }
                return true;
            }
        });

        int boxId = getIntent().getIntExtra("box_id", -1);
        Log.d("BookListActivity", "box_id reçu : " + boxId);
        if (boxId != -1) {
            fetchCopiesForBox(boxId);
        }
    }

    private List<Integer> extractBookIdsFromCopies(List<Copy> copies) {
        List<Integer> bookIdList = new ArrayList<>();
        for (Copy copy : copies) {
            if (copy.getBookId() != -1 && copy.getDisponibilite() == 1) {
                bookIdList.add(copy.getBookId());
                Log.d("BookListActivity", "Copie récupérée - ID Livre: " + copy.getBookId() + ", Disponibilité: " + copy.getDisponibilite());
            }
        }
        Log.d("BookListActivity", "Total des livres extraits : " + bookIdList.size());
        return bookIdList;
    }

    private void fetchCopiesForBox(int boxId) {
        apiService.getCopies(boxId).enqueue(new retrofit2.Callback<CopyResponse>() {
            @Override
            public void onResponse(Call<CopyResponse> call, Response<CopyResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Copy> allCopies = response.body().getCopies();
                    bookIdList = extractBookIdsFromCopies(allCopies);
                    if (!bookIdList.isEmpty()) {
                        fetchBooksDetails(bookIdList, boxId);
                    }
                } else {
                    Log.e("BookListActivity", "Réponse échouée, code: " + response.code());
                }
            }

            @Override
            public void onFailure(Call<CopyResponse> call, Throwable t) {
                Log.e("BookListActivity", "onFailure appelé : " + t.getMessage());
            }
        });
    }

    private Book fetchBookDetailsSync(int bookId) {
        try {
            Response<BookResponse> response = apiService.getBookById(bookId).execute();
            return response.isSuccessful() ? response.body().getBook() : null;
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }

    private void fetchBooksDetails(List<Integer> bookIdList, int boxId) {
        Log.d("BookListActivity", "fetchBooksDetails appelé avec IDs: " + bookIdList + " et box ID: " + boxId);

        List<Future<Book>> futures = new ArrayList<>();
        for (int bookId : bookIdList) {
            futures.add(executorService.submit(() -> fetchBookDetailsSync(bookId)));
        }

        executorService.shutdown();

        new Thread(() -> {
            List<Book> fetchedBooks = new ArrayList<>();
            try {
                for (Future<Book> future : futures) {
                    Book book = future.get();
                    if (book != null) {
                        Log.d("BookListActivity", "Livre récupéré : " + book.getName());
                        fetchedBooks.add(book);
                    }
                }
                mainHandler.post(() -> {
                    books.addAll(fetchedBooks);
                    Log.d("BookListActivity", "setupRecyclerView appelé avec " + books.size() + " livres et box ID: " + boxId);
                    setupRecyclerView(books, boxId);


                    MainActivity.booksList = books;
                });

            } catch (Exception e) {
                e.printStackTrace();
            }
        }).start();
    }

    private void setupRecyclerView(List<Book> books, int boxId) {
        Log.d("BookListActivity", "setupRecyclerView appelé avec " + books.size() + " livres et box ID: " + boxId);

        bookAdapter = new BookAdapter(books, book -> {
            Intent intent = new Intent(BookListActivity.this, BookDetailActivity.class);
            intent.putExtra("book_id", book.getId());
            intent.putExtra("box_id", boxId);
            startActivity(intent);
        });
        recyclerView.setAdapter(bookAdapter);
    }
}