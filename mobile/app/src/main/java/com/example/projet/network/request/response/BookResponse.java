package com.example.projet.network.request.response;

import com.example.projet.models.Book;
import com.google.gson.annotations.SerializedName;

public class BookResponse {

    @SerializedName("book")
    private Book book;

    public Book getBook() {
        return book;
    }

    public void setBook(Book book) {
        this.book = book;
    }
}
