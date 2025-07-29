package com.example.projet.network.request;
import com.google.gson.annotations.SerializedName;

public class BoxIdRequest {
    @SerializedName("box_id")
    private int boxId;

    public BoxIdRequest(int boxId) {
        this.boxId = boxId;
    }

    public int getBoxId() {
        return boxId;
    }

    public void setBoxId(int boxId) {
        this.boxId = boxId;
    }
}
