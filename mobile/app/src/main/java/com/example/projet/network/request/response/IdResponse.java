package com.example.projet.network.request.response;

public class IdResponse {

    private int book_id;
    private String message;


    public IdResponse(int book_id, String message) {
        this.book_id = book_id;
        this.message = message;
    }


    public int getBookId() {
        return book_id;
    }

    public void setBookId(int book_id) {
        this.book_id = book_id;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }
}
