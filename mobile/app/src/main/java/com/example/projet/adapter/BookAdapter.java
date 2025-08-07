package com.example.projet.adapter;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.recyclerview.widget.RecyclerView;
import com.example.projet.R;
import com.example.projet.models.Book;
import java.util.ArrayList;
import java.util.List;

public class BookAdapter extends RecyclerView.Adapter<BookAdapter.BookViewHolder> {
    private List<Book> bookList;
    private List<Book> originalBookList;
    private OnBookClickListener onBookClickListener;

    public interface OnBookClickListener {
        void onBookClick(Book book);
    }

    public BookAdapter(List<Book> bookList, OnBookClickListener listener) {
        this.bookList = new ArrayList<>(bookList);
        this.originalBookList = new ArrayList<>(bookList);
        this.onBookClickListener = listener;
    }

    @Override
    public BookViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_book, parent, false);
        return new BookViewHolder(view);
    }

    @Override
    public void onBindViewHolder(BookViewHolder holder, int position) {
        Book book = bookList.get(position);

        holder.titleTextView.setText(book.getName());
        holder.isbnTextView.setText("ISBN: " + book.getISBN());
        holder.genreTextView.setText("Genre: " + book.getGenre());
        holder.descriptionTextView.setText(book.getDescription());

        holder.itemView.setOnClickListener(v -> {
            if (onBookClickListener != null) {
                onBookClickListener.onBookClick(book);
            }
        });
    }

    @Override
    public int getItemCount() {
        return bookList.size();
    }


    public void filterList(String query) {
        List<Book> filteredList = new ArrayList<>();
        if (query.isEmpty()) {
            filteredList.addAll(originalBookList);
        } else {
            for (Book book : originalBookList) {
                if (book.getName().toLowerCase().contains(query.toLowerCase())) {
                    filteredList.add(book);
                }
            }
        }
        this.bookList = filteredList;
        notifyDataSetChanged();
    }

    public static class BookViewHolder extends RecyclerView.ViewHolder {
        TextView titleTextView, isbnTextView, genreTextView, descriptionTextView;

        public BookViewHolder(View itemView) {
            super(itemView);
            titleTextView = itemView.findViewById(R.id.textTitle);
            isbnTextView = itemView.findViewById(R.id.textIsbn);
            genreTextView = itemView.findViewById(R.id.textGenre);
            descriptionTextView = itemView.findViewById(R.id.textDescription);
        }
    }
}