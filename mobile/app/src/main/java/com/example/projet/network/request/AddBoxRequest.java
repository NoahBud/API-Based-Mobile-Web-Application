package com.example.projet.network.request;

public class AddBoxRequest {
    private String name;
    private String address;
    private String etat;

    public AddBoxRequest(String name, String address, String etat) {
        this.name = name;
        this.address = address;
        this.etat = etat;
    }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public String getAddress() { return address; }
    public void setAddress(String address) { this.address = address; }

    public String getEtat() { return etat; }
    public void setEtat(String etat) { this.etat = etat; }
}
