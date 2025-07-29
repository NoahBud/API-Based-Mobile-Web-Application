package com.example.projet.network.request.response;

import com.example.projet.models.User;

public class AuthResponse {
    private User user;
    private String token;

    public User getUser() {
        return user;
    }

    public String getToken() {
        return token;
    }
}
