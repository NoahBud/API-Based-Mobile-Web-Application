package com.example.projet.network.request.response;

import com.example.projet.models.Copy;
import com.google.gson.annotations.SerializedName;
import java.util.List;

public class CopyResponse {

    @SerializedName("copies")
    private List<Copy> copies;

    public List<Copy> getCopies() {
        return copies;
    }

    public void setCopies(List<Copy> copies) {
        this.copies = copies;
    }
}
