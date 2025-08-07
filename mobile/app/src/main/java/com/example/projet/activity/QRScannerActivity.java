package com.example.projet.activity;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.example.projet.R;
import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

public class QRScannerActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_qrscanner);

        IntentIntegrator integrator = new IntentIntegrator(this);
        integrator.setDesiredBarcodeFormats(IntentIntegrator.QR_CODE);
        integrator.setPrompt("Scannez un QR code");
        integrator.setBeepEnabled(true);
        integrator.setBarcodeImageEnabled(true);
        integrator.initiateScan();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        IntentResult result = IntentIntegrator.parseActivityResult(requestCode, resultCode, data);
        if (result != null) {
            String scannedData = result.getContents();
            if (scannedData != null) {
                Toast.makeText(this, "QR code scanné : " + scannedData, Toast.LENGTH_SHORT).show();
                // Supprimer le préfixe "box:" s'il existe
                scannedData = scannedData.trim();
                if (scannedData.startsWith("box:")) {
                    scannedData = scannedData.substring(4).trim();
                }
                try {
                    int boxIdValue = Integer.parseInt(scannedData);
                    Intent intent = new Intent(QRScannerActivity.this, BookListActivity.class);
                    intent.putExtra("box_id", boxIdValue); // Clé cohérente "box_id"
                    startActivity(intent);
                    finish();
                } catch (NumberFormatException e) {
                    Toast.makeText(this, "QR code invalide", Toast.LENGTH_SHORT).show();
                    finish();
                }
            } else {
                Toast.makeText(this, "Scan annulé", Toast.LENGTH_SHORT).show();
                finish();
            }
        } else {
            super.onActivityResult(requestCode, resultCode, data);
        }
    }
}