package com.example.projet.adapter;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.projet.R;
import com.example.projet.models.Box;
import java.util.ArrayList;
import java.util.List;

public class BoxAdapter extends RecyclerView.Adapter<BoxAdapter.BoxViewHolder> {
    private List<Box> boxList;
    private List<Box> originalBoxList;
    private OnBoxClickListener onBoxClickListener;

    public interface OnBoxClickListener {
        void onBoxClick(Box box);
    }

    public BoxAdapter(List<Box> boxList, OnBoxClickListener listener) {
        this.boxList = new ArrayList<>(boxList);
        this.originalBoxList = new ArrayList<>(boxList);
        this.onBoxClickListener = listener;
    }

    @NonNull
    @Override
    public BoxViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_box, parent, false);
        return new BoxViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull BoxViewHolder holder, int position) {
        Box box = boxList.get(position);

        holder.nameTextView.setText(box.getName());
        holder.idTextView.setText("ID: " + box.getId());
        holder.addressTextView.setText(box.getAddress());

        holder.itemView.setOnClickListener(v -> {
            if (onBoxClickListener != null) {
                onBoxClickListener.onBoxClick(box);
            }
        });
    }

    @Override
    public int getItemCount() {
        return boxList.size();
    }


    public void filterList(String query) {
        List<Box> filteredList = new ArrayList<>();
        if (query.isEmpty()) {

            filteredList.addAll(originalBoxList);
        } else {

            for (Box box : originalBoxList) {
                if (box.getName().toLowerCase().contains(query.toLowerCase())) {
                    filteredList.add(box);
                }
            }
        }
        this.boxList = filteredList;
        notifyDataSetChanged();
    }

    // ViewHolder class
    public static class BoxViewHolder extends RecyclerView.ViewHolder {
        TextView idTextView, nameTextView, addressTextView;

        public BoxViewHolder(View itemView) {
            super(itemView);
            idTextView = itemView.findViewById(R.id.textId);
            nameTextView = itemView.findViewById(R.id.textName);
            addressTextView = itemView.findViewById(R.id.textAddress);
        }
    }
}