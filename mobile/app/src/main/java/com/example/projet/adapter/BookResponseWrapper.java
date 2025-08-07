package com.example.projet.adapter;

import com.example.projet.models.Author;
import com.example.projet.models.Book;

import java.util.List;

public class BookResponseWrapper {
    private Book book_id;
    private List<Author> authors;
    private String message;

    // Constructeur par défaut
    public BookResponseWrapper() {
    }

    // Getter et setter pour book_id
    public Book getBook() {
        return book_id;
    }

    public void setBook(Book book_id) {
        this.book_id = book_id;
    }

    // Getter et setter pour authors
    public List<Author> getAuthors() {
        return authors;
    }

    public void setAuthors(List<Author> authors) {
        this.authors = authors;
    }

    // Getter et setter pour message
    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }
}