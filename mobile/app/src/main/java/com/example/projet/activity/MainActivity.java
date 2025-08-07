package com.example.projet.activity;

import android.Manifest;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.database.MatrixCursor;
import android.os.Build;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.SearchView;
import android.widget.SimpleCursorAdapter;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import com.example.projet.R;
import com.example.projet.models.Box;
import com.example.projet.models.Book;
import com.example.projet.network.request.GestionBoites;
import com.google.android.datatransport.backend.cct.BuildConfig;
import org.osmdroid.config.Configuration;
import org.osmdroid.tileprovider.tilesource.TileSourceFactory;
import org.osmdroid.util.GeoPoint;
import org.osmdroid.views.MapView;
import org.osmdroid.views.overlay.Marker;
import java.util.ArrayList;
import java.util.List;

public class MainActivity extends AppCompatActivity {
    private Button btnConnexion, btnLogout, btnQRCode, btnBox;
    private ImageView menuIcon;
    private LinearLayout menuContainer;
    private SearchView searchBar;
    private boolean isMenuOpen = false;
    private GestionBoites gestionBoites;
    private ImageView btnProfile;
    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1;
    private List<Box> boxesList;
    static List<Book> booksList = new ArrayList<>();
    private MapView mapView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Configuration.getInstance().setUserAgentValue(BuildConfig.APPLICATION_ID);
        Configuration.getInstance().load(getApplicationContext(),
                PreferenceManager.getDefaultSharedPreferences(getApplicationContext()));

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this,
                    new String[]{Manifest.permission.ACCESS_FINE_LOCATION},
                    LOCATION_PERMISSION_REQUEST_CODE);
        }

        setContentView(R.layout.activity_main);

        menuIcon = findViewById(R.id.menuIcon);
        menuContainer = findViewById(R.id.menuContainer);
        btnConnexion = findViewById(R.id.btnConnexion);
        btnLogout = findViewById(R.id.btnLogout);
        btnQRCode = findViewById(R.id.btnQRCode);
        btnBox = findViewById(R.id.btnVoirBoxes);
        btnProfile = findViewById(R.id.btnProfile);
        searchBar = findViewById(R.id.searchBar);

        menuContainer.setVisibility(android.view.View.GONE);

        SharedPreferences sharedPreferences = getSharedPreferences("MyPrefs", MODE_PRIVATE);
        boolean isLoggedIn = sharedPreferences.getBoolean("isLoggedIn", false);
        btnConnexion.setVisibility(isLoggedIn ? android.view.View.GONE : android.view.View.VISIBLE);
        btnLogout.setVisibility(isLoggedIn ? android.view.View.VISIBLE : android.view.View.GONE);
        btnProfile.setEnabled(isLoggedIn);
        btnProfile.setAlpha(isLoggedIn ? 1.0f : 0.5f);

        final Animation slideIn = AnimationUtils.loadAnimation(this, R.anim.slide_in_left);
        final Animation slideOut = AnimationUtils.loadAnimation(this, R.anim.slide_in_right);
        final Animation fadeIn = AnimationUtils.loadAnimation(this, R.anim.fade_in_second);

        menuIcon.setOnClickListener(v -> toggleMenu(fadeIn, slideIn, slideOut));

        btnConnexion.setOnClickListener(v -> startActivity(new Intent(MainActivity.this, LoginActivity.class)));
        btnQRCode.setOnClickListener(v -> startActivity(new Intent(MainActivity.this, QRScannerActivity.class)));
        btnBox.setOnClickListener(v -> startActivity(new Intent(MainActivity.this, BoxListActivity.class)));
        btnLogout.setOnClickListener(v -> {
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putBoolean("isLoggedIn", false);
            editor.apply();
            btnConnexion.setVisibility(android.view.View.VISIBLE);
            btnLogout.setVisibility(android.view.View.GONE);
            btnProfile.setEnabled(false);
            btnProfile.setAlpha(0.5f);
            toggleMenu(fadeIn, slideIn, slideOut);
        });
        btnProfile.setOnClickListener(v -> {
            Intent intent = new Intent(MainActivity.this, UserProfileActivity.class);
            startActivity(intent);
        });


        if (booksList == null) {
            booksList = new ArrayList<>();
        }

        searchBar.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                handleSearch(query);
                return true;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                showSuggestions(newText);
                return true;
            }
        });


        searchBar.setOnSuggestionListener(new SearchView.OnSuggestionListener() {
            @Override
            public boolean onSuggestionClick(int position) {
                MatrixCursor cursor = (MatrixCursor) searchBar.getSuggestionsAdapter().getItem(position);
                String suggestion = cursor.getString(1);


                if (suggestion.startsWith("Boîte : ")) {
                    String boxName = suggestion.replace("Boîte : ", "");
                    handleBoxSuggestionClick(boxName);
                } else if (suggestion.startsWith("Livre : ")) {
                    String bookName = suggestion.replace("Livre : ", "");
                    handleBookSuggestionClick(bookName);
                }
                return true;
            }

            @Override
            public boolean onSuggestionSelect(int position) {
                return false;
            }
        });

        mapView = findViewById(R.id.mapView);
        if (mapView != null) {
            mapView.setUseDataConnection(true);
            mapView.setTileSource(TileSourceFactory.MAPNIK);
            mapView.setMultiTouchControls(true);
            GeoPoint startPoint = new GeoPoint(48.8566, 2.3522);
            mapView.getController().setCenter(startPoint);
            mapView.getController().setZoom(12.0);
        } else {
            Toast.makeText(this, "Erreur : mapView est null", Toast.LENGTH_SHORT).show();
        }

        gestionBoites = new GestionBoites(this, mapView);
        gestionBoites.fetchBoxes(new GestionBoites.OnBoxesFetchedListener() {
            @Override
            public void onBoxesFetched(List<Box> boxes) {
                if (boxes != null && !boxes.isEmpty()) {
                    Toast.makeText(MainActivity.this, "Boîtes récupérées : " + boxes.size(), Toast.LENGTH_SHORT).show();
                    boxesList = boxes;
                    gestionBoites.displayBoxesOnMap(boxes);
                } else {
                    Toast.makeText(MainActivity.this, "Aucune boîte récupérée", Toast.LENGTH_SHORT).show();
                }
            }
        });
        gestionBoites.initializeLocationUpdates();
    }

    private void handleSearch(String query) {
        if (boxesList != null && !boxesList.isEmpty()) {
            Box matchedBox = null;
            for (Box box : boxesList) {
                if (box.getName().equalsIgnoreCase(query) || box.getAddress().equalsIgnoreCase(query)) {
                    matchedBox = box;
                    break;
                }
            }
            if (matchedBox != null) {
                GeoPoint boxPoint = new GeoPoint(matchedBox.getLatitude(), matchedBox.getLongitude());
                mapView.getController().animateTo(boxPoint);
                Toast.makeText(MainActivity.this, "Boîte trouvée : " + matchedBox.getName(), Toast.LENGTH_SHORT).show();
            } else {
                Toast.makeText(MainActivity.this, "Aucune boîte correspondante", Toast.LENGTH_SHORT).show();
            }
        } else {
            Toast.makeText(MainActivity.this, "Liste des boîtes non chargée", Toast.LENGTH_SHORT).show();
        }
    }

    private void showSuggestions(String query) {
        String[] columns = new String[]{"_id", "suggestion"};
        MatrixCursor cursor = new MatrixCursor(columns);
        int id = 0;

        if (boxesList != null) {
            for (Box box : boxesList) {
                if (box.getName().toLowerCase().contains(query.toLowerCase())) {
                    cursor.addRow(new Object[]{id++, "Boîte : " + box.getName()});
                }
            }
        }
        if (booksList != null) {
            for (Book book : booksList) {
                if (book.getName().toLowerCase().contains(query.toLowerCase())) {
                    cursor.addRow(new Object[]{id++, "Livre : " + book.getName()});
                }
            }
        }

        String[] from = {"suggestion"};
        int[] to = {android.R.id.text1};
        SimpleCursorAdapter adapter = new SimpleCursorAdapter(this,
                android.R.layout.simple_list_item_1, cursor, from, to, 0);

        searchBar.setSuggestionsAdapter(adapter);
    }

    private void handleBoxSuggestionClick(String boxName) {
        if (boxesList != null) {
            for (Box box : boxesList) {
                if (box.getName().equalsIgnoreCase(boxName)) {
                    GeoPoint boxPoint = new GeoPoint(box.getLatitude(), box.getLongitude());
                    mapView.getController().animateTo(boxPoint);
                    break;
                }
            }
        }
    }

    private void handleBookSuggestionClick(String bookName) {
        if (booksList != null) {
            for (Book book : booksList) {
                if (book.getName().equalsIgnoreCase(bookName)) {
                    Intent intent = new Intent(MainActivity.this, BookDetailActivity.class);
                    intent.putExtra("book_id", book.getId());
                    startActivity(intent);
                    break;
                }
            }
        }
    }

    private void toggleMenu(Animation fadeIn, Animation slideIn, Animation slideOut) {
        if (isMenuOpen) {
            menuContainer.startAnimation(slideOut);
            menuContainer.setVisibility(View.GONE);
        } else {
            menuContainer.setVisibility(View.VISIBLE);
            menuContainer.startAnimation(fadeIn);
        }
        isMenuOpen = !isMenuOpen;
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (mapView != null)
            mapView.onResume();
    }

    @Override
    protected void onPause() {
        super.onPause();
        if (mapView != null)
            mapView.onPause();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (mapView != null)
            mapView.onDetach();
    }
}