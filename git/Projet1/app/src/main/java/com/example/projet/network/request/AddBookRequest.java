package com.example.projet.network.request;

import java.util.List;

public class AddBookRequest {
    private String ISBN;
    private String name;
    private String genre;
    private String description;
    private int editor_id;
    private List<String> authors;


    public AddBookRequest(String ISBN, String name, String genre, String description, int editor_id, List<String> authors) {
        this.ISBN = ISBN;
        this.name = name;
        this.genre = genre;
        this.description = description;
        this.editor_id = editor_id;
        this.authors = authors;
    }


    public String getISBN() {
        return ISBN;
    }

    public void setISBN(String ISBN) {
        this.ISBN = ISBN;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getGenre() {
        return genre;
    }

    public void setGenre(String genre) {
        this.genre = genre;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public int getEditor_id() {
        return editor_id;
    }

    public void setEditor_id(int editor_id) {
        this.editor_id = editor_id;
    }

    public List<String> getAuthors() {
        return authors;
    }

    public void setAuthors(List<String> authors) {
        this.authors = authors;
    }
}
