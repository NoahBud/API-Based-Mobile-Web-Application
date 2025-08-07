package com.example.projet.network.request;

import android.Manifest;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.content.Context;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Build;
import android.widget.Toast;
import androidx.core.app.ActivityCompat;
import androidx.core.app.NotificationCompat;
import androidx.core.app.NotificationManagerCompat;
import androidx.core.content.ContextCompat;
import com.example.projet.R;
import com.example.projet.models.Box;
import com.example.projet.network.ApiClient;
import com.example.projet.network.ApiService;
import org.osmdroid.util.GeoPoint;
import org.osmdroid.views.MapView;
import org.osmdroid.views.overlay.Marker;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class GestionBoites {
    private Context context;
    private LocationManager locationManager;
    private boolean isNotificationSent = false;
    private List<Box> boxes;
    private MapView mapView;

    public interface OnBoxesFetchedListener {
        void onBoxesFetched(List<Box> boxes);
    }

    public GestionBoites(Context context, MapView mapView) {
        this.context = context;
        this.mapView = mapView;
        locationManager = (LocationManager) context.getSystemService(Context.LOCATION_SERVICE);
        createNotificationChannel();
    }

    public void fetchBoxes(OnBoxesFetchedListener listener) {
        ApiService apiService = ApiClient.getClient(context).create(ApiService.class);
        Call<List<Box>> call = apiService.getAllBoxes();
        call.enqueue(new Callback<List<Box>>() {
            @Override
            public void onResponse(Call<List<Box>> call, Response<List<Box>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    boxes = response.body();
                    listener.onBoxesFetched(boxes); // Appel du callback avec les boîtes récupérées
                    Toast.makeText(context, "Données récupérées avec succès", Toast.LENGTH_SHORT).show();
                } else {
                    Toast.makeText(context, "Erreur lors de la récupération des données", Toast.LENGTH_SHORT).show();
                }
            }
            @Override
            public void onFailure(Call<List<Box>> call, Throwable t) {
                Toast.makeText(context, "Échec de la connexion à l'API", Toast.LENGTH_SHORT).show();
            }
        });
    }


    private Bitmap resizeDrawable(Drawable drawable, int newWidth, int newHeight) {

        Bitmap originalBitmap = Bitmap.createBitmap(drawable.getIntrinsicWidth(), drawable.getIntrinsicHeight(), Bitmap.Config.ARGB_8888);
        Canvas canvas = new Canvas(originalBitmap);
        drawable.setBounds(0, 0, canvas.getWidth(), canvas.getHeight());
        drawable.draw(canvas);

        return Bitmap.createScaledBitmap(originalBitmap, newWidth, newHeight, true);
    }

    public void displayBoxesOnMap(List<Box> boxes) {
        if (boxes != null && mapView != null) {
            mapView.getOverlays().clear();
            Drawable originalIcon = ContextCompat.getDrawable(context, R.drawable.ic_location);
            if (originalIcon != null) {
                int defaultWidth = originalIcon.getIntrinsicWidth();
                int defaultHeight = originalIcon.getIntrinsicHeight();
                int newWidth = defaultWidth * 2;
                int newHeight = defaultHeight * 2;

                for (Box box : boxes) {
                    GeoPoint boxLocation = new GeoPoint(box.getLatitude(), box.getLongitude());
                    Marker boxMarker = new Marker(mapView);
                    boxMarker.setPosition(boxLocation);
                    boxMarker.setTitle(box.getName());
                    boxMarker.setSnippet("Adresse : " + box.getAddress());
                    Bitmap scaledBitmap = resizeDrawable(originalIcon, newWidth, newHeight);
                    BitmapDrawable markerDrawable = new BitmapDrawable(context.getResources(), scaledBitmap);
                    markerDrawable.setTint(ContextCompat.getColor(context, R.color.red));
                    boxMarker.setIcon(markerDrawable);
                    boxMarker.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
                    mapView.getOverlays().add(boxMarker);
                }
                mapView.invalidate();
            }
        }
    }

    public void initializeLocationUpdates() {
        if (ActivityCompat.checkSelfPermission(context, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            return;
        }
        locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, new LocationListener() {
            @Override
            public void onLocationChanged(Location location) {
                double userLatitude = location.getLatitude();
                double userLongitude = location.getLongitude();
                boolean isNearBox = false;
                if (boxes != null) {
                    for (Box box : boxes) {
                        float[] results = new float[1];
                        Location.distanceBetween(userLatitude, userLongitude, box.getLatitude(), box.getLongitude(), results);
                        float distance = results[0];
                        if (distance <= 100) {
                            isNearBox = true;
                            if (!isNotificationSent) {
                                sendNotification();
                                isNotificationSent = true;
                            }
                            break;
                        }
                    }
                }
                if (!isNearBox) {
                    isNotificationSent = false;
                }
            }
            @Override
            public void onStatusChanged(String provider, int status, android.os.Bundle extras) {}
            @Override
            public void onProviderEnabled(String provider) {}
            @Override
            public void onProviderDisabled(String provider) {}
        });
    }

    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel("default", "Default Channel", NotificationManager.IMPORTANCE_HIGH);
            channel.setDescription("Notifications pour la boîte à livres");
            NotificationManager notificationManager = context.getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(channel);
        }
    }

    private void sendNotification() {
        NotificationCompat.Builder builder = new NotificationCompat.Builder(context, "default")
                .setSmallIcon(R.drawable.e_box_logo)
                .setContentTitle("Boîte à livres à proximité")
                .setContentText("Vous êtes à moins de 100m d'une boîte à livres!")
                .setPriority(NotificationCompat.PRIORITY_MAX)
                .setAutoCancel(true);
        NotificationManagerCompat notificationManager = NotificationManagerCompat.from(context);
        if (ActivityCompat.checkSelfPermission(context, Manifest.permission.POST_NOTIFICATIONS) == PackageManager.PERMISSION_GRANTED) {
            notificationManager.notify(1, builder.build());
        }
    }
}