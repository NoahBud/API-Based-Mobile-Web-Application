package com.example.projet.models;

import com.google.gson.annotations.SerializedName;

public class Editor {
    @SerializedName("id")
    private int id_editor;

    @SerializedName("name")
    private String name;

    @SerializedName("address")
    private String address;

    @SerializedName("mail")
    private String mail;

    @SerializedName("created_at")
    private String createdAt;

    @SerializedName("updated_at")
    private String updatedAt;


    public Editor() {}


    public Editor(int id_editor, String name, String address, String mail, String createdAt, String updatedAt) {
        this.id_editor = id_editor;
        this.name = name;
        this.address = address;
        this.mail = mail;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
    }


    public int getId_editor() {
        return id_editor;
    }

    public void setId_editor(int id_editor) {
        this.id_editor = id_editor;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getMail() {
        return mail;
    }

    public void setMail(String mail) {
        this.mail = mail;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }

    public String getUpdatedAt() {
        return updatedAt;
    }

    public void setUpdatedAt(String updatedAt) {
        this.updatedAt = updatedAt;
    }
}
