package com.example.projet.models;

import com.google.gson.annotations.SerializedName;

public class User {
    private int id;
    private String name;
    private String email;

    @SerializedName("token") // Correspondance avec la réponse JSON de Laravel
    private String token;

    public User() {}

    public User(int id, String name, String email, String token) {
        this.id = id;
        this.name = name;
        this.email = email;
        this.token = token;
    }

    public int getId() { return id; }
    public String getName() { return name; }
    public String getEmail() { return email; }
    public String getToken() { return token; }

    public void setId(int id) { this.id = id; }
    public void setName(String name) { this.name = name; }
    public void setEmail(String email) { this.email = email; }
    public void setToken(String token) { this.token = token; }
}
