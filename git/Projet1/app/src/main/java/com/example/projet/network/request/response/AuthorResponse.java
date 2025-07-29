package com.example.projet.network.request.response;

import com.example.projet.models.Author;

import java.util.List;

public class AuthorResponse {
    private List<Author> authors;

    public List<Author> getAuthors() {
        return authors;
    }

    public void setAuthors(List<Author> authors) {
        this.authors = authors;
    }
}
