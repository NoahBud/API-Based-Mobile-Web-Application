package com.example.projet.network.request.response;

import com.example.projet.models.Box;
import com.google.gson.annotations.SerializedName;

public class BoxResponse {

    @SerializedName("box")
    private Box box;

    public Box getBox() {
        return box;
    }

    public void setBox(Box box) {
        this.box = box;
    }
}
